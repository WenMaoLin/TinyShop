<?php

namespace addons\TinyShop\services\common;

use common\components\Service;
use common\enums\StatusEnum;
use addons\TinyShop\common\models\common\Video;
use yii\data\Pagination;

/**
 * Class AdvService
 * @package addons\TinyShop\services\common
 * @author jianyan74 <751393839@qq.com>
 */
class VideoService extends Service
{
    /**
     * 获取首页广告
     *
     * @param array $locals
     * @return array
     */
    public function getIndexList()
    {

        $result = Video::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->where(['index_status' => StatusEnum::ENABLED])
            ->select(['id', 'title', 'cover', 'url'])
            ->orderBy('sort asc, id desc')
            ->limit(4)
//            ->cache(60)
            ->asArray()
            ->all();

        return $result;
    }


    /**
     * 获取视频列表
     *
     * @param array $locals
     * @return array
     */
    public function getList()
    {

        $data = Video::find()
            ->where(['status' => StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10, 'validatePage' => false]);
        $result = $data->offset($pages->offset)
            ->select(['id', 'title', 'cover', 'url'])
            ->orderBy('sort asc, id desc')
            ->asArray()
            ->limit($pages->limit)
            ->all();

        return $result;
    }
}