<?php

namespace SSF\Entity;

class UserEntity
{
    const PRIMARY_KEY = 'id';
    const TABLE = 'user';
    const CLASS_NAME = '\\SSF\\Entity\\UserEntity';
    
    const KEY_ID = 'id';
    const KEY_TYPE = 'type';
    const KEY_USERNAME = 'username';
    const KEY_EMAIL = 'email';
    const KEY_PASSWORD = 'password';
    const KEY_DISPLAY_NAME = 'display_name';
    const KEY_AVATAR = 'avatar';
    const KEY_CREATED_AT = 'created_at';
    
    const TYPE_ADMIN = 'admin';
    const TYPE_USER = 'user';
    
    public $id;
    public $type;
    public $username;
    public $email;
    public $password;
    public $display_name;
    public $avatar;
    public $created_at;
}
