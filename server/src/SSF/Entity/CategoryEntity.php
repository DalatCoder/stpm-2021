<?php

namespace SSF\Entity;

class CategoryEntity
{
    const PRIMARY_KEY = 'id';
    const TABLE = 'category';
    const CLASS_NAME = '\\SSF\\Entity\\CategoryEntity';

    const KEY_ID = 'id';
    const KEY_TYPE = 'type';
    const KEY_NAME = 'name';
    const KEY_CREATED_AT = 'created_at';
    
    const TYPE_INCOME = 'in';
    const TYPE_OUTCOME = 'out';
    
    public $id;
    public $type;
    public $name;
    public $created_at;
}
