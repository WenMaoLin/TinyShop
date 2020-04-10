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

    /*
     * 获取附近医院门店
     * */
    public function getNearbyStore(string $location) {
        $params = $this->mergeOptions([
            'location' => $location,
            'radius' => 10000,
            'orderby' => $location,
            'auto_extend' => 1
        ]);
        $url = "https://apis.map.qq.com/place_cloud/search/nearby";

        return self::get($url, $params);
    }

    /*
     * 获取某个区域的医院
     * */
    public function getRegionStore(string $region, string $location) {
        $params = $this->mergeOptions([
            'region' => $region,
            'orderby' => "distance({$location})",
            'page_size' => 30
        ]);
        $url = "https://apis.map.qq.com/place_cloud/search/region";

        return self::get($url, $params);
    }

    /*
     * 获取所有医院
     * */
    public function getStoreList() {
        $params = [
            'key' => $this->key,
            'table_id' => $this->map_table_name
        ];
        $url = 'https://apis.map.qq.com/place_cloud/data/list';

        return self::get($url, $params);
    }

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

    public static function paramsToString(array $params): string {
        foreach ($params as $key => $value) {
            $params[$key] = "$key=$value";
        }
        return join('&', $params);
    }

    public static function get(string $url, array $params) {
        $url.= '?' . self::paramsToString($params);

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