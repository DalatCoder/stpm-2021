<?php

namespace SSF;

use Ninja\Authentication;
use Ninja\DatabaseTable;
use SSF\Api\AuthApi;
use SSF\Api\CategoryApi;
use SSF\Api\UserApi;
use SSF\Api\WalletApi;
use SSF\Api\WalletLogApi;
use SSF\Controller\AuthController;
use SSF\Controller\HomeController;
use SSF\Controller\WalletController;
use SSF\Controller\WalletLogController;
use SSF\Entity\CategoryEntity;
use SSF\Entity\UserEntity;
use SSF\Entity\WalletEntity;
use SSF\Entity\WalletLogEntity;
use SSF\Model\CategoryModel;
use SSF\Model\UserModel;
use SSF\Model\WalletLogModel;
use SSF\Model\WalletModel;

class SSFRoutesHandler implements \Ninja\NJInterface\IRoutes
{
    private $user_table;
    private $wallet_table;
    private $category_table;
    private $wallet_log_table;

    private $authentication_helper;

    public function __construct()
    {
        $this->user_table = new DatabaseTable(UserEntity::TABLE, UserEntity::PRIMARY_KEY, UserEntity::CLASS_NAME, [
            &$this->wallet_table
        ]);
        $this->wallet_table = new DatabaseTable(WalletEntity::TABLE, WalletEntity::PRIMARY_KEY, WalletEntity::CLASS_NAME, [
            &$this->user_table,
            &$this->wallet_log_table
        ]);
        $this->category_table = new DatabaseTable(CategoryEntity::TABLE, CategoryEntity::PRIMARY_KEY, CategoryEntity::CLASS_NAME);
        $this->wallet_log_table = new DatabaseTable(WalletLogEntity::TABLE, WalletLogEntity::PRIMARY_KEY, WalletLogEntity::CLASS_NAME, [
            &$this->wallet_table,
            &$this->category_table
        ]);

        $this->authentication_helper = new Authentication($this->user_table, UserEntity::KEY_USERNAME, UserEntity::KEY_PASSWORD);
    }

    public function getRoutes(): array
    {
        $user_model = new UserModel($this->user_table);
        $user_api_handler = new UserApi($user_model);

        $wallet_model = new WalletModel($this->wallet_table);
        $wallet_api_handler = new WalletApi($wallet_model);

        $category_model = new CategoryModel($this->category_table);
        $category_api_handler = new CategoryApi($category_model);

        $wallet_log_model = new WalletLogModel($this->wallet_log_table);
        $wallet_log_api_handler = new WalletLogApi($wallet_log_model, $this->authentication_helper);
        $auth_api_handler = new AuthApi($this->authentication_helper);

        $user_routes = $this->get_user_api_routes($user_api_handler);
        $wallet_routes = $this->get_wallet_api_routes($wallet_api_handler);
        $category_routes = $this->get_category_api_routes($category_api_handler);
        $wallet_log_routes = $this->get_walletlog_api_routes($wallet_log_api_handler);
        $auth_routes = $this->get_auth_api_routes($auth_api_handler);

        $home_controller = new HomeController();
        $home_controller_routes = $this->get_controller_home_routes($home_controller);

        $auth_controller = new AuthController($this->authentication_helper);
        $auth_controller_routes = $this->get_controller_auth_routes($auth_controller);

        $wallet_controller = new WalletController($wallet_model, $category_model, $this->authentication_helper);
        $wallet_controller_routes = $this->get_controller_wallet_routes($wallet_controller);
        
        $wallet_log_controller = new WalletLogController($wallet_log_model);
        $wallet_log_controller_routes = $this->get_controller_wallet_log_routes($wallet_log_controller);

        return $user_routes + $wallet_routes + $category_routes + $wallet_log_routes + $auth_routes + $home_controller_routes + $auth_controller_routes + $wallet_controller_routes + $wallet_log_controller_routes;
    }

    public function getAuthentication(): ?\Ninja\Authentication
    {
        return $this->authentication_helper;
    }

    public function checkPermission($permission): ?bool
    {
        return null;
    }

