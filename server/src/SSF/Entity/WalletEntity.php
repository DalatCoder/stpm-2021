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
    
    private $user_entity;
    
    public function __construct(DatabaseTable $user_table)
    {
        $this->user_table = $user_table;
    }
    
    public function get_owner()
    {
        if (!$this->user_entity)
            $this->user_entity = $this->user_table->findById($this->user_id);
        
        return $this->user_entity;
    }
    
    public function get_begin_date($format = 'Y-m-d')
    {
        if ($this->date_begin instanceof \DateTime)
            return $this->date_begin->format($format);
        
        return null;
    }

    public function get_end_date($format = 'Y-m-d')
    {
        if ($this->date_end instanceof \DateTime)
            return $this->date_end->format($format);

        return null;
    }
}
