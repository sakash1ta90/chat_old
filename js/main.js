$(() => {
    // 投稿ボタンクリック
    $('#greet').on('click', () => {
        if (!$('#user').val() || !$('#message').val()) {
            return;
        }
        $.get('bbs.php', {
            user: $('#user').val(),
            message: $('#message').val(),
            mode: 0, // 書き込み
            type: 'html'
        }, replace);
        $('#message').val('');
    });

    // 一定間隔でログをリロードする
    const logAll = () => {
        // ログリロード処理
        $.get('bbs.php', {
            user: $('#user').val(),
            mode: 1, // 読み込み
            type: 'html'
        }, replace);

        // ポーリング
        setTimeout(() => {
            logAll();
        }, 5000); // リロード時間はここで調整
    };

    // 取得したHTMLでチャットを更新する
    const replace = data => {
        $('#result').html(data);
    };

    logAll();
});
