function base64Decode(text, charset) {
    return fetch(`data:text/plain;charset=${charset};base64,` + text).then(response => response.text());
}

// プッシュ通知を受け取ったとき
self.addEventListener('push', function (event) {
    event.waitUntil((async function () {
        let msg = event.data.text();
        msg = await base64Decode(msg);
        const data = JSON.parse(msg);
        const title = data.title;
        const options = {
            body: data.message,
            data: { url: data.url }, // 送信元のリンクを保存
            icon: "/favicon.ico"
        };
        await self.registration.showNotification(title, options);
    })());
});

// プッシュ通知のクリック時
self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    // 送信元のリンクを開く
    event.waitUntil(clients.openWindow(event.notification.data.url));
});
