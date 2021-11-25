<?php

namespace SSF\Controller;

use Ninja\Authentication;
use Ninja\NJInterface\IController;
use SSF\Model\CategoryModel;
use SSF\Model\WalletModel;

class WalletController extends SSFBaseController implements IController 
{
    private $wallet_model;
    private $category_model;
    private $authentication_helper;
    
    public function __construct(WalletModel $wallet_model, CategoryModel $category_model, Authentication $authentication)
    {
        parent::__construct();
        
        $this->wallet_model = $wallet_model;
        $this->category_model = $category_model;
        $this->authentication_helper = $authentication;
    }

    public function index()
    {
        $wallets = $this->wallet_model->get_all_by_user_id($this->authentication_helper->getUserId());
        $outcome_categories = $this->category_model->get_all_outcome_categories();
        $income_categories = $this->category_model->get_all_income_categories();
        
        $this->view_handler->render('wallet/index.html.php', [
            'wallets' => $wallets,
            'outcome_categories' => $outcome_categories,
            'income_categories' => $income_categories
        ]);
    }

    public function show()
    {
        // TODO: Implement show() method.
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function store()
    {
        // TODO: Implement store() method.
    }

    public function edit()
    {
        // TODO: Implement edit() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function destroy()
    {
        // TODO: Implement destroy() method.
    }
}
