<?
require_once 'MainFunction.php';
dump($_GET);

$keyword = _GET("keyword", "");

$json = file_get_contents('http://xf.house.163.com/bj/es/searchProducts?district=0&plate=0&metro=0&metro_station=0&price=0&total_price=0&huxing=0&property=0&base_kpsj=0&base_lpts=0&buystatus=0&base_hxwz=0&param_zxqk=0&orderby=1&use_priority_str=buystatus&keyword=' . urlencode($keyword) . '&pageSize=10&pageno=1');

$data = json_decode($json);

// dump($data->dataList);

foreach ($data->dataList as $key => $value) {
    echo '<a href="detail.php?mode=xpath&keyword=' . $keyword . '&productid=' . $value->productid . '"> 详细 → ' . $value->name . '</a>';
    dump($value);
}
