<?php

namespace addons\TinyShop\api\modules\v1\forms;

use Yii;
use common\enums\StatusEnum;
use common\models\member\Member;
use addons\TinyShop\common\enums\AccessTokenGroupEnum;
use yii\base\Model;

/**
 * Class LoginForm
 * @package api\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class WechatAppLoginForm extends Model
{
    public $group;
    public $openId;
    public $unionId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['openId','unionId'], 'required'],
            [['openId', 'unionId', 'group'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'openId' => 'openId',
            'unionId' => 'unionId',
            'group' => '组别',
        ];
    }

    /**
     * 用户登录
     *
     * @return mixed|null|static
     */
    public function getUser()
    {
        if ($this->_user == false) {
            $this->_user = Member::find()
                ->where(['mobile' => $this->mobile, 'status' => StatusEnum::ENABLED])
                ->andFilterWhere(['merchant_id' => Yii::$app->services->merchant->getId()])
                ->one();
        }

        return $this->_user;
    }
}
