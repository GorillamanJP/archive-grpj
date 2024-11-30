async function check_receive() {
    try {
        const response = await fetch("../is_receive_bool.php");
        if (response.ok) {
            const data = await response.json();
            if (data.is_receive) {
                location.href = "../receive/";
            } else if (data.is_cancel) {
                location.href = "../cancel/";
            }
        } else if (response.status == 403) {
            location.reload();
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setInterval(check_receive, 5000);
});

check_receive();