<?php

namespace addons\TinyShop\merchant\modules\product\controllers;

use addons\TinyShop\common\models\common\VoucherExchangeCode;
use addons\TinyShop\common\models\product\Product;
use Yii;
use common\traits\MerchantCurd;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use addons\TinyShop\common\models\product\Tag;
use addons\TinyShop\merchant\controllers\BaseController;
use yii\data\ActiveDataProvider;

/**
 * Class ProductTagController
 * @package addons\TinyShop\merchant\controllers
 * @author  jianyan74 <751393839@qq.com>
 */
class VoucherController extends BaseController
{
//    use MerchantCurd;

    /**
     * @var Tag
     */
    public $modelClass = VoucherExchangeCode::class;


    /**
     * 首页
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($product_id)
    {
        $product = Product::findOne($product_id);
        if (!$product || $product->is_virtual == 0) {
            echo "<script>alert('只有虚拟物品才能生成卡券兑换码!');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
            die;
        }

        $query = VoucherExchangeCode::find()
            ->where(['product_id'=>$product_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'product_id' => $product_id,
        ]);
    }


    /**
     * 编辑/创建
     * @return mixed
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $product_id = $request->get('product_id');
        if ($request->post()) {
            //生成数量
            $count = $request->post('count', 1);
            //过期时间
            $expired_at = strtotime($request->post('VoucherExchangeCode')['expired_at']);
            $product_id = $request->post('product_id');
            $product_code = sprintf('%03d', $product_id);
            $data = [];
            $time = time();
            for ($i = 0; $i < $count; $i++) {

                $data[] = [
                    $product_id,
                    $product_code . Yii::$app->getSecurity()->generateRandomString(17),
                    $expired_at,
                    $time,
                    $time,
                ];
            }

            Yii::$app->db->createCommand()
                ->batchInsert(VoucherExchangeCode::tableName(), ['product_id', 'code', 'expired_at', 'created_at', 'updated_at'],
                    $data)
                ->execute();
            return $this->redirect(['index?product_id=' . $product_id]);
        }
//        var_dump($product_id);die;
        return $this->render($this->action->id, [
            'model' => new VoucherExchangeCode(),
            'product_id' => $product_id,
        ]);
    }
}