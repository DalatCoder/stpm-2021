<?php

namespace SSF\Model;

use Ninja\DatabaseTable;
use Ninja\NinjaException;
use SSF\Entity\WalletEntity;

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
}
