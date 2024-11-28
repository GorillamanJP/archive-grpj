async function fetchNotifications() {
    try {
        const response = await fetch("/regi/notify/notify.php");
        if (response.ok) {
            const data = await response.json();
            if (data.hasData && data.notifications.length > 0) {
                data.notifications.forEach(notification => displayNotification(notification));
            }
        } else {
            console.error("Error fetching notifications: " + response.statusText);
        }
    } catch (error) {
        console.error("Error fetching notifications: " + error);
    }
}

function displayNotification(notification) {
    const notificationContainer = document.getElementById("notifications");

    const toastHTML = `
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="20000">
            <div class="toast-header">
                <i class="bi bi-info-circle-fill me-2 text-primary"></i>
                <strong class="me-auto">${notification.title}</strong>
                <small>${notification.sent_date}</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${notification.message}
            </div>
        </div>
    `;

    // Insert the new toast at the beginning of the notification container
    notificationContainer.insertAdjacentHTML('afterbegin', toastHTML);

    // Initialize the new toast element
    const toastElement = notificationContainer.querySelector('.toast');
    const toast = new bootstrap.Toast(toastElement);
    toast.show();

    const audio = new Audio("/regi/notify/notify.mp3");
    audio.play();
}

document.addEventListener("DOMContentLoaded", function () {
    // 10秒おきにfetchNotificationsを実行
    setInterval(fetchNotifications, 10000);
});
fetchNotifications();