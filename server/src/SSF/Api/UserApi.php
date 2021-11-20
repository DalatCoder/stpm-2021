<?php

namespace SSF\Api;

use Ninja\DatabaseTable;
use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Entity\UserEntity;

class UserApi
{
    use Jsonable;
    
    private $user_table;
    
    public function __construct(DatabaseTable $user_table)
    {
        $this->user_table = $user_table;
    }
    
    public function index()
    {
        $this->response_json([
            'status' => 'success',
            'data' => null
        ]);
    }
    
    public function store()
    {
        try {
            $json = $this->parse_json_from_request();

            $username = $json[UserEntity::KEY_USERNAME] ?? null;
            $email = $json[UserEntity::KEY_EMAIL] ?? null;
            $password = $json[UserEntity::KEY_PASSWORD] ?? null;
            $display_name = $json[UserEntity::KEY_DISPLAY_NAME] ?? null;
            $avatar = $json[UserEntity::KEY_AVATAR] ?? null;

            $existing = $this->user_table->find(UserEntity::KEY_USERNAME, $username);
            if (count($existing))
                throw new NinjaException('Tên người dùng đã tồn tại');

            $existing = $this->user_table->find(UserEntity::KEY_EMAIL, $email);
            if (count($existing))
                throw new NinjaException('Email đã tồn tại');
            
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            $new_user = $this->user_table->save([
                UserEntity::KEY_USERNAME => $username,
                UserEntity::KEY_EMAIL => $email,
                UserEntity::KEY_DISPLAY_NAME => $display_name,
                UserEntity::KEY_AVATAR => $avatar,
                UserEntity::KEY_PASSWORD => $password,
                UserEntity::KEY_TYPE => UserEntity::TYPE_USER,
            ]);
            
            $response_data = [
                'id' => $new_user->id,
                'username' => $new_user->username,
                'email' => $new_user->email,
                'display_name' => $new_user->display_name,
                'avatar' => $new_user->avatar
            ];
            
            $this->response_json([
                'status' => 'success',
                'data' => $response_data
            ], 200);
        }
        catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
}
