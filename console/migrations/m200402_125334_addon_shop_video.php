<?php

use yii\db\Migration;

class m200402_125334_addon_shop_video extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_shop_video}}', [
            'id' => "int(10) unsigned NOT NULL AUTO_INCREMENT",
            'merchant_id' => "int(10) unsigned NOT NULL DEFAULT '1' COMMENT '商户id'",
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



        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_shop_video}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

