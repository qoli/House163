<?
error_reporting(E_ALL & ~E_NOTICE);
require_once 'MainFunction.php';

$productid = _GET("productid", "");
$keyword   = _GET("keyword", "");
$mode      = _GET("mode", "xpath");

//dump($_GET);
echo "返回搜索：<br/><a href=search.php?keyword=$keyword> 关键字 → $keyword</a><br/>";

if ($mode == 'reg') {
    echo '切换匹配模式 <br/><a href="detail.php?mode=xpath&keyword=' . $keyword . '&productid=' . $productid . '"> XPATH 匹配 → ' . $productid . '</a><br/>';
} else {
    echo '切换匹配模式 <br/><a href="detail.php?mode=reg&keyword=' . $keyword . '&productid=' . $productid . '"> 正则匹配 → ' . $productid . '</a><br/>';
}
echo "<br/>";

if ($mode == 'reg') {
    echo "正则匹配模式：<br/>";
    $file = file_get_contents('http://xf.house.163.com/bj/' . $productid . '.html');
    $file = HtmlFormat($file);
    // dump($file);

    $ini = parse_ini_file("preg.ini");

    echo "{";
    echo "<br/>";

    $n   = 0;
    $len = count($ini);
    foreach ($ini as $key => $value) {
        $n++;
        $rule = '/' . $value . '/i';
        preg_match_all($rule, $file, $match);
        if ($n != $len) {
            echo '"' . $key . '": "' . StrFormat($match[1][0]) . '",';
        } else {
            echo '"' . $key . '": "' . StrFormat($match[1][0]) . '"';
        }
        echo "<br/>";
    }

    echo "}";
}

if ($mode == 'xpath') {
    echo "XPATH 匹配模式：<br/>";
    $file = file_get_contents('http://xf.house.163.com/bj/' . $productid . '.html');
    // echo $file;
    // $file = HtmlFormat($file);
    // dump($file);

    $ini = parse_ini_file("xpath.ini");
    // dump($ini);

    $html_dom = new DOMDocument();
    @$html_dom->loadHTML($file);
    $x_path = new DOMXPath($html_dom);

    echo "{";
    echo "<br/>";

    $n   = 0;
    $len = count($ini);
    foreach ($ini as $key => $value) {
        $n++;
        $nodes = $x_path->query($value);
        foreach ($nodes as $i => $node) {
            if ($n != $len) {
                echo '"' . $key . '": "' . trim($node->nodeValue) . '",';
            } else {
                echo '"' . $key . '": "' . trim($node->nodeValue) . '"';
            }
        }
        echo "<br/>";
    }

    echo "}";
}
