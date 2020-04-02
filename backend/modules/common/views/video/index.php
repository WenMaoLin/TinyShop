<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;
use addons\TinyShop\common\enums\AdvJumpTypeEnum;

$this->title = '视频矩阵管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit']); ?>
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
                        'title',
                        [
                            'label'=> '封面',
                            'value' => function ($model) {
                                if (!empty($model->cover)) {
                                    return ImageHelper::fancyBox($model->cover);
                                }
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => '首页显示',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::whether($model->index_status);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::whether($model->status);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'sort',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::sort($model->sort);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>