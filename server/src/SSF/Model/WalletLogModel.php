<?php

namespace SSF\Model;

use Ninja\DatabaseTable;
use Ninja\NinjaException;
use SSF\Entity\WalletLogEntity;

class WalletLogModel
{
    private $wallet_log_table;
    
    public function __construct(DatabaseTable $wallet_log_table)
    {
        $this->wallet_log_table = $wallet_log_table;
    }
    
    public function get_all_wallet_logs()
    {
        return $this->wallet_log_table->findAll();
    }
    
    public function create_new_wallet_log($args)
    {
        $wallet_id = $args[WalletLogEntity::KEY_WALLET_ID] ?? null;
        $category_id = $args[WalletLogEntity::KEY_CATEGORY_ID] ?? null;
        $type = $args[WalletLogEntity::KEY_TYPE] ?? null;
        $amount = $args[WalletLogEntity::KEY_AMOUNT] ?? null;
        $log_date = $args[WalletLogEntity::KEY_LOG_DATE] ?? null;

        $format = $args['format'] ?? null;
        if (!$format)
            $format = 'Y-m-d';

        $log_date = \DateTime::createFromFormat($format, $log_date);

        if (!$log_date)
            throw new NinjaException('Định dạng ngày tháng không hợp lệ, mặc định là Y-m-d');

        return $this->wallet_log_table->save([
            WalletLogEntity::KEY_WALLET_ID => $wallet_id,
            WalletLogEntity::KEY_CATEGORY_ID => $category_id,
            WalletLogEntity::KEY_TYPE => $type,
            WalletLogEntity::KEY_AMOUNT => $amount,
            WalletLogEntity::KEY_LOG_DATE => $log_date
        ]);
    }
    
}