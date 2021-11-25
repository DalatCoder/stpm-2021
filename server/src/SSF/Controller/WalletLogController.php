<?php

namespace SSF\Controller;

use Ninja\NinjaException;
use SSF\Entity\WalletLogEntity;
use SSF\Model\WalletLogModel;

class WalletLogController extends SSFBaseController
{
    private $wallet_log_model;
    
    public function __construct(WalletLogModel $wallet_log_model)
    {
        parent::__construct();
        $this->wallet_log_model = $wallet_log_model;
    }
    
    public function store_income_log()
    {
        try {
            $income_log = $_POST['income_log'] ?? null;
            
            if (!$income_log)
                throw new NinjaException('Vui lòng truyền tham số hợp lệ');
            
            $income_log['type'] = WalletLogEntity::TYPE_INCOME;
            $income_log['log_date'] = (new \DateTime())->format('Y-m-d');
            
            $this->wallet_log_model->create_new_wallet_log($income_log);
            
            $this->route_redirect('/wallets');
        }
        catch (NinjaException $exception) {
            
        }
    }
    
    public function store_outcome_log()
    {
        try {
            $outcome_log = $_POST['log'] ?? null;

            if (!$outcome_log)
                throw new NinjaException('Vui lòng truyền tham số hợp lệ');

            $outcome_log['type'] = WalletLogEntity::TYPE_OUTCOME;

            $this->wallet_log_model->create_new_wallet_log($outcome_log);

            $this->route_redirect('/wallets');
        }
        catch (NinjaException $exception) {

        }
    }
}
