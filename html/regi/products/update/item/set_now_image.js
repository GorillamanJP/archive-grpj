document.addEventListener("DOMContentLoaded", function () {
  let img = document.getElementById("now_item_image");
  let file_input = document.getElementById("new_item_image");

  fetch(img.src)
    .then((res) => res.blob())
    .then((blob) => {
      let file = new File([blob], "now_image.jpg", { type: "image/jpeg" });
      let data_transfer = new DataTransfer();
      data_transfer.items.add(file);
      file_input.files = data_transfer.files;
    });
});

document
  .getElementById("new_item_image")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("now_item_image").src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
