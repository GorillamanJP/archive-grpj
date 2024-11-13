async function check_receive() {
    try {
        const response = await fetch("../is_receive_bool.php");
        if (response.ok) {
            const data = await response.json();
            if (data.is_receive) {
                location.href = "../receive/";
            }
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setInterval(check_receive, 10000);
});