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
    
    public function aggregate_by_date($wallet_id)
    {
        $all_logs = $this->wallet_log_table->find(WalletLogEntity::KEY_WALLET_ID, $wallet_id) ?? [];
        
        $aggregates = [];
        
        foreach ($all_logs as $log) {
            if ($log->{WalletLogEntity::KEY_TYPE} == WalletLogEntity::TYPE_INCOME)
                continue;
            
            if (!isset($aggregates[$log->get_log_date()]))
                $aggregates[$log->get_log_date()] = [
                    'date' => $log->get_log_date(), 
                    'total' => 0,
                    'counter' => 0,
                    'wallet_id' => $wallet_id
                ];
            
            $aggregates[$log->{WalletLogEntity::KEY_LOG_DATE}]['total'] += $log->{WalletLogEntity::KEY_AMOUNT} ?? 0;
            $aggregates[$log->{WalletLogEntity::KEY_LOG_DATE}]['counter'] ++;
        }
        
        $results = [];
        foreach ($aggregates as $key => $value) {
            $results[] = $value;
        }
        
        return $results;
    }
    
    public function get_all_by_date($wallet_id, $date)
    {
        $all_logs = $this->wallet_log_table->find(WalletLogEntity::KEY_WALLET_ID, $wallet_id);
        
        $filtered = [];
        
        foreach ($all_logs as $log) {
            $log_date = $log->get_log_date();
            
            if ($log_date->format('Y-m-d') == $date) {
                $filtered[] = $log;
            }
        }
        
        return $filtered;
    }
}
