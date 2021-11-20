<?php

namespace SSF;

use Ninja\DatabaseTable;
use SSF\Api\UserApi;
use SSF\Entity\UserEntity;

class SSFRoutesHandler implements \Ninja\NJInterface\IRoutes
{
    private $user_table;
    
    public function __construct()
    {
        $this->user_table = new DatabaseTable(UserEntity::TABLE, UserEntity::PRIMARY_KEY, UserEntity::CLASS_NAME);
    }

    public function getRoutes(): array
    {
        $user_api_handler = new UserApi($this->user_table);

        return $this->get_user_api_routes($user_api_handler);
    }
    
    public function getAuthentication(): ?\Ninja\Authentication
    {
        return null;
    }

    public function checkPermission($permission): ?bool
    {
        return null;
    }
    
    private function get_user_api_routes(UserApi $user_api_handler)
    {
        return [
            '/api/v1/users' => [
                'GET' => [
                    'controller' => $user_api_handler,
                    'action' => 'index'
                ],
                'POST' => [
                    'controller' => $user_api_handler,
                    'action' => 'store'
                ]
            ]
        ];
    }
}
