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
$this->params['breadcrumbs'][] = ['label' => '兑换码', 'url' => ['index']];
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
                <adiv class="col-lg-12">
                    <div class="form-group field-voucherexchangecode-code">
                        <div class='col-sm-2 text-right'>
                            <label class="control-label" for="voucherexchangecode-code">生成数量</label>
                        </div>
                        <div class='col-sm-10'>
                            <input type="hidden" name="product_id" value="<?php echo (int)$product_id ?>">
                            <input type="number" value="1" id="voucherexchangecode-code" class="form-control" name="count">
                            <div class="help-block"></div>
                        </div>
                </adiv>
                <?= $form->field($model, 'expired_at')->widget(DateTimePicker::class, [
                    'language' => 'zh-CN',
                    'options' => [
                        'value' => date('Y-m-d H:i:s',strtotime('+30 days')),
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd hh:ii',
                        'todayHighlight' => true,//今日高亮
                        'autoclose' => true,//选择后自动关闭
                        'todayBtn' => true,//今日按钮显示
                    ]
                ]);?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">生成</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>