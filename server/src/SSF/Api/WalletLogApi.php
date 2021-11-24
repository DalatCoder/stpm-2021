<?php

namespace SSF\Api;

use Ninja\Authentication;
use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Entity\WalletLogEntity;
use SSF\Model\WalletLogModel;

class WalletLogApi
{
    use Jsonable;

    private $wallet_log_model;
    private $authentication_helper;

    public function __construct(WalletLogModel $wallet_log_model, Authentication $authentication_helper)
    {
        $this->wallet_log_model = $wallet_log_model;
        $this->authentication_helper = $authentication_helper;
    }

    public function index()
    {
        $all = $this->wallet_log_model->get_all_wallet_logs();

        $this->response_json([
            'status' => 'success',
            'data' => $all
        ]);
    }

    public function store()
    {
        try {
            $json = $this->parse_json_from_request();

            $format = $json['format'] ?? null;
            if (!$format)
                $format = 'Y-m-d';

            $new_wallet_log = $this->wallet_log_model->create_new_wallet_log($json);

            $response_data = [
                'id' => $new_wallet_log->id,
                WalletLogEntity::KEY_WALLET_ID => $new_wallet_log->wallet_id,
                WalletLogEntity::KEY_CATEGORY_ID => $new_wallet_log->category_id,
                WalletLogEntity::KEY_TYPE => $new_wallet_log->type,
                WalletLogEntity::KEY_AMOUNT => $new_wallet_log->amount,
                WalletLogEntity::KEY_LOG_DATE => $new_wallet_log->get_log_date($format),
                'format' => $format
            ];

            $this->response_json([
                'status' => 'success',
                'data' => $response_data
            ], 201);
        } catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => $exception->getMessage()
            ], 400);
        }
    }
    
    public function aggregate_by_date()
    {
        $wallet_id = $_GET['wallet_id'] ?? null;
        
        $response_data = $this->wallet_log_model->aggregate_by_date($wallet_id);
        
        $this->response_json([
            'status' => 'success',
            'data' => $response_data
        ]);
    }
    
    public function get_logs_by_date()
    {
        try {
            if (!$this->authentication_helper->isLoggedIn())
                throw new NinjaException('Bạn phải đăng nhập để tiếp tục', 403);

            $date = $_GET['date'] ?? (new \DateTime())->format('Y-m-d');
            $wallet_id = $_GET['wallet_id'] ?? null;
            
            if (is_null($wallet_id))
                throw new NinjaException('Bạn phải truyền tham số wallet_id');

            $logs = $this->wallet_log_model->get_all_by_date($wallet_id, $date);
            
            foreach ($logs as $log) {
                $log->category = $log->get_category();
            }
            
            $this->response_json([
                'status' => 'success',
                'data' => $logs
            ]);
        } catch (NinjaException $exception) {
            $this->response_json([
                'status' => 'fail',
                'data' => null,
                'message' => $exception->getMessage()
            ], $exception->get_status_code());
        }
    }    
}
