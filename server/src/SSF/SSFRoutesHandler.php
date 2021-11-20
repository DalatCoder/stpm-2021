<?php

namespace SSF;

use Ninja\DatabaseTable;
use SSF\Api\UserApi;
use SSF\Api\WalletApi;
use SSF\Entity\UserEntity;
use SSF\Entity\WalletEntity;
use SSF\Model\UserModel;
use SSF\Model\WalletModel;

class SSFRoutesHandler implements \Ninja\NJInterface\IRoutes
{
    private $user_table;
    private $wallet_table;
    
    public function __construct()
    {
        $this->user_table = new DatabaseTable(UserEntity::TABLE, UserEntity::PRIMARY_KEY, UserEntity::CLASS_NAME);
        $this->wallet_table = new DatabaseTable(WalletEntity::TABLE, WalletEntity::PRIMARY_KEY, WalletEntity::CLASS_NAME, [
            &$this->user_table
        ]);
    }

    public function getRoutes(): array
    {
        $user_model = new UserModel($this->user_table);
        $user_api_handler = new UserApi($user_model);
        
        $wallet_model = new WalletModel($this->wallet_table);
        $wallet_api_handler = new WalletApi($wallet_model);
        
        $user_routes = $this->get_user_api_routes($user_api_handler);
        $wallet_routes = $this->get_wallet_api_routes($wallet_api_handler);

        return $user_routes + $wallet_routes;
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
    
    private function get_wallet_api_routes(WalletApi $wallet_api_handler)
    {
        return [
            '/api/v1/wallets' => [
                'GET' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'get_all_wallets'
                ],
                'POST' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'create_new_wallet'
                ]
            ]
        ];
    }
}
