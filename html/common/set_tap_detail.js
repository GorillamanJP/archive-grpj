// 行を押すだけで詳細が見れるようになるやつ
function set_tap_detail() {
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(function (row) {
        row.addEventListener("click", function () {
            const saleId = row.getAttribute("data-id");
            const form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "../show/");
            const hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "id");
            hiddenField.setAttribute("value", saleId);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        });
    });
}