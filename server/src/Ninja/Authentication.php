<?php

namespace Ninja;

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
        
        error_log(print_r($user, true));

        if (empty($user))
            return false;

        $passwordColumn = $this->passwordColumn;
        
        /** 
         * Hashed password
         *
         */
        if (!password_verify($password, $user[0]->$passwordColumn))
            return false;
        
        session_regenerate_id();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $user[0]->$passwordColumn;

        return true;
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
}
