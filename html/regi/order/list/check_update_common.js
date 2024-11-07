async function get_update() {
    try {
        const resp = await fetch("./updated_data.php");
        if (resp.ok) {
            const data = await resp.json();
            if (data.table) {
                set_html("refresh", data.table);
            }
            if (data.time) {
                set_html("last-update", data.time);
            }
            if (data.sold_table) {
                set_html("total_sold_table", data.sold_table);
            }
        } else {
            console.error("Error: " + resp.statusText);
        }
    } catch (error) {
        console.error("Error: " + error);
    }
}

function set_html(id, data) {
    document.getElementById(id).innerHTML = data;
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