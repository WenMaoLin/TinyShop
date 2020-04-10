<?php

namespace addons\TinyShop\api\modules\v1\controllers\store;

use Yii;
use api\controllers\OnAuthController;
/**
 * 产品分类
 *
 * Class CateController
 * @package addons\TinyShop\api\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class StoreController extends OnAuthController
{
    /**
     * @var Cate
     */
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];
    

    public function actionIndex()
    {
        $request = Yii::$app->request->get();
        $region = $request['region'];
        $location = $request['location'];
        return Yii::$app->tinyShopService->store->getRegionStore($region, $location);
    }

}