<?php

namespace SSF\Entity;

use Ninja\DatabaseTable;

class WalletLogEntity
{
    const PRIMARY_KEY = 'id';
    const TABLE = 'wallet_log';
    const CLASS_NAME = '\\SSF\\Entity\\WalletLogEntity';

    const KEY_ID = 'id';
    const KEY_WALLET_ID = 'wallet_id';
    const KEY_CATEGORY_ID = 'category_id';
    const KEY_TYPE = 'type';
    const KEY_AMOUNT = 'amount';
    const KEY_LOG_DATE = 'log_date';
    const KEY_CREATED_AT = 'created_at';
    
    public $id;
    public $wallet_id;
    public $category_id;
    public $type;
    public $amount;
    public $log_date;
    public $created_at;
    
    private $wallet_entity;
    private $category_entity;
    
    private $wallet_table;
    private $category_table;
    
    public function __construct(DatabaseTable $wallet_table, DatabaseTable $category_table)
    {
        $this->wallet_table = $wallet_table;
        $this->category_table = $category_table;
    }
    
    public function get_wallet()
    {
        return $this->wallet_table->findById($this->wallet_id);
    }
    
    public function get_category()
    {
        return $this->category_table->findById($this->category_id);
    }
    
    public function get_log_date($format = 'Y-m-d')
    {
        return $this->log_date->format($format);
    }
}