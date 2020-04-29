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
class AppleLoginForm extends Model
{
    public $user;
    public $group;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user', 'group'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'user' => 'user',
            'group' => '组别',
        ];
    }

}
