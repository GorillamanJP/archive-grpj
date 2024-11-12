async function get_update() {
    try {
        let resp;
        const updated_data_path = typeof custom_updated_data_path == "string" ? custom_updated_data_path : "./updated_data.php";
        if (document.getElementById("page_no")) {
            const page_no = document.getElementById("page_no").innerText;
            const limit = 20;
            const offset = (page_no - 1) * limit;
            resp = await fetch(updated_data_path, {
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
            resp = await fetch(updated_data_path);
        }
        if (resp.ok) {
            const data = await resp.json();
            set_html_from_json(data);
            if (typeof run_custom_function === "function") {
                run_custom_function();
            }
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
        const check_update_path = typeof custom_check_update_path == "string" ? custom_check_update_path : "./check_update.php";
        const resp = await fetch(check_update_path);
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
