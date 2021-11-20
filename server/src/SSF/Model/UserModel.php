<?php

namespace SSF\Model;

use Ninja\DatabaseTable;
use Ninja\NinjaException;
use SSF\Entity\UserEntity;

class UserModel
{
    private $user_table;
    
    public function __construct(DatabaseTable $user_table)
    {
        $this->user_table = $user_table;
    }

    public function get_users()
    {
        return $this->user_table->findAll();
    }

    /**
     * @throws NinjaException
     */
    public function create_new_user($args)
    {
        $username = $args[UserEntity::KEY_USERNAME] ?? null;
        $email = $args[UserEntity::KEY_EMAIL] ?? null;
        $password = $args[UserEntity::KEY_PASSWORD] ?? null;
        $display_name = $args[UserEntity::KEY_DISPLAY_NAME] ?? null;
        $avatar = $args[UserEntity::KEY_AVATAR] ?? null;

        $existing = $this->user_table->find(UserEntity::KEY_USERNAME, $username);
        if (count($existing))
            throw new NinjaException('Tên người dùng đã tồn tại');

        $existing = $this->user_table->find(UserEntity::KEY_EMAIL, $email);
        if (count($existing))
            throw new NinjaException('Email đã tồn tại');

        $password = password_hash($password, PASSWORD_DEFAULT);

        return $this->user_table->save([
            UserEntity::KEY_USERNAME => $username,
            UserEntity::KEY_EMAIL => $email,
            UserEntity::KEY_DISPLAY_NAME => $display_name,
            UserEntity::KEY_AVATAR => $avatar,
            UserEntity::KEY_PASSWORD => $password,
            UserEntity::KEY_TYPE => UserEntity::TYPE_USER,
        ]);
    }
}
