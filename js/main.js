$(() => {
    const args = ['chat.php', {}, data => $('#result').html(data)];
    // 投稿ボタンクリック
    $('#greet').on('click', () => {
        if (!$('#user').val() || !$('#message').val()) {
            return;
        }
        args[1] = {
            user: $('#user').val(),
            message: $('#message').val(),
            mode: 0, // 書き込み
            type: 'html'
        };
        $.get(...args);
        $('#message').val('');
    });

    // 一定間隔でログをリロードする
    const logAll = () => {
        args[1] = {
            user: $('#user').val(),
            mode: 1, // 読み込み
            type: 'html'
        };
        // ログリロード処理
        $.get(...args);

        // ポーリング
        setTimeout(() => {
            logAll();
        }, 5000); // リロード時間はここで調整
    };
    logAll();
});
