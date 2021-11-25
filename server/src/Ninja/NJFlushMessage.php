<?php

namespace Ninja;

class NJFlushMessage
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
    }
    
    public function have_messages()
    {
        $isset = isset($_SESSION['messages']) ?? null;
        
        if (!$isset)
            return false;
        
        $is_array = is_array($_SESSION['messages']) ?? null;
        if (!$is_array)
            return false;
        
        return true;
    }
    
    public function get($key)
    {
        if (!$this->have_messages())
            return null;
        
        return $_SESSION['messages'][$key] ?? null;
    }
    
    public function set($key, $value)
    {
        if (!$this->have_messages())
            $_SESSION['messages'] = [];
        
        $_SESSION['messages'][$key] = $value;
    }
    
    public function clean_at($key)
    {
        if ($this->have_messages())
            unset($_SESSION['messages'][$key]);
    }
    
    public function reset()
    {
        if ($this->have_messages())
            unset($_SESSION['messages']);
    }
}
