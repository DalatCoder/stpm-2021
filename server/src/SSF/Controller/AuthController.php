<?php

namespace SSF\Controller;

use Ninja\Authentication;
use Ninja\NinjaException;

class AuthController extends SSFBaseController
{
    private $authentication_helper;
    
    public function __construct(Authentication $authentication_helper)
    {
        parent::__construct();
        $this->authentication_helper = $authentication_helper;
    }

    public function show_login_form()
    {
        $this->view_handler->render('auth/login.html.php');
    }
    
    public function process_login()
    {
        try {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;

            if (is_null($username) || is_null($password))
                throw new NinjaException('Vui lòng điền đủ tên đăng nhập và mật khẩu');

            $is_succedded = $this->authentication_helper->login($username, $password);
            
            if (!$is_succedded)
                throw new NinjaException('Thông tin đăng nhập không hợp lệ');
            
            $this->route_redirect('/');
        }
        catch (NinjaException $exception) {
            $this->flush_handler->set('auth_error', $exception->getMessage());
            $this->route_redirect('/auth/login');
        }
    }
    
    public function show_register_form()
    {
        $this->view_handler->render('auth/register.html.php');
    }
    
    public function process_register()
    {
        try {
            $username = $_POST['username'] ?? null;
            $password = $_POST['password'] ?? null;
            $display_name = $_POST['display_name'] ?? null;

            $user = $this->authentication_helper->register($username, $password, $display_name);
            
            if (empty($user))
                throw new NinjaException('Có lỗi trong quá trình tạo mới người dùng, thử lại sau');
            
            $this->route_redirect('/auth/login');
        }
        catch (NinjaException $exception) {
            $this->flush_handler->set('auth_error', $exception->getMessage());
            $this->route_redirect('/auth/register');
        }
    }
    
    public function log_user_out()
    {
        $this->authentication_helper->logout();
        $this->route_redirect('/');
    }
}
