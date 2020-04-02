<?php

namespace addons\TinyShop\common\models\common;

use common\behaviors\MerchantBehavior;
use common\helpers\StringHelper;

/**
 * This is the model class for table "{{%addon_shop_base_adv}}".
 * @property int    $id          序号
 * @property string $title       标题
 * @property string $cover       图片
 * @property string $location    广告位
 * @property string $silder_text 图片描述
 * @property int    $start_time  开始时间
 * @property int    $end_time    结束时间
 * @property string $jump_link   跳转链接
 * @property int    $jump_type   跳转方式[1:新标签; 2:当前页]
 * @property int    $sort        优先级
 * @property int    $status      状态
 * @property int    $created_at  创建时间
 * @property int    $updated_at  更新时间
 */
class Video extends \common\models\base\BaseModel
{
    use MerchantBehavior;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addon_shop_video}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cover', 'url', 'title'], 'required'],
            [['index_status', 'sort', 'created_at', 'updated_at'], 'integer'],
            [['cover', 'url'], 'string', 'max' => 125],
            [['title'], 'string', 'max' => 125],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'cover' => '封面',
            'url' => '视频超链接',
            'sort' => '排序',
            'index_status' => '首页显示',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

}
