<?php

namespace addons\TinyShop\common\models\common;

use addons\TinyShop\common\models\product\Product;
use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;
use common\models\merchant\Merchant;

/**
 * This is the model class for table "{{%addon_shop_base_adv}}".
 * @property int    $id          序号
 * @property string $title       标题
 * @property string $cover       图片
 * @property string $location    广告位
 * @property string $silder_text 图片描述
 * @property int    $start_time  开始时间
 * @property int    $end_time    结束时间
 * @property string $jump_link   跳转链接
 * @property int    $jump_type   跳转方式[1:新标签; 2:当前页]
 * @property int    $sort        优先级
 * @property int    $status      状态
 * @property int    $created_at  创建时间
 * @property int    $updated_at  更新时间
 */
class Voucher extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    const STATE_UNUSED = 0; // 未使用
    const STATE_USED = 1; //已使用
    const STATE_EXPIRED = 2; //已过期

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_shop_voucher}}';
    }


    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::STATE_UNUSED => '未使用',
            self::STATE_USED => '已使用',
            self::STATE_EXPIRED => '已过期',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMerchant()
    {
        return $this->hasOne(Merchant::class, ['id' => 'merchant_id']);
    }
}
