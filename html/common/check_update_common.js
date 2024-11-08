async function get_update() {
    try {
        let resp;
        if (document.getElementById("page_no")) {
            const page_no = document.getElementById("page_no").innerText;
            const limit = 20;
            const offset = (page_no - 1) * limit;
            resp = await fetch("./updated_data.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    "page_offset": offset,
                    "page_limit": limit,
                })
            });
        } else {
            resp = await fetch("./updated_data.php");
        }
        if (resp.ok) {
            const data = await resp.json();
            set_html_from_json(data);
            set_tap_detail();
        } else {
            console.error("Error: " + resp.statusText);
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

function set_html_from_json(data) {
    for (const [key, value] of Object.entries(data)) {
        set_html(key, value);
    }
}

function set_html(id, data) {
    const element = document.getElementById(id);
    if (element) {
        element.innerHTML = data;
    } else {
        console.warn(`Element with id ${id} not found.`);
    }
}

async function check_update() {
    try {
        const resp = await fetch("./check_update.php");
        if (resp.ok) {
            const data = await resp.json();
            if (data.is_update) {
                await get_update();
            }
        } else {
            console.error("Update Error: " + resp.statusText);
        }
    } catch (error) {
        console.error("Catch Error: " + error);
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setInterval(check_update, 10000);
});

get_update();
