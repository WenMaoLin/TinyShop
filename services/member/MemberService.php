<?php

namespace addons\TinyShop\services\member;

use common\enums\StatusEnum;
use common\helpers\EchantsHelper;
use common\models\member\Member;
use common\components\Service;

/**
 * Class MemberService
 * @package addons\TinyShop\services\member
 * @author  jianyan74 <751393839@qq.com>
 */
class MemberService extends Service
{
    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id, $select = ['*'])
    {
        return Member::find()
            ->select($select)
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
//            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }


    /**
     * 绑定邀请用户
     * @param $member
     * @param $code
     * @return mixed
     * @throws \yii\web\BadRequestHttpException
     */
    public function invitedByCode($member, $code)
    {
        if ($member->pid != 0) {
            throw new \yii\web\BadRequestHttpException('你已经填写过邀请码了');
        }

        $parent = Member::find()->where(['invitation_code' => $code])->one();
        if (!$parent) {
            throw new \yii\web\BadRequestHttpException('邀请码不存在');
        }

        //不能互为上下级
        if($member->id == $parent->pid){
            throw new \yii\web\BadRequestHttpException('绑定失败！');
        }

        $member->pid = $parent->id;
        $member->invited_at = time();
        return $member->save();
    }


    /**
     * 获取下级用户
     * @param $member
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChildren($member_id)
    {
        return Member::find()
            ->where(['pid'=>$member_id])
            ->select(['id','nickname', 'head_portrait', 'invited_at'])
            ->orderBy('invited_at desc')
            ->asArray()
            ->all();
    }
}