    private function get_controller_home_routes(HomeController $home_controller)
    {
        return [
            '/' => [
                'GET' => [
                    'controller' => $home_controller,
                    'action' => 'home'
                ]
            ]
        ];
    }

    private function get_controller_auth_routes(AuthController $auth_controller)
    {
        return [
            '/auth/login' => [
                'GET' => [
                    'controller' => $auth_controller,
                    'action' => 'show_login_form'
                ],
                'POST' => [
                    'controller' => $auth_controller,
                    'action' => 'process_login'
                ]
            ],
            '/auth/register' => [
                'GET' => [
                    'controller' => $auth_controller,
                    'action' => 'show_register_form'
                ],
                'POST' => [
                    'controller' => $auth_controller,
                    'action' => 'process_register'
                ]
            ],
            '/auth/logout' => [
                'GET' => [
                    'controller' => $auth_controller,
                    'action' => 'log_user_out'
                ]
            ]
        ];
    }

    private function get_controller_wallet_routes(WalletController $wallet_controller)
    {
        return [
            '/wallets' => [
                'GET' => [
                    'controller' => $wallet_controller,
                    'action' => 'index'
                ],
                'login' => true
            ]
        ];
    }

    private function get_controller_wallet_log_routes(WalletLogController $wallet_log_controller)
    {
        return [
            '/wallet-logs/incomes' => [
                'POST' => [
                    'controller' => $wallet_log_controller,
                    'action' => 'store_income_log'
                ]
            ],
            '/wallet-logs/outcomes' => [
                'POST' => [
                    'controller' => $wallet_log_controller,
                    'action' => 'store_outcome_log'
                ]
            ]
        ];
    }

    private function get_user_api_routes(UserApi $user_api_handler): array
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

    private function get_wallet_api_routes(WalletApi $wallet_api_handler): array
    {
        return [
            '/api/v1/wallets' => [
                'GET' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'get_all_wallets'
                ],
                'POST' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'store'
                ]
            ],
            '/api/v1/wallets/logs' => [
                'GET' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'get_all_logs_belong_to_wallet'
                ]
            ],
            '/api/v1/wallets/by-user' => [
                'GET' => [
                    'controller' => $wallet_api_handler,
                    'action' => 'get_all_by_user'
                ]
            ]
        ];
    }

    private function get_category_api_routes(CategoryApi $category_api_handler): array
    {
        return [
            '/api/v1/categories' => [
                'GET' => [
                    'controller' => $category_api_handler,
                    'action' => 'index'
                ],
                'POST' => [
                    'controller' => $category_api_handler,
                    'action' => 'store'
                ]
            ],
            '/api/v1/categories/outcomes' => [
                'GET' => [
                    'controller' => $category_api_handler,
                    'action' => 'get_all_outcomes'
                ],
            ],
            '/api/v1/categories/incomes' => [
                'GET' => [
                    'controller' => $category_api_handler,
                    'action' => 'get_all_incomes'
                ],
            ],
        ];
    }

    private function get_walletlog_api_routes(WalletLogApi $wallet_log_api_handler): array
    {
        return [
            '/api/v1/wallet-logs' => [
                'GET' => [
                    'controller' => $wallet_log_api_handler,
                    'action' => 'index'
                ],
                'POST' => [
                    'controller' => $wallet_log_api_handler,
                    'action' => 'store'
                ]
            ],
            '/api/v1/wallet-logs/aggregate-by-date' => [
                'GET' => [
                    'controller' => $wallet_log_api_handler,
                    'action' => 'aggregate_by_date'
                ]
            ],
            '/api/v1/wallet-logs/get-logs-by-date' => [
                'GET' => [
                    'controller' => $wallet_log_api_handler,
                    'action' => 'get_logs_by_date'
                ]
            ]
        ];
    }

    private function get_auth_api_routes(AuthApi $auth_api_handler)
    {
        return [
            '/api/v1/auth/login' => [
                'POST' => [
                    'controller' => $auth_api_handler,
                    'action' => 'log_user_in'
                ]
            ]
        ];
    }
}
