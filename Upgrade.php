<?php

namespace addons\TinyShop;

use Yii;
use yii\db\Migration;
use common\interfaces\AddonWidget;

/**
 * 升级数据库
 * Class Upgrade
 * @package addons\TinyShop
 */
class Upgrade extends Migration implements AddonWidget
{
    /**
     * @var array
     */
    public $versions = [
        '1.0.0', // 默认版本
        '1.0.1',
        '1.0.2',
        '1.0.3',//增加video表
        '1.0.4',//video表增加merchant_id字段
    ];

    /**
     * @param $addon
     * @return mixed|void
     * @throws \yii\db\Exception
     */
    public function run($addon)
    {
        switch ($addon->version) {
            case '1.0.1' :
                // 增加测试 - 冗余的字段
                // $this->addColumn('{{%addon_example_curd}}', 'redundancy_field', 'varchar(48)');
                break;
            case '1.0.2' :
                // 删除测试 - 冗余的字段
                // $this->dropColumn('{{%addon_example_curd}}', 'redundancy_field');
                break;
            case '1.0.3' :
                // 增加video
                $this->createTable('{{%addon_shop_video}}', [
                    'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
                    'cover' => "varchar(125) NOT NULL COMMENT '封面'",
                    'title' => "varchar(20) NOT NULL COMMENT '标题'",
                    'url' => "varchar(125) NOT NULL COMMENT '视频链接'",
                    'sort' => "int(11) NOT NULL DEFAULT '999' COMMENT '排序'",
                    'index_status' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT '首页状态 1=>显示'",
                    'status' => "tinyint(4) NULL DEFAULT '1' COMMENT '状态[-1:删除;0:禁用;1启用]'",
                    'created_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
                    'updated_at' => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
                    'PRIMARY KEY (`id`)'
                ], "ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='扩展_视频表'");
                break;
            case '1.0.4' :
                // video表增加merchant_id字段
                $this->addColumn('{{%addon_shop_video}}', 'merchant_id', "int(10) unsigned NOT NULL DEFAULT '1' COMMENT '商户id'");
                break;
        }
    }
}