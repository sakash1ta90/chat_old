$(() => {
    const getArgs = ['chat.php', {}, data => $('#result').html(data)];

    // 一定間隔でログをリロードする
    const reloadLog = () => {
        getArgs[1] = {
            user: $('#user').val(),
            mode: 1, // 読み込み
            type: 'html'
        };
        $.get(...getArgs);

        // 最下部にスクロールさせる
        $('#talkField').animate({scrollTop: $('#end').offset().top}, 'fast');

        // ポーリング
        setTimeout(() => {
            reloadLog();
        }, 5000); // リロード時間はここで調整
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
    });

    reloadLog();
});
