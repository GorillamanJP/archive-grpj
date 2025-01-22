self.addEventListener("load", async () => {
    if ("serviceWorker" in navigator) {
        window.sw = await navigator.serviceWorker.register("./service_worker.js", { scope: "./" });
    }

    displayNotificationStatus();
});

class Subscription {
    constructor() {
        this.Endpoint = "";
        this.UserPublicKey = "";
        this.UserAuthToken = "";
    }
}

async function get_subscription() {
    const notify_status_text = document.getElementById("notify_status_text");
    notify_status_text.innerText = "処理中";
    if ("Notification" in window) {
        let permission = Notification.permission;
        if (permission === "denied") {
            notify_status_text.innerText = "拒否";
            return null;
        }
    }
    const app_server_key = await fetch("./get_public_key.php").then(resp => resp.json()).then(data => data.key);
    const server_key = urlB64ToUint8Array(app_server_key);
    let subscription = undefined;
    try {
        subscription = await window.sw.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: server_key
        });
    } catch (e) {
        notify_status_text.innerText = "登録エラー";
        console.error(e);
        return null;
    }
    const key = subscription.getKey("p256dh");
    const token = subscription.getKey("auth");
    let obj = new Subscription();
    obj.Endpoint = subscription.endpoint;
    obj.UserPublicKey = btoa(String.fromCharCode.apply(null, new Uint8Array(key)));
    obj.UserAuthToken = btoa(String.fromCharCode.apply(null, new Uint8Array(token)));
    return obj;
}

function urlB64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i)
        outputArray[i] = rawData.charCodeAt(i);
    return outputArray;
}

async function subscribe_push() {
    let subscription = await get_subscription();
    if (subscription == null) {
        return;
    }

    try {
        const resp = await fetch("./subscribe.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                "endpoint": subscription.Endpoint,
                "userPublicKey": subscription.UserPublicKey,
                "userAuthToken": subscription.UserAuthToken
            })
        });
        if (resp.ok) {
            document.getElementById("notify_status_text").innerText = "有効";
            document.getElementById("notify_enable_text").innerText = "無効";
            document.getElementById("notify_button").classList.remove("btn-success");
            document.getElementById("notify_button").classList.add("btn-danger");
        }
    } catch (e) {
        document.getElementById("notify_status_text").innerText = "登録処理エラー";
        console.error(e);
    }
}

async function de_subscribe_push() {
    let subscription = await get_subscription();
    if (subscription == null) {
        return;
    }

    try {
        const resp = await fetch("./de_subscribe.php");
        if (resp.ok) {
            document.getElementById("notify_status_text").innerText = "登録解除";
            document.getElementById("notify_enable_text").innerText = "有効";
            document.getElementById("notify_button").classList.remove("btn-danger");
            document.getElementById("notify_button").classList.add("btn-success");
        }
    } catch (e) {
        document.getElementById("notify_status_text").innerText = "登録解除処理エラー";
        console.error(e);
    }
}

function displayNotificationStatus() {
    if ("Notification" in window) {
        let permission = Notification.permission;
        if (permission === "granted") {
            document.getElementById("notify_status_text").innerText = "許可";
        } else if (permission === "denied") {
            document.getElementById("notify_status_text").innerText = "拒否";
        }
    }
}

document.getElementById("notify_button").addEventListener("click", () => {
    const enable = document.getElementById("notify_enable_text").innerText === "有効";
    if (enable) {
        subscribe_push();
    } else {
        de_subscribe_push();
    }
});
