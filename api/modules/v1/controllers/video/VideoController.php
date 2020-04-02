<?php

namespace addons\TinyShop\api\modules\v1\controllers\video;

use Yii;
use api\controllers\OnAuthController;
use addons\TinyShop\common\models\common\Video;

/**
 * 产品分类
 *
 * Class CateController
 * @package addons\TinyShop\api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class VideoController extends OnAuthController
{
    /**
     * @var Cate
     */
    public $modelClass = Video::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];

    /**
     * @return array|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return Yii::$app->tinyShopService->video->getList();
    }


    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'update', 'view', 'create'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}