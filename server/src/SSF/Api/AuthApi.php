<?php

namespace SSF\Api;

use Ninja\Authentication;
use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Entity\UserEntity;
use SSF\Model\UserModel;

class AuthApi
{
    use Jsonable;

    private $authentication_helper;

    public function __construct(Authentication $authentication)
    {
        $this->authentication_helper = $authentication;
    }

    public function log_user_in()
    {
        try {
            $json = $this->parse_json_from_request();

            $username = $json['username'] ?? null;
            if (!$username)
                throw new NinjaException('Vui lòng điền tên đăng nhập');

            $password = $json['password'] ?? null;
            if (!$password)
                throw new NinjaException('Vui lòng điền mật khẩu');

            $is_success = $this->authentication_helper->login($username, $password);

            if (!$is_success)
                throw new NinjaException('Thông tin đăng nhập không hợp lệ, vui lòng kiểm tra lại');

            $sid = $this->authentication_helper->get_sid();
            $user = $this->authentication_helper->getUser();
            
            $response_json = [];
            $response_json[UserEntity::KEY_ID] = $user->{UserEntity::KEY_ID};
            $response_json[UserEntity::KEY_USERNAME] = $user->{UserEntity::KEY_USERNAME};
            $response_json[UserEntity::KEY_AVATAR] = $user->{UserEntity::KEY_AVATAR};
            $response_json[UserEntity::KEY_DISPLAY_NAME] = $user->{UserEntity::KEY_DISPLAY_NAME};
            $response_json[UserEntity::KEY_EMAIL] = $user->{UserEntity::KEY_EMAIL};
            $response_json[UserEntity::KEY_CREATED_AT] = $user->{UserEntity::KEY_CREATED_AT};
            
            $this->response_json([
                'status' => 'success',
                'data' => [
                    'sid' => $sid,
                    'user' => $response_json
                ]
            ]);
        } catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function log_user_out()
    {
        $this->authentication_helper->logout();

        $this->response_json([
            'status' => 'success',
            'data' => null
        ]);
    }
}
