<?php

namespace SSF\Api;

use Ninja\Authentication;
use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Model\WalletModel;

class WalletApi
{
    private $wallet_model;
    private $authentication_helper;

    use Jsonable;

    public function __construct(WalletModel $wallet_model, Authentication $authentication)
    {
        $this->wallet_model = $wallet_model;
        $this->authentication_helper = $authentication;
    }
    
    public function store()
    {
        try {
            $json = $this->parse_json_from_request();
            $new_wallet = $this->wallet_model->create_new_wallet($json);
            
            $format = $json['format'] ?? null;
            if (!$format)
                $format = 'Y-m-d';
            
            $begin_date = $new_wallet->get_begin_date($format);
            $end_date = $new_wallet->get_end_date($format);
            
            $response_data = [
                'id' => $new_wallet->id,
                'user_id' => $new_wallet->user_id,
                'date_begin' => $begin_date,
                'end_date' => $end_date,
                'format' => $format
            ];

            $this->response_json([
                'status' => 'success',
                'data' => $response_data
            ], 201);
        }
        catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], 400);
        }
    }
    
    public function get_all_logs_belong_to_wallet()
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id)
                throw new NinjaException('Vui lòng truyền tham số id');
            
            $wallet_entity = $this->wallet_model->get_wallet_by_id($id);
            
            $logs = $wallet_entity->get_logs();
            
            $this->response_json([
                'status' => 'success',
                'data' => $logs
            ]);
        }
        catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], 400);
        }
        
    }
    
    public function get_all_by_user()
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id)
                throw new NinjaException('Vui lòng truyền tham số id người dùng');
            
            $wallets = $this->wallet_model->get_all_by_user_id($id);
            
            $this->response_json([
                'status' => 'success',
                'data' => $wallets
            ]);
        }
        catch (NinjaException $exception) {
            
        }
    }
    
    public function get_wallet_statistics()
    {
        try {
            if (!$this->authentication_helper->isLoggedIn())
                throw new NinjaException('Bạn phải đăng nhập trước khi thực hiện thao tác này', 403);
            
            $user_id = $this->authentication_helper->getUserId();
            $statistic = $this->wallet_model->get_wallet_statistics($user_id);
            
            $this->response_json([
                'status' => 'success',
                'data' => $statistic
            ]);
        }
        catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], $exception->get_status_code());
        }
    }
}
