<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;
use addons\TinyShop\common\enums\AdvJumpTypeEnum;

$this->title = '兑换码';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit?product_id='.$product_id],'批量生成'); ?>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'label' => '兑换码',
                            'value' => function ($model) {
                                return $model->code;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '创建时间',
                            'value' => function ($model) {
                                return date('Y-m-d H:i', $model->created_at);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '有效期至',
                            'value' => function ($model) {
                                return date('Y-m-d H:i', $model->expired_at);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '使用情况',
                            'value' => function ($model) {
                                return $model->used_at ? '已使用' : '未使用';
                            },
                            'format' => 'raw',
                        ],
//                        [
//                            'header' => "操作",
//                            'class' => 'yii\grid\ActionColumn',
//                            'template' => '{edit} {status} {delete}',
//                            'buttons' => [
//                                'edit' => function ($url, $model, $key) {
//                                    return Html::edit(['edit', 'id' => $model->id]);
//                                },
//                                'delete' => function ($url, $model, $key) {
//                                    return Html::delete(['destroy', 'id' => $model->id]);
//                                },
//                            ],
//                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>