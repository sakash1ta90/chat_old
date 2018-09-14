<?php
declare(strict_types=1);

define('LOG_FILE', 'chat.log');
if ('0' == $_GET['mode']) {
    // ファイルをオープンできたか
    if (!$fp = fopen(LOG_FILE, 'a+')) {
        echo 'could not open';
        exit();
    }
    // 書き込みできたか
    $logJson = json_encode([
            'user' => htmlspecialchars($_GET['user'], ENT_QUOTES, 'utf-8'),
            'message' => htmlspecialchars($_GET['message'], ENT_QUOTES, 'utf-8')
        ]) . PHP_EOL;
    if (false === fwrite($fp, $logJson)) {
        echo 'could not write';
        exit();
    }
    fclose($fp);

    echo getRenderHtmlFromLog();
} elseif ('1' == $_GET['mode']) {
    echo getRenderHtmlFromLog();
}

/**
 * 出力用HTML作成処理
 *
 * @return string
 */
function getRenderHtmlFromLog(): string
{
    if (!$fp = fopen(LOG_FILE, 'r')) {
        return 'could not open';
    }

    $ret = '';
    while (!feof($fp) && false !== $get = fgets($fp)) {
        $getAry = json_decode($get, true);
        if ($_GET['user'] == $getAry['user']) {
            $ret .= "<div class='user_right'>{$getAry['user']}</div><div class='right_balloon'>{$getAry['message']}</div>\n";
        } else {
            $ret .= "<div class='user'>{$getAry['user']}</div><div class='left_balloon'>{$getAry['message']}</div>\n";
        }
    }
    fclose($fp);
    return $ret;
}