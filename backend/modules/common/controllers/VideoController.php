<?php

namespace addons\TinyShop\backend\modules\common\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\TinyShop\common\models\common\Video;
use addons\TinyShop\backend\controllers\BaseController;
use yii\data\ActiveDataProvider;

/**
 * 幻灯片
 *
 * Class AdvController
 * @package addons\TinyShop\backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class VideoController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Adv
     */
    public $modelClass = Video::class;

    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $query = Video::find()
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}