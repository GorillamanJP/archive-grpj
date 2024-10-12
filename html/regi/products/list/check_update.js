function check_update() {
    fetch("./check_update.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({ "last_update": document.getElementById("last-update").innerText })
    }).then((response) => {
        if (!response.ok) {
            return response.json().then((error) => {
                throw new Error(error.error);
            });
        }
        return response.json();
    }).then((data) => {
        if (data) {
            location.reload();
        }
    }).catch((error) => console.error("Error:", error));
}
setInterval(check_update, 10000);