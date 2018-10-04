const getArgs = ['chat.php', {}, data => $('#result').html(data)];

// 一定間隔でログをリロードする
const reloadLog = () => {
    getArgs[1] = {
        user: $('#user').val(),
        mode: 1, // 読み込み
        type: 'html'
    };
    $.get(...getArgs);
    $('#talkField').animate({scrollTop: $('#result').height()}, 'fast');
};

// 投稿ボタンクリック
$('#greet').on('click', () => {
    getArgs[1] = {
        user: $('#user').val(),
        message: $('#message').val(),
        mode: 0, // 書き込み
        type: 'html'
    };
    if (!getArgs[1].user || !getArgs[1].message) {
        return;
    }
    $.get(...getArgs);
    $('#message').val('');
    $('#talkField').animate({scrollTop: $('#result').height()}, 'fast');
});
reloadLog();

// ポーリング
setInterval(reloadLog, 5000); // リロード時間