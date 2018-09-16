<?php
declare(strict_types=1);

define('RESPONSE_VIEW', '<div class="%s_user">%s</div><div class="%s_balloon">%s</div>' . PHP_EOL);
define('LOG_FILE', 'chat.log');

if ('0' === $_GET['mode']) {
    // ファイルをオープンできたか
    if (!$fp = fopen(LOG_FILE, 'a+')) {
        echo 'could not open';
        exit();
    }
    // 書き込みできたか
    $logJson = json_encode([
            'user' => htmlspecialchars($_GET['user'], ENT_QUOTES, 'utf-8'),
            'message' => htmlspecialchars($_GET['message'], ENT_QUOTES, 'utf-8'),
            'date' =>  time()
        ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
    if (false === fwrite($fp, $logJson)) {
        echo 'could not write';
        exit();
    }
    fclose($fp);
}
if ('1' === $_GET['mode'] || '0' === $_GET['mode']) {
    // モードが一致する場合、出力用HTML作成処理
    if (!$fp = fopen(LOG_FILE, 'r')) {
        return 'could not open';
    }

    $ret = '';
    while (!feof($fp) && false !== $get = fgets($fp)) {
        $getAry = json_decode($get, true);
        if (empty($_GET['type']) || 'html' !== $_GET['type']) {
            header("Content-Type: text/javascript; charset=utf-8");
            echo $get;
        } else {
            $target = $_GET['user'] == $getAry['user'] ? 'right' : 'left';
            $ret .= sprintf(RESPONSE_VIEW, $target, $getAry['user'], $target, $getAry['message']);
        }
    }
    fclose($fp);
    echo $ret;
}