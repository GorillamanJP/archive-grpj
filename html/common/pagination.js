function change_value(value) {
    const page_no = document.getElementById("page_no");
    const page_end = document.getElementById("page_end").innerText;
    const after_page_no = page_no.innerText - value;
    if (after_page_no < 1) {
        page_no.innerText = 1;
    } else if (after_page_no > page_end) {
        page_no.innerText = page_end;
    } else {
        page_no.innerText = after_page_no;
    }
}
document.getElementById("page_prev").addEventListener("click", function () {
    change_value(1);
    get_update();
});
document.getElementById("page_next").addEventListener("click", function () {
    change_value(-1);
    get_update();
});