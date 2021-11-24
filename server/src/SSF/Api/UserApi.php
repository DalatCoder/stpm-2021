<?php

namespace SSF\Api;

use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Model\UserModel;

class UserApi
{
    use Jsonable;
    
    private $user_model;
    
    public function __construct(UserModel $userModel)
    {
        $this->user_model = $userModel;
    }
    
    public function index()
    {
        $users = $this->user_model->get_users();
        
        $response_data = [];
        foreach ($users as $user) {
            $response_data[] = [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'display_name' => $user->display_name,
                'avatar' => $user->avatar
            ];
        }
        
        $this->response_json([
            'status' => 'success',
            'data' => $response_data
        ]);
    }
    
    public function store()
    {
        try {
            $json = $this->parse_json_from_request();
            
            $new_user = $this->user_model->create_new_user($json);

            $response_data = [
                'id' => $new_user->id,
                'username' => $new_user->username,
                'email' => $new_user->email,
                'display_name' => $new_user->display_name,
                'avatar' => $new_user->avatar,
                'created_at' => (new \DateTime())->format('y-m-d H:i:s')
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
