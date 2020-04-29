<?php

namespace addons\TinyShop\services\common;

use common\components\Service;
use Da\QrCode\QrCode;

/**
 * Class NiceService
 * @package addons\TinyShop\services\common
 * @author jianyan74 <751393839@qq.com>
 */
class QrService extends Service
{

    /**
     * 生成二维码
     * @param $code
     * @return string
     */
    public function create($code)
    {
        $qrCode = (new QrCode($code))
            ->setSize(200)
            ->setMargin(5)
            ->useForegroundColor(51, 153, 255);

        return $qrCode->writeDataUri();
    }


    /**
     * 生成卡券二维码
     * @param $code
     * @return string
     */
    public function createVoucher($code)
    {
        $string = json_encode(['type' => 'voucher', 'code' => $code]);
        return $this->create($string);
    }


    /**
     * 生成邀请二维码
     * @param $code
     * @return string
     */
    public function createInvitation($code){
        $string = json_encode(['type' => 'invitation', 'code' => $code]);
        return $this->create($string);
    }
}