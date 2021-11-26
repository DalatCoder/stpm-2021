<?php

namespace SSF\Model;

use Ninja\DatabaseTable;
use Ninja\NinjaException;
use SSF\Entity\WalletEntity;
use SSF\Entity\WalletLogEntity;

class WalletModel
{
    private $wallet_table;

    public function __construct(DatabaseTable $wallet_table)
    {
        $this->wallet_table = $wallet_table;
    }

    /**
     * @throws NinjaException
     */
    public function create_new_wallet($args)
    {
        $user_id = $args[WalletEntity::KEY_USER_ID] ?? null;
        $date_begin = $args[WalletEntity::KEY_DATE_BEGIN] ?? null;
        $date_end = $args[WalletEntity::KEY_DATE_END] ?? null;
        
        $format = $args['format'] ?? null;
        if (!$format)
            $format = 'Y-m-d';
        
        $date_begin = \DateTime::createFromFormat($format, $date_begin);
        $date_end = \DateTime::createFromFormat($format, $date_end);
        
        if (!$date_begin || !$date_end)
            throw new NinjaException('Định dạng ngày tháng không hợp lệ, mặc định là Y-m-d');
        
        return $this->wallet_table->save([
            WalletEntity::KEY_USER_ID => $user_id,
            WalletEntity::KEY_DATE_BEGIN => $date_begin,
            WalletEntity::KEY_DATE_END => $date_end
        ]);
    }

    /**
     * @throws NinjaException
     */
    public function get_wallet_by_id($id)
    {
        $wallet_entity = $this->wallet_table->findById($id);
        
        if (!$wallet_entity)
            throw new NinjaException('Không tìm thấy ví tiền này');
        
        return $wallet_entity;
    }
    
    public function get_all_by_user_id($user_id)
    {
        return $this->wallet_table->find(WalletEntity::KEY_USER_ID, $user_id);
    }
    
    public function get_wallet_statistics($user_id)
    {
        $wallets = $this->get_all_by_user_id($user_id);
        
        $statistic = [
            'total_incomes' => 0,
            'total_outcomes' => 0,
            'total' => 0,
            'completed' => 0
        ];
        
        $statistic['total'] = count($wallets);
        
        $now = new \DateTime();
        
        foreach ($wallets as $wallet) {
            $date_end = $wallet->get_end_date();
            $date_end_object = \DateTime::createFromFormat('Y-m-d', $date_end) ?? null;
            
            $all_logs = $wallet->get_logs();
            foreach ($all_logs as $log) {
                if ($log->{WalletLogEntity::KEY_TYPE} === WalletLogEntity::TYPE_INCOME)
                    $statistic['total_incomes'] += $log->{WalletLogEntity::KEY_AMOUNT} ?? 0;
                else
                    $statistic['total_outcomes'] += $log->{WalletLogEntity::KEY_AMOUNT} ?? 0;
            }
            
            if ($date_end_object)
                if ($date_end_object->getTimestamp() <= $now->getTimestamp())
                    $statistic['completed'] += 1;
        }
        
        return $statistic;
    }
}
