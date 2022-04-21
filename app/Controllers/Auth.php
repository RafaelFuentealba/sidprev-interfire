<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\InterfazModel;
use App\Libraries\Hash;

class Auth extends BaseController
{   
    private $db;

    public function __construct() {
        helper('Form');
        helper(['url', 'form']);
        $this->db = \Config\Database::connect('default');
    }
    
    /** return the view for login */
    public function login() {
        return view('auth/templates/header').view('auth/login');
    }

    /** return the view for register */
    public function register() {
        return view('auth/templates/header').view('auth/register');
    }

    /** saves a new user on database */
    public function save() {
        $userModel = new UserModel($this->db);

        $validation = $this->validate([
            'firstname' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'El nombre es requerido',
                    'max_length' => 'El nombre no puede contener más de 100 caracteres'
                ]
                ],
            'lastname' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'El apellido es requerido',
                    'max_length' => 'El apellido no puede contener más de 100 caracteres'
                ]
                ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[usuarios.email]|max_length[150]',
                'errors' => [
                    'required' => 'El correo electrónico es requerido',
                    'valid_email' => 'El correo electrónico no es válido',
                    'is_unique' => 'El correo electrónico ya está registrado',
                    'max_length' => 'El correo electrónico no puede contener más de 150 caracteres'
                ]
                ],
            'password' => [
                'rules' => 'required|min_length[8]|max_length[25]',
                'errors' => [
                    'required' => 'La contraseña es requerida',
                    'min_length' => 'La contraseña no puede contener menos de 8 caracteres',
                    'max_length' => 'La contraseña no puede contener más de 25 caracteres'
                ]
                ],
            'cpassword' => [
                'rules' => 'required|min_length[8]|max_length[25]|matches[password]',
                'errors' => [
                    'required' => 'La contraseña es requerida',
                    'min_length' => 'La contraseña no puede contener menos de 8 caracteres',
                    'max_length' => 'La contraseña no puede contener más de 25 caracteres',
                    'matches' => 'La contraseña ingresada no coincide con la contraseña anterior'
                ]
            ]
        ]);

        if (!$validation) {
            return view('auth/templates/header').view('auth/register', ['validation' => $this->validator]);
        } else {
            $firstname = $this->request->getPost('firstname');
            $lastname = $this->request->getPost('lastname');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $userData = array(
                'nombre' => $firstname,
                'apellido' => $lastname,
                'email' => $email,
                'clave' => Hash::make($password),
            );

            $saveUser = $userModel->insert($userData);
            if (!$saveUser) {
                return redirect()->back()->with('auth-fail', 'No se pudo completar el registro');
            } else {
                return redirect()->to('auth/ingresar')->with('auth-success', 'Tu registro se ha completado correctamente. El habilitador validará tu acceso.');
            }
        }
    }

    /** checks for user login on system */
    public function checkLogin() {
        $userModel = new UserModel($this->db);
        $interfazModel = new InterfazModel($this->db);

        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[usuarios.email]',
                'errors' => [
                    'required' => 'El correo electrónico es requerido',
                    'valid_email' => 'El correo electrónico no es válido',
                    'is_not_unique' => 'El correo electrónico no está registrado en el servicio',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]|max_length[25]',
                'errors' => [
                    'required' => 'La contraseña es requerida',
                    'min_length' => 'La contraseña debe contener un minimo de 8 caracteres',
                    'max_length' => 'La contraseña debe contener un maximo de 25 caracteres'
                ]
            ]
        ]);

        if (!$validation) {
            return view('auth/templates/header').view('auth/login', ['validation'=>$this->validator]);
        } else {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            
            $userLogin = $userModel->where('email', $email)->first();
            if ($userLogin['deleted'] == 'f') {
                $checkPassword = Hash::check($password, $userLogin['clave']);
                if (!$checkPassword) {
                    session()->setFlashdata('auth-fail', "La contraseña es incorrecta");
                    return redirect()->to('auth/ingresar')->withInput();
                } else {
                    $countInterfaces = $interfazModel->where('propietario_id', $userLogin['usuario_id'])->countAllResults();
                    session()->set('loggedUser', $userLogin['usuario_id']);
                    if ($countInterfaces >= 1) return redirect()->to('admin/gestion/listar');
                    else return redirect()->to('admin/interfaz/crear');
                }
            } else {
                session()->setFlashdata('auth-fail', "No se ha validado tu acceso al sistema");
                return redirect()->to('auth/ingresar')->withInput();
            }
        }
    }
    
    /** end the user's session in the system */
    public function logout() {
        if (session()->has('loggedUser')) {
            session()->remove('loggedUser');
            return  redirect()->to('auth/ingresar?acceso=out')->with('auth-fail', 'Sesión finalizada');
        }
    }

    /** return the view for recovery password via email */
    public function forgotPassword() {
        return view('auth/templates/header').view('auth/forgot-password.php');
    }

    /** send an email to the user for recover their password */
    public function sendMailTokenPassword() {
        $userModel = new UserModel($db);

        $validation = $this->validate([
            'email' => [
                'rules' => 'required|valid_email|is_not_unique[usuarios.email]',
                'errors' => [
                    'required' => 'El correo electrónico es requerido',
                    'valid_email' => 'El correo electrónico no es válido',
                    'is_not_unique' => 'El correo electrónico no está registrado en el servicio',
                ]
            ]
        ]);

        if (!$validation) {
            return view('auth/templates/header').view('auth/forgot-password', ['validation'=>$this->validator]);
        } else {
            $userEmail = $this->request->getPost('email');
            $userLogin = $userModel->where(['email' => $userEmail, 'deleted' => true])->first();
            if ($userLogin) {
                session()->setFlashdata('auth-forgot-password', "No se ha validado tu acceso al sistema");
                return redirect()->to('auth/clave/recuperar')->withInput();
            } else {
                $userLogin = $userModel->where(['email' => $userEmail, 'deleted' => false])->first();
                $userId = $userLogin['usuario_id'];
                $token = $userLogin['clave'];
                
                $email = \Config\Services::Email();
                $email->setFrom('rfuentealba@interfire.cl', 'Soporte Interfire SpA');
                $email->setTo($userEmail);
                $email->setSubject('Cambio de contraseña en SIDPREV');
                $logoFile = base_url().'/public/images/logo-interfire.png';
                $email->attach($logoFile);
                $cid = $email->setAttachmentCID($logoFile);
                $email->setMessage('
                    <div style="text-align: center;">
                        <a href="https://interfire.cl"><img src="cid:'. $cid .'" alt="" height="125" /></a> <br><br>
                    </div>
                    '.$userLogin['nombre'].' '.$userLogin['apellido'].', <br><br>
                    Hemos recibido una solicitud de cambio de contraseña asociada a tu cuenta en SIDPREV de Interfire. Por favor, dirígete a <a href="'.site_url('auth/clave/actualizar?id='.$userId.'&token='.$token).'">cambiar contraseña</a> para continuar.    
                ');
                
                if ($email->send()) {
                    session()->setFlashdata('auth-forgot-password-email', "Un enlace de recuperación ha sido enviado a tu correo electrónico");
                    return redirect()->to('auth/clave/recuperar')->withInput();
                } else {
                    session()->setFlashdata('auth-forgot-password', "Ocurrió un problema al enviar tu correo de recuperación. Inténtalo más tarde.");
                    return redirect()->to('auth/clave/recuperar')->withInput();
                }               
            }
        }
    }

    /** return the view to reset password */
    public function resetPassword() {
        $userModel = new UserModel($db);

        $url = $_SERVER['REQUEST_URI']; 
        $url_components = parse_url($url); 
        parse_str($url_components['query'], $params); 
        $id = $params['id'];
        $token = $params['token'];
        $userToReset = $userModel->where(['usuario_id' => $id, 'clave' => $token, 'deleted' => false])->first();

        if (!empty($id) && !empty($token) && !empty($userToReset)) {
            return view('auth/templates/header').view('auth/reset-password', ['user' => $userToReset]);
        } else {
            session()->setFlashdata('auth-forgot-password', 'El token para restablecer contraseña ha caducado. Inténtalo nuevamente.');
            return redirect()->to('auth/clave/recuperar')->withInput();
        }
    }

    /** update the password of user */
    public function updatePassword() {
        $userModel = new UserModel($db);

        $validation = $this->validate([
            'password' => [
                'rules' => 'required|min_length[8]|max_length[25]',
                'errors' => [
                    'required' => 'La contraseña es requerida',
                    'min_length' => 'La contraseña no puede contener menos de 8 caracteres',
                    'max_length' => 'La contraseña no puede contener más de 25 caracteres'
                ]
                ],
            'cpassword' => [
                'rules' => 'required|min_length[8]|max_length[25]|matches[password]',
                'errors' => [
                    'required' => 'La contraseña es requerida',
                    'min_length' => 'La contraseña no puede contener menos de 8 caracteres',
                    'max_length' => 'La contraseña no puede contener más de 25 caracteres',
                    'matches' => 'La contraseña ingresada no coincide con la contraseña anterior'
                ]
            ]
        ]);

        $url = $_SERVER['REQUEST_URI']; 
        $url_components = parse_url($url); 
        parse_str($url_components['query'], $params); 
        $id = $params['id'];
        $token = $params['token'];
        $userToReset = $userModel->where(['usuario_id' => $id, 'clave' => $token, 'deleted' => false])->first();

        if (!$validation) {
            return view('auth/templates/header').view('auth/reset-password', ['validation' => $this->validator, 'user' => $userToReset]);
        } else {
            $newPassword = $this->request->getPost('password');
            $userToReset['clave'] = Hash::make($newPassword);
            $userToUpdate = $userModel->update($userToReset['usuario_id'], $userToReset);
            if ($userToUpdate) {
                return redirect()->to('auth/ingresar')->with('success-password-update', 'La contraseña se actualizó correctamente');
            } else {
                session()->setFlashdata('fail-password-update', 'No se pudo actualizar la contraseña. Inténtalo más tarde.');
                return redirect()->to('auth/clave/actualizar?id='.$userToReset['usuario_id'].'&token='.$userToReset['clave'])->withInput();
            }
        }
    }
}
