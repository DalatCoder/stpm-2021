<?php

namespace SSF\Api;

use Ninja\NJTrait\Jsonable;
use SSF\Entity\CategoryEntity;
use SSF\Model\CategoryModel;

class CategoryApi
{
    use Jsonable;
    
    private $category_model;
    
    public function __construct(CategoryModel $category_model)
    {
        $this->category_model = $category_model;
    }
    
    public function index()
    {
        $categories = $this->category_model->get_all_categories();
        
        $this->response_json([
            'status' => 'success',
            'data' => $categories
        ]);
    }
    
    public function store()
    {
        $json = $this->parse_json_from_request();
        
        $new_category = $this->category_model->create_new_category($json);
        
        $response_data = [
            'id' => $new_category->id,
            CategoryEntity::KEY_NAME => $new_category->name,
            CategoryEntity::KEY_TYPE => $new_category->type
        ];
        
        $this->response_json([
            'status' => 'success',
            'data' => $response_data
        ]);
    }
}
