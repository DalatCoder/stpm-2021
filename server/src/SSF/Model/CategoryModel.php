<?php

namespace SSF\Model;

use Ninja\DatabaseTable;
use SSF\Entity\CategoryEntity;

class CategoryModel
{
    private $category_table;
    
    public function __construct(DatabaseTable $category_table)
    {
        $this->category_table = $category_table;
    }
    
    public function get_all_categories()
    {
        return $this->category_table->findAll();
    }
    
    public function get_category_by_type($type)
    {
        return $this->category_table->find(CategoryEntity::KEY_TYPE, $type);
    }

    public function get_all_income_categories()
    {
        return $this->get_category_by_type(CategoryEntity::TYPE_INCOME);
    }
    
    public function get_all_outcome_categories()
    {
        return $this->get_category_by_type(CategoryEntity::TYPE_OUTCOME);
    }
    
    public function create_new_category($args)
    {
        $type = $args[CategoryEntity::KEY_TYPE] ?? null;
        $name = $args[CategoryEntity::KEY_NAME] ?? null;
        
        return $this->category_table->save([
            CategoryEntity::KEY_TYPE => $type,
            CategoryEntity::KEY_NAME => $name
        ]);
    }
}
