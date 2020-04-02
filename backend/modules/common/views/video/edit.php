<?php

use yii\widgets\ActiveForm;
use addons\TinyShop\common\enums\AdvLocalEnum;
use addons\TinyShop\common\enums\AdvJumpTypeEnum;
use common\helpers\ArrayHelper;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;

$addon = <<< HTML
<div class="input-group-append">
    <span class="input-group-text">
        <i class="fas fa-calendar-alt"></i>
    </span>
</div>
HTML;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '视频矩阵', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'cover')->widget(\common\widgets\webuploader\Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'url')->widget(\common\widgets\webuploader\Files::class, [
                        'type' => 'videos',
                        'config' => [
                            // 可设置自己的上传地址, 不设置则默认地址
                            // 'server' => '',
                            'pick' => [
                                'multiple' => false,
                            ],
                            'accept' => [
                                'extensions' => ['rm', 'rmvb', 'wmv', 'avi', 'mpg', 'mpeg', 'mp4'],
                                'mimeTypes' => 'video/*',
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'sort')->textInput()->hint('数字越小，排名越靠前'); ?>
                    <?= $form->field($model, 'index_status')->radioList(\common\enums\StatusEnum::getMap()); ?>
                    <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::getMap()); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>