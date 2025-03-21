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

  const result = await waitForButtonPress();
  return result;
}

function waitForButtonPress() {
  return new Promise((resolve) => {
    let isHandled = false; // 処理が実行されたかどうかを追跡

    const keydownHandler = function (event) {
      if (event.key === "Enter" && !isHandled) {
        event.preventDefault(); // Enterキーのデフォルト動作を無効にする
        const modal = document.getElementById("Modal");
        if (modal && modal.classList.contains("show")) {
          isHandled = true; // 処理が実行されたと記録
          const confirmButton = document.getElementById("confirm_button");
          if (confirmButton) {
            confirmButton.click();
            resolve(true);
            document.removeEventListener("keydown", keydownHandler);
          }
        }
      }
    };

    document.addEventListener("keydown", keydownHandler);

    const confirmButton = document.getElementById("confirm_button");
    const cancelButton = document.getElementById("cancel_button");

    if (confirmButton) {
      confirmButton.addEventListener("click", function onClick() {
        if (!isHandled) {
          isHandled = true;
          document.removeEventListener("keydown", keydownHandler);
          confirmButton.removeEventListener("click", onClick);
          resolve(true);
        }
      });
    }

    if (cancelButton) {
      cancelButton.addEventListener("click", function onClick() {
        if (!isHandled) {
          isHandled = true;
          document.removeEventListener("keydown", keydownHandler);
          cancelButton.removeEventListener("click", onClick);
          const closeButton = document.querySelector(".close");
          if (closeButton) closeButton.click();
          resolve(false);
        }
      });
    }
  });
}
