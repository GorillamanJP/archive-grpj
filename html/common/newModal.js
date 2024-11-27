async function handleSubmit(event) {
  event.preventDefault();

  const formData = new FormData(event.target);
  setModalContent(formData);

  const result = await callModal();
  if (result) {
    event.target.submit();
  }
  return false;
}

function setModalContent(formData) {
  const itemNameElement = document.getElementById("confirmItemName");
  const priceElement = document.getElementById("confirmPrice");
  const previewImage = document.getElementById("confirmItemImage");
  const currentStockElement = document.getElementById("confirmCurrentStock");
  const newStockElement = document.getElementById("confirmNewStock");

  if (itemNameElement) {
    itemNameElement.textContent = formData.get("item_name") || "";
  }

  if (priceElement) {
    priceElement.textContent = formData.get("price") || "";
  }

  const itemImage = formData.get("item_image");
  if (previewImage) {
    if (itemImage && itemImage.size > 0) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImage.src = e.target.result;
      };
      reader.readAsDataURL(itemImage);
    } else {
      previewImage.src = document.getElementById("now_item_image")
        ? document.getElementById("now_item_image").src
        : "";
    }
  }

  const currentStock = parseInt(
    document.getElementById("currentStock")?.textContent || "0",
    10
  );
  const addQuantity = parseInt(formData.get("add_quantity") || "0", 10);
  const newStock = currentStock + addQuantity;

  if (currentStockElement) {
    currentStockElement.textContent = currentStock;
  }

  if (newStockElement) {
    newStockElement.textContent = newStock;
  }
}

async function callModal() {
  const modal = new bootstrap.Modal(document.getElementById("Modal"));
  modal.show();

  const buttonId = await waitForButtonPress([
    "confirm_button",
    "cancel_button",
  ]);
  return buttonId === "confirm_button";
}

function waitForButtonPress(buttonIds) {
  return new Promise((resolve) => {
    buttonIds.forEach((buttonId) => {
      const button = document.getElementById(buttonId);
      button.addEventListener("click", function onClick() {
        button.removeEventListener("click", onClick);
        resolve(buttonId);
      });
    });
  });
}
