<?php

namespace addons\TinyShop\api\modules\v1\controllers\member;

use addons\TinyShop\common\models\common\Voucher;
use Yii;
use api\controllers\OnAuthController;
use common\helpers\ResultHelper;

/**
 * 产品分类
 * Class CateController
 * @package addons\TinyShop\api\controllers
 * @author  jianyan74 <751393839@qq.com>
 */
class VoucherController extends OnAuthController
{

    /**
     * @var Cate
     */
    public $modelClass = Voucher::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     * @var array
     */
//    protected $authOptional = ['index', 'code', 'check', 'exchange'];


    /**
     * @return array|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $state = Yii::$app->request->get('state', Voucher::STATE_UNUSED);
        $member_id = !Yii::$app->user->isGuest ? Yii::$app->user->identity->member_id : 1;
        switch ($state) {
            case Voucher::STATE_UNUSED :
                $result = Yii::$app->tinyShopService->voucher->getUnusedByMemberId($member_id);
                break;
            case Voucher::STATE_USED :
                $result = Yii::$app->tinyShopService->voucher->getUsedByMemberId($member_id);
                break;
            case Voucher::STATE_EXPIRED :
                $result = Yii::$app->tinyShopService->voucher->getExpiredByMemberId($member_id);
                break;
            default:
                $result = [];
        }


        return $result;

    }


    /**
     * 获取核销码
     * @param $id
     * @return mixed
     */
    public function actionCode($id)
    {
        $member_id = !Yii::$app->user->isGuest ? Yii::$app->user->identity->member_id : 1;
        $code = Yii::$app->tinyShopService->voucher->generateCode($id, $member_id);
        $qr = Yii::$app->tinyShopService->qr->createVoucher($code);

        return compact('qr', 'code');
    }


    /**
     * 轮询。检查卡券被核销了没有
     * @param $id
     * @param $code
     * @return mixed
     */
    public function actionCheck($id)
    {
        $member_id = !Yii::$app->user->isGuest ? Yii::$app->user->identity->member_id : 1;
        return Yii::$app->tinyShopService->voucher->checkCode($id, $member_id);
    }


    /**
     * 用户兑换卡券
     * @param $code
     * @return mixed
     */
    public function actionExchange()
    {
        $code = Yii::$app->request->post('code');
        if (!$code) {
            throw new \yii\web\BadRequestHttpException('请输入兑换码');
        }
        $member_id = !Yii::$app->user->isGuest ? Yii::$app->user->identity->member_id : 1;
        return Yii::$app->tinyShopService->voucher->exchangeByCode($code, $member_id);
    }


    /**
     * 权限验证
     * @param string $action 当前的方法
     * @param null   $model  当前的模型类
     * @param array  $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'update', 'create'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}