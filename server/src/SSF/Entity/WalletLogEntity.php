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
    const KEY_TITLE = 'title';
    const KEY_TYPE = 'type';
    const KEY_AMOUNT = 'amount';
    const KEY_LOG_DATE = 'log_date';
    const KEY_CREATED_AT = 'created_at';
    
    const TYPE_INCOME = 'in';
    const TYPE_OUTCOME = 'out';
    
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
        if (!$this->wallet_entity)
            $this->wallet_entity = $this->wallet_table->findById($this->wallet_id);
        
        return $this->wallet_entity;
    }
    
    public function get_category()
    {
        if (!$this->category_entity)
            $this->category_entity = $this->category_table->findById($this->category_id);
        
        return $this->category_entity;
    }
    
    public function get_log_date($format = 'Y-m-d H:i:s')
    {
        if ($this->log_date instanceof \DateTime)
            return $this->log_date->format($format);
        
        $date_obj = \DateTime::createFromFormat($format, $this->log_date);
        if ($date_obj)
            return $date_obj->format($format);
        
        return null;
    }
}
