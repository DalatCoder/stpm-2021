<?php

namespace SSF\Api;

use Ninja\NinjaException;
use Ninja\NJTrait\Jsonable;
use SSF\Entity\WalletEntity;
use SSF\Model\WalletModel;

class WalletApi
{
    private $wallet_model;

    use Jsonable;

    public function __construct(WalletModel $wallet_model)
    {
        $this->wallet_model = $wallet_model;
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
}
