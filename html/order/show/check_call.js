async function check_call() {
    try {
        const response = await fetch("./check_call.php");
        if (response.ok) {
            const data = await response.json();
            if (data.is_call) {
                document.getElementById("call_status").innerText = "呼び出し中";
                if (document.getElementById('allow_sound').checked) {
                    const sound = document.getElementById('notificationSound');
                    if (sound) {
                        sound.play().catch(error => {
                            console.error("Sound play error: ", error);
                        });
                    }
                }
            }
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setInterval(check_call, 10000);
});

check_call();