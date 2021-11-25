<?php

namespace Ninja;

use SSF\Entity\UserEntity;

class Authentication
{
    private $users;
    private $usernameColumn;
    private $passwordColumn;
    
    private $user_entity;

    public function __construct(DatabaseTable $users, $usernameColumn, $passwordColumn)
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        $this->users = $users;
        $this->usernameColumn = $usernameColumn;
        $this->passwordColumn = $passwordColumn;
    }

    public function login($username, $password)
    {
        $user = $this->users->find($this->usernameColumn, strtolower($username));
        
        if (empty($user))
            return false;

        $passwordColumn = $this->passwordColumn;
        
        /** 
         * Hashed password
         */
        if (!password_verify($password, $user[0]->$passwordColumn))
            return false;
        
        session_regenerate_id();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $user[0]->$passwordColumn;

        return true;
    }

    /**
     * @throws NinjaException
     */
    public function register($username, $password, $display_name)
    {
        if (empty($username))
            throw new NinjaException('Vui lòng điền tên đăng nhập');
        
        if (empty($password))
            throw new NinjaException('Vui lòng điền mật khẩu');
        
        if (empty($display_name))
            throw new NinjaException('Vui lòng điền tên hiển thị');
        
        $user = $this->users->find($this->usernameColumn, $username);
        
        if ($user instanceof UserEntity)
            throw new NinjaException("Tên đăng nhập '$username' đã tồn tại trong hệ thống");

        $password = password_hash($password, PASSWORD_DEFAULT);

        return $this->users->save([
            UserEntity::KEY_USERNAME => $username,
            UserEntity::KEY_EMAIL => '',
            UserEntity::KEY_DISPLAY_NAME => $display_name,
            UserEntity::KEY_AVATAR => 'avatar1',
            UserEntity::KEY_PASSWORD => $password,
            UserEntity::KEY_TYPE => UserEntity::TYPE_USER,
        ]);
    }
    
    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        session_destroy();
        
        return true;
    }

    public function isLoggedIn()
    {
        if (empty($_SESSION['username'])) {
            return false;
        }

        $user = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']));

        if (empty($user)) {
            return false;
        }

        $passwordColumn = $this->passwordColumn;
        
        /**
         * Hashed Password
         *
         */
        if ($user[0]->$passwordColumn !== $_SESSION['password']) {
            return false;
        }
        
        return true;
    }

    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        if (!$this->user_entity) 
            $this->user_entity = $this->users->find($this->usernameColumn, strtolower($_SESSION['username']))[0];

        return $this->user_entity;
    }
    
    public function get_sid()
    {
        return session_id();
    }
    
    public function getUserId()
    {
        $user = $this->getUser() ?? null;
        
        if (!$user)
            return null;
        
        return $user->id;
    }
}
