<?php

namespace addons\TinyShop\services\common;

use addons\TinyShop\common\models\common\VoucherExchangeCode;
use addons\TinyShop\common\models\product\Product;
use common\enums\StatusEnum;
use common\helpers\AddonHelper;
use common\models\forms\CreditsLogForm;
use common\models\forms\MerchantCreditsLogForm;
use common\models\merchant\Account;
use common\models\merchant\Merchant;
use Yii;
use addons\TinyShop\common\models\common\Voucher;
use common\components\Service;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AdvService
 * @package addons\TinyShop\services\common
 * @author  jianyan74 <751393839@qq.com>
 */
class VoucherService extends Service
{

    /**
     * 获取用户未使用的卡券
     * @param array $locals
     * @return array
     */
    public function getUnusedByMemberId($member_id)
    {
        $time = time();

        $data = Voucher::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['used_at' => 0])
            ->andWhere('expired_at > ' . $time);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->select(['id', 'product_name', 'product_id', 'created_at', 'expired_at', 'order_money'])
            ->asArray()
            ->all();

        foreach ($result as $key => $value) {
            $result[$key]['created_at'] = date("Y-m-d", $value['created_at']);
            $result[$key]['expired_at'] = date("Y-m-d", $value['expired_at']);
        }

        return $result;
    }


    /**
     * 获取用户已使用的卡券
     * @param array $locals
     * @return array
     */
    public function getUsedByMemberId($member_id)
    {

        $data = Voucher::find()
            ->where(['member_id' => $member_id])
            ->andWhere('used_at != 0');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->orderBy('used_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->select(['id', 'product_name', 'product_id', 'created_at', 'expired_at', 'order_money', 'used_at'])
            ->asArray()
            ->all();

        foreach ($result as $key => $value) {
            $result[$key]['created_at'] = date("Y-m-d", $value['created_at']);
            $result[$key]['expired_at'] = date("Y-m-d", $value['expired_at']);
            $result[$key]['used_at'] = date("Y-m-d", $value['used_at']);
        }

        return $result;
    }


    /**
     * 获取用户已过期的卡券
     * @param array $locals
     * @return array
     */
    public function getExpiredByMemberId($member_id)
    {
        $time = time();
        $data = Voucher::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['used_at' => 0])
            ->andWhere('expired_at < ' . $time);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->select(['id', 'product_name', 'product_id', 'created_at', 'expired_at', 'order_money'])
            ->asArray()
            ->all();

        foreach ($result as $key => $value) {
            $result[$key]['created_at'] = date("Y-m-d", $value['created_at']);
            $result[$key]['expired_at'] = date("Y-m-d", $value['expired_at']);
        }

        return $result;
    }

    public function findCountByMemberId($member_id)
    {
        $time = time();
        return Voucher::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['used_at' => 0])
            ->andWhere('expired_at > ' . $time)->count();
    }


    /**
     * 获取商户核销过的卡券
     * @param $merchant_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVerifiedByMerchantId($merchant_id)
    {
        $data = Voucher::find()
            ->where(['merchant_id' => $merchant_id])
            ->andWhere('used_at != 0');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->orderBy('used_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->select(['id', 'product_name', 'product_id', 'order_money', 'used_at'])
            ->asArray()
            ->all();

        foreach ($result as $key => $value) {
            $result[$key]['used_at'] = date("Y-m-d", $value['used_at']);
        }

        return $result;
    }


    /**
     * 获取商户员工核销过的
     * @param $merchant_member_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVerifiedByMerchantMemberId($merchant_member_id)
    {
        $data = Voucher::find()
            ->where(['merchant_member_id' => $merchant_member_id])
            ->andWhere('used_at != 0');
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->orderBy('used_at DESC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->select(['id', 'product_name', 'product_id', 'order_money', 'used_at'])
            ->asArray()
            ->all();

        foreach ($result as $key => $value) {
            $result[$key]['used_at'] = date("Y-m-d", $value['used_at']);
        }

        return $result;
    }


    /**
     * 获取商户员工核销过的卡券数量
     * @param $merchant_member_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getVerifiedCountByMerchantMemberId($merchant_member_id)
    {
        return Voucher::find()
            ->where(['merchant_member_id' => $merchant_member_id])
            ->andWhere('used_at != 0')->count();
    }


    /**
     * 用户请求生成核销码
     * @param $id
     * @param $member_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function generateCode($id, $member_id)
    {
        $time = time();
        $exists = Voucher::find()
            ->where(['id' => $id])
            ->andWhere(['member_id' => $member_id])
            ->andWhere(['used_at' => 0])
            ->andWhere('expired_at > ' . $time)
            ->exists();

        if (!$exists) {
            throw new NotFoundHttpException('找不到该卡券');
        }

        //8位随机秘钥
        $code = mt_rand(10000000, 99999999);
        $redis = Yii::$app->redis;
        $old_code = $redis->hget($id, 'code');
        if ($old_code) {
            $redis->del($old_code);
        }
        $redis->hset($id, 'code', $code);
        $redis->hset($id, 'member_id', $member_id);
        $redis->expire($id, 300);
        $redis->setex($code, 300, $id);
        return $code;
    }


    /**
     * 用于轮询，检查核销码是否还有效
     * @param $id
     * @param $code
     * @return bool
     */
    public function checkCode($id, $member_id)
    {
        $redis = Yii::$app->redis;
        $exist_member_id = $redis->hget($id, 'member_id');

        return $exist_member_id == $member_id ? 1 : 0;
    }


    /**
     * 商家核销之前，先查看该核销券是否对应的商品
     * @param $code
     */
    public function getVoucherFromCode($code)
    {
        $redis = Yii::$app->redis;
        $id = $redis->get($code);

        //如果id和秘钥对不上
        $match = $id && $redis->hget($id, 'code') == $code;
        if ($match == false) {
            throw new NotFoundHttpException('找不到该卡券');
        }

        $voucher = Voucher::find()->where([
            'id' => $id,
        ])->one();

        return $voucher;
    }


    /**
     * 商家核销卡券
     * @param $code
     * @param $merchant_id
     * @param $merchant_member_id
     * @return int
     * @throws NotFoundHttpException
     */
    public function verifyByCode($code, $merchant_id, $merchant_member_id)
    {
        $redis = Yii::$app->redis;
        $id = $redis->get($code);

        //如果id和秘钥对不上
        $match = $id && $redis->hget($id, 'code') == $code;
        if ($match == false) {
            throw new NotFoundHttpException('找不到该卡券');

        }

        //开启事务
        $transaction = Yii::$app->db->beginTransaction();

        try {
            //把兑换码给核销了
            $voucher = $this->verifyCode($id, $code, $merchant_id, $merchant_member_id);
            //结算金额给商家
            $this->settleMoneyToMerchant($voucher);
            //结算佣金给上级
            $this->settleMoneyToMemberParent($voucher);

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new NotFoundHttpException($e->getMessage());
        }


        //提交事务
        $transaction->commit();
        //删除reids
        $redis->del($code);
        $redis->hdel($id, 'code', 'member_id');
        return 1;
    }


    /**
     * 核销该卡券，成功返回该卡券金额
     * @param string $code               核销码
     * @param int    $merchant_id        商户id
     * @param int    $merchant_member_id 商户操作员id
     * @return Voucher $voucher 卡券金额
     * @throws NotFoundHttpException
     */
    private function verifyCode(int $id, string $code, int $merchant_id, int $merchant_member_id)
    {

        //更新卡券状态
        $time = time();

        $result = Voucher::updateAll([
            'used_at' => $time,
            'merchant_id' => $merchant_id,
            'merchant_member_id' => $merchant_member_id,
            'code' => $code,
        ], [
            'id' => $id,
            'used_at' => 0,
        ]);

        if ($result == false) {
            throw new NotFoundHttpException('核销失败！可能卡券已被使用');
        }

        $voucher = Voucher::find()->where([
            'id' => $id,
        ])->one();
        return $voucher;
    }


    /**
     * 核销完成后。结算金额给商户
     * @param Voucher $voucher
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    private function settleMoneyToMerchant(Voucher $voucher) : void
    {

        $config =  AddonHelper::getBackendConfig(false,'TinyShop');
        $rate =  $config['merchant_commission'];

        //给予佣金
        Yii::$app->services->merchantCreditsLog->incrMoney(new MerchantCreditsLogForm([
            'merchant' => $voucher->merchant,
            'pay_type' => 0,
            'num' => $voucher->order_money * $rate / 100,
            'credit_group' => 'voucherCreate',
            'remark' => "【系统】<$voucher->product_name>核销",
            'map_id' => $voucher->id,
        ]));

        //更新商户余额
//        $merchant = Merchant::find()->where(['id' => $voucher->merchant_id, 'state' => StatusEnum::ENABLED])->select('tax_rate')->one();
//        $account = Account::find()->where(['merchant_id' => $voucher->merchant_id, 'status' => StatusEnum::ENABLED])->one();
//        if (!$merchant || !$account) {
//            throw new NotFoundHttpException('核销失败！更新数据时发生错误');
//        }
//
//        $account->user_money += $voucher->order_money * (100 - $merchant->tax_rate) / 100;
//        $account->accumulate_money += $voucher->order_money * (100 - $merchant->tax_rate) / 100;
//        if (!$account->save()) {
//            throw new UnprocessableEntityHttpException($this->getError($account));
//        }
    }


    /**
     * 发放佣金给用户上级
     * @param $voucher
     */
    public function settleMoneyToMemberParent($voucher)
    {
        //不是通过订单购买得来的（即通过核销码兑换来的）
        if ($voucher->order_id == 0) {
            return;
        }

        $parent = Yii::$app->tinyShopService->member->findById($voucher->member_id)->parent;
        if (!$parent) {
            return;
        }

        $config =  AddonHelper::getBackendConfig(false,'TinyShop');
        $rate =  $config['parent_commission'];

        //给予佣金
        Yii::$app->services->memberCreditsLog->incrMoney(new CreditsLogForm([
            'member' => $parent,
            'pay_type' => 0,
            'num' => $voucher->order_money * $rate / 100,
            'credit_group' => 'voucherCreate',
            'remark' => "【系统】<$voucher->product_name>佣金",
            'map_id' => $voucher->id,
        ]));
    }

    /**
     * 用户兑换卡券
     * @param $code
     * @param $member_id
     * @return int
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function exchangeByCode($code, $member_id)
    {
        //先做个最基本的检验把
        if (strlen($code) != 20) {
            throw new NotFoundHttpException('兑换码不存在，请重试');
        }

        //开启事务
        $transaction = Yii::$app->db->beginTransaction();

        try {
            //核销兑换券
            $exchange = $this->verifyExchangeCode($code, $member_id);

            //找到该商品
            $product = Product::find()->where([
                'id' => $exchange->product_id,
                'product_status' => 1,//没有被下架
            ])->select(['id', 'name', 'product_status', 'price', 'term_of_validity_type', 'fixed_term', 'end_time'])->one();

            if ($product == false) {
                throw new NotFoundHttpException('该兑换码要对换的商品已不存在');
            }

            //核销完成，生成对应的卡券
            $this->createByProduct($product, $member_id, 0, $exchange->id);
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new NotFoundHttpException($e->getMessage());
        }

        $transaction->commit();
        return 1;

    }


    /**
     * 平台核销兑换码
     * @param string $code
     * @param int    $member_id
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    private function verifyExchangeCode(string $code, int $member_id)
    {
        $time = time();
        $exchange = VoucherExchangeCode::find()->where([
            'code' => $code,
            'used_at' => 0,
        ])->andWhere('expired_at > ' . $time)->one();

        if ($exchange == false) {
            throw new NotFoundHttpException('兑换码不存在，请重试');
        }
        $exchange->member_id = $member_id;
        $exchange->used_at = $time;
        if (!$exchange->save()) {
            throw new UnprocessableEntityHttpException($this->getError($exchange));
        }
        return $exchange;
    }


    /**
     * 根据商品生成对应卡券
     * @param Product $product
     * @param int     $member_id
     * @param int     $order_id
     * @param int     $exchange_code_id
     * @param int     $num
     * @throws UnprocessableEntityHttpException
     */
    public function createByProduct(Product $product, int $member_id, int $order_id = 0, int $exchange_code_id = 0, $num = 1)
    {
        $field = [
            'member_id',
            'product_id',
            'product_name',
            'order_money',
            'expired_at',
            'order_id',
            'exchange_code_id',
            'created_at',
            'updated_at'
        ];

        if ($product->term_of_validity_type == StatusEnum::ENABLED) {
            $expired_at = time() + $product->fixed_term * 60 * 60 * 24;
        } else {
            $expired_at = $product->end_time;
        }
        $time = time();
        $data = [];
        for ($i = 0; $i < $num; $i++) {
            $data[] = [
                $member_id,
                $product->id,
                $product->name,
                $product->price,
                $expired_at,
                $order_id,
                $exchange_code_id,
                $time,
                $time

            ];
        }

        $result = Yii::$app->db->createCommand()
            ->batchInsert(Voucher::tableName(), $field, $data)
            ->execute();

        if (!$result) {
            throw new UnprocessableEntityHttpException($this->getError($voucher));
        }

    }
}