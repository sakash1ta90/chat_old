<?php
declare(strict_types=1);

new Class
{
    private const RESPONSE_VIEW = '<div class="%s_user">%s</div><div class="%s_balloon">%s</div>' . PHP_EOL;
    private const LOG_FILE = 'chat.log';
    private $getValue;

    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->getValue = $_GET;
        echo $this->getValue['mode'] == 0 ? $this->writeChatLog() : $this->readChatLog();
    }

    /**
     * チャットログ読み込み
     */
    public function readChatLog(): string
    {
        // モードが一致する場合、出力用HTML作成処理
        if (!$fp = fopen(self::LOG_FILE, 'r')) {
            return 'could not open';
        }

        $ret = '';
        while (!feof($fp) && false !== $get = fgets($fp)) {
            $getAry = json_decode($get, true);
            if (empty($this->getValue['type']) || 'html' !== $this->getValue['type']) {
                header('Content-Type: text/javascript; charset=utf-8');
                $ret = $get;
            } else {
                $target = $this->getValue['user'] == $getAry['user'] ? 'right' : 'left';
                $ret .= sprintf(self::RESPONSE_VIEW, $target, $getAry['user'], $target, $getAry['message']);
            }
        }
        fclose($fp);
        return $ret;
    }

    /**
     * チャットログ書き込み
     */
    public function writeChatLog(): string
    {
        // ファイルをオープンできたか
        if (!$fp = fopen(self::LOG_FILE, 'a+')) {
            return 'could not open';
        }
        // 書き込みできたか
        $logJson = json_encode([
                'user' => htmlspecialchars($this->getValue['user'], ENT_QUOTES, 'utf-8'),
                'message' => htmlspecialchars($this->getValue['message'], ENT_QUOTES, 'utf-8'),
                'date' => time()
            ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        if (false === fwrite($fp, $logJson)) {
            return 'could not write';
        }
        fclose($fp);
        return $this->readChatLog();
    }
};