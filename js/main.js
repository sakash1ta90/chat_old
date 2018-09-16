$(() => {
    // 投稿ボタンクリック
    $('#greet').on('click', () => {
        if (!$('#user').val() || !$('#message').val()) {
            return;
        }
        $.get('bbs.php', {
            user: $('#user').val(),
            message: $('#message').val(),
            mode: 0 // 書き込み
        }, data => {
            $('#result').html(data);
            // scTarget();
        });
    });
    $('#message').val('');
    loadLog();
    logAll();
});

// ログをロードする
const loadLog = () => {
    $.get('bbs.php', {
        user: $('#user').val(),
        mode: 1 // 読み込み
    }, data => {
        $('#result').html(data);
        // scTarget();
    });
};

// 一定間隔でログをリロードする
const logAll = () => {
    setTimeout(() => {
        loadLog();
        logAll();
    }, 5000); // リロード時間はここで調整
};

// 画面を最下部へ移動させる
// const scTarget = () => {
//     $("#talkField").animate({
//         scrollTop: $("#end").offset().top
//     }, 0, "swing"); //swingで0が良さそう
//     return false;
// };
