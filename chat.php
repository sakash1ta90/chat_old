<?php
declare(strict_types=1);

new class
{
    private const RESPONSE_VIEW = '<div class="%s_user">%s</div><div class="%s_balloon">%s</div>' . PHP_EOL;
    private const LOG_FILE = 'chat.log';
    private $mode;
    private $type;
    private $user;
    private $message;

    /**
     *  constructor.
     */
    public function __construct()
    {
        $this->mode = filter_input(INPUT_GET, 'mode', FILTER_VALIDATE_INT);
        $this->type = filter_input(INPUT_GET, 'type');
        $this->user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_ENCODED);
        echo $this->mode == 0 ? $this->writeChatLog() : $this->readChatLog();
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
            if (empty($this->type) || 'html' !== $this->type) {
                header('Content-Type: text/javascript; charset=utf-8');
                $ret = $get;
            } else {
                $target = $this->user == $getAry['user'] ? 'right' : 'left';
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
        $this->message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_ENCODED);

        // ファイルをオープンできたか
        if (!$fp = fopen(self::LOG_FILE, 'a')) {
            return 'could not open';
        }
        // 書き込みできたか
        $logJson = json_encode([
                'user' => $this->user,
                'message' => $this->message,
                'date' => time()
            ], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        if (false === fwrite($fp, $logJson)) {
            return 'could not write';
        }
        fclose($fp);
        return $this->readChatLog();
    }
};