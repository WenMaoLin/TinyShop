<?php

namespace addons\TinyShop\services\common;

use common\components\Service;
use common\enums\StatusEnum;
use yii\data\Pagination;

/**
 * Class AdvService
 * @package addons\TinyShop\services\common
 * @author jianyan74 <751393839@qq.com>
 */
class StoreService extends Service
{

    protected string $key = 'D56BZ-AU2KW-NLNR7-RIOS5-OWLT2-3XBSW';
    protected string $map_table_name = '5e89a76ec05f1003f4a8f53f';

    /**
     * 获取周边的医院
     *
     * @param string $location
     * @return object
     */
    public function getNearbyStore(string $location, int $radius = 10000): object {
        $params = $this->mergeOptions([
            'location' => $location,
            'radius' => $radius,
            'orderby' => $location,
            'auto_extend' => 1
        ]);
        $url = "https://apis.map.qq.com/place_cloud/search/nearby";
        return self::get($url, $params);
    }

    /**
     *
     * 获取某个区域距离 $location 最近的医院
     *
     * @param string $region
     * @param string $location 腾讯坐标
     * @return object
     */
    public function getRegionStore(string $region, string $location): object {
        $params = $this->mergeOptions([
            'region' => $region,
            'orderby' => "distance({$location})",
            'page_size' => 30
        ]);
        $url = "https://apis.map.qq.com/place_cloud/search/region";
        return self::get($url, $params);
    }

    /**
     *
     * 获取某个区域距离 $location 最近的医院
     *
     * @param string $region
     * @param string $location GPS 坐标
     * @return object
     */
    public function getRegionStoreGps(string $region, string $location): object {
        $translateResult = $this->translate($location, 1);
        $tencentLng = $translateResult->locations[0]->lng;
        $tencentLat = $translateResult->locations[0]->lat;
        $location = "{$tencentLat},{$tencentLng}";

        return $this->getRegionStore($region, $location);
    }

    /**
     *
     * 获取所有门店
     *
     * @return object
     */
    public function getStoreList() {
        $url = 'https://apis.map.qq.com/place_cloud/data/list';
        return self::get($url);
    }

    /**
     * 坐标转换
     * type 输入的locations的坐标类型
     * 可选值为[1,6]之间的整数，每个数字代表的类型说明：
     * 1 GPS坐标
     * 2 sogou经纬度
     * 3 baidu经纬度
     * 4 mapbar经纬度
     * 5 [默认]腾讯、google、高德坐标
     * 6 sogou墨卡托
     *
     * @param string $locations
     * @param int $type
     * @return object
     */
    public function translate(string $locations, int $type) {
        $params = $this->mergeOptions([
            'locations' => $locations,
            'type' => $type
        ]);
        $url = 'https://apis.map.qq.com/ws/coord/v1/translate';

        return self::get($url, $params);
    }

    /**
     * 将基础配置 和 $options 合并
     *
     * @param array $options
     * @return array
     */
    public function mergeOptions(array $options): array {
        $initParams = [
            'key' => $this->key,
            'table_id' => $this->map_table_name
        ];
        return array_merge(
            $initParams,
            $options
        );
    }

    /**
     * @param string $url
     * @param array $params
     * @return object
     */
    public static function get(string $url, array $params = []): object {
        $url.= '?' . http_build_query($params, '&');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return json_decode($result);
    }
}