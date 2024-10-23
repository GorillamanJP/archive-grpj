// 現在の時刻を取得する関数
function getCurrentTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = ('0' + (now.getMonth() + 1)).slice(-2);
    const day = ('0' + now.getDate()).slice(-2);
    const hours = ('0' + now.getHours()).slice(-2);
    const minutes = ('0' + now.getMinutes()).slice(-2);
    const seconds = ('0' + now.getSeconds()).slice(-2);
    return `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;
}

// 更新をチェックする関数
async function check_update() {
    try {
        const response = await fetch('./check_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'last_update': document.getElementById('last-update').innerText,
                'last_products_count': document.getElementById("products_count").value,
            })
        });

        if (!response.ok) {
            const error = await response.text();
            throw new Error(error);
        }

        const data = await response.text();
        if (data.trim() !== "") { // データが空でない場合のみ更新
            document.getElementById('refresh').innerHTML = data;
            document.getElementById('last-update').innerText = getCurrentTime(); // 最終更新時刻を更新

            document.getElementById("update_type_msg_notify").innerText = document.getElementById("update_type_msg").innerText;
            
            const toastLiveExample = document.getElementById('liveToast');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastBootstrap.show();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

// 10秒ごとに更新をチェック
setInterval(check_update, 10000);

// ページが読み込まれたときにデータを取得
window.onload = check_update;