<?php

namespace SSF\Entity;

use Ninja\DatabaseTable;

class WalletEntity
{
    const PRIMARY_KEY = 'id';
    const TABLE = 'wallet';
    const CLASS_NAME = '\\SSF\\Entity\\WalletEntity';
    
    const KEY_ID = 'id';
    const KEY_USER_ID = 'user_id';
    const KEY_DATE_BEGIN = 'date_begin';
    const KEY_DATE_END = 'date_end';
    const KEY_CREATED_AT = 'created_at';
    
    public $id;
    public $user_id;
    public $date_begin;
    public $date_end;
    public $created_at;

    private $user_table;
    private $wallet_log_table;
    
    private $user_entity;
    private $wallet_log_entities;
    
    public function __construct(DatabaseTable $user_table, DatabaseTable $wallet_log_table)
    {
        $this->user_table = $user_table;
        $this->wallet_log_table = $wallet_log_table;
    }
    
    public function get_owner()
    {
        if (!$this->user_entity)
            $this->user_entity = $this->user_table->findById($this->user_id);
        
        return $this->user_entity;
    }
    
    public function get_logs()
    {
        if (!$this->wallet_log_entities) {
            $logs = $this->wallet_log_table->find(WalletLogEntity::KEY_WALLET_ID, $this->id);
            
            foreach ($logs as $log) {
                $log->category = $log->get_category();
            }
            
            $this->wallet_log_entities = $logs;
        }
        
        return $this->wallet_log_entities;
    }
    
    public function get_all_income_logs()
    {
        $all = $this->get_logs();
        
        $filtered = [];
        
        foreach ($all as $item) {
            if ($item->type === WalletLogEntity::TYPE_INCOME)
                $filtered[] = $item;
        }
        
        return $filtered;
    }

    public function get_all_outcome_logs()
    {
        $all = $this->get_logs();

        $filtered = [];

        foreach ($all as $item) {
            if ($item->type === WalletLogEntity::TYPE_OUTCOME)
                $filtered[] = $item;
        }

        return $filtered;
    }

    public function get_begin_date($format = 'Y-m-d')
    {
        if ($this->date_begin instanceof \DateTime)
            return $this->date_begin->format($format);
        
        $date_obj = \DateTime::createFromFormat('Y-m-d H:i:s', $this->date_begin);
        if ($date_obj)
            return $date_obj->format($format);
        
        $date_obj = \DateTime::createFromFormat('d-m-Y H:i:s', $this->date_begin);
        if ($date_obj)
            return $date_obj->format($format);
        
        return null;
    }

    public function get_end_date($format = 'Y-m-d')
    {
        if ($this->date_end instanceof \DateTime)
            return $this->date_end->format($format);

        $date_obj = \DateTime::createFromFormat('Y-m-d H:i:s', $this->date_end);
        if ($date_obj)
            return $date_obj->format($format);

        $date_obj = \DateTime::createFromFormat('d-m-Y H:i:s', $this->date_end);
        if ($date_obj)
            return $date_obj->format($format);

        return null;
    }
    
    public function get_remaining_date()
    {
        
    }
    
    public function get_total_incomes()
    {
        $incomes = $this->get_all_income_logs() ?? [];
        $amounts = array_column($incomes, 'amount');
        
        return array_sum($amounts);
    }
    
    public function get_total_outcomes()
    {
        $outcomes = $this->get_all_outcome_logs() ?? [];
        $amounts = array_column($outcomes, 'amount');

        return array_sum($amounts);
    }

}
