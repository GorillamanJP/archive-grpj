async function check_call() {
    try {
        const response = await fetch("./check_call.php");
        if (response.ok) {
            const data = await response.json();
            if (data.is_call) {
                document.getElementById("call_status").style = "";
                if (document.getElementById('allow_sound').checked) {
                    const sound = document.getElementById('notificationSound');
                    if (sound) {
                        sound.play().catch(error => {
                            console.error("Sound play error: ", error);
                        });
                    }
                }
            } else {
                if (response.status == 403) {
                    location.reload();
                }
                document.getElementById("call_status").style = "display: none;"
            }
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setInterval(check_call, 5000);
});

check_call();