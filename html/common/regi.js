document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM fully loaded and parsed"); // DOMのロード確認ログ

  setupEventListeners(); // イベントリスナーの設定関数を呼び出し
});

function setupEventListeners() {
  const buttons = document.querySelectorAll(
    ".quantity-button.increment, .quantity-button.decrement"
  );
  console.log("Buttons found:", buttons.length); // ボタンの数を確認

  buttons.forEach((button) => {
    addLongPressEvent(
      button,
      button.classList.contains("increment") ? "increment" : "decrement"
    );
  });

  // Add any other event listeners here
}

function addLongPressEvent(button, action) {
  console.log("Button:", button); // ボタンが正しく選択されているか確認
  let timer;
  let interval;
  let longPress = false; // 長押しを示すフラグ
  let isTouch = false; // タッチイベントかどうかを示すフラグ
  let preventClick = false; // 連続タップを防ぐフラグ
  const delay = 500; // 長押しの開始までの遅延 (ミリ秒)
  const speed = 100; // 長押し中の繰り返し速度 (ミリ秒)
  const tapDelay = 300; // 連続タップを防ぐディレイ (ミリ秒)

  const startLongPress = (e) => {
    e.preventDefault(); // デフォルト動作をキャンセル
    isTouch = e.type.startsWith("touch"); // タッチイベントかどうかを判定
    longPress = false; // 長押しフラグをリセット
    console.log("Start Long Press Event Triggered", action); // デバッグ用ログ
    timer = setTimeout(() => {
      longPress = true; // 長押し開始
      interval = setInterval(() => {
        console.log("Interval Triggered", action); // デバッグ用ログ
        changeQuantity(
          button,
          action === "increment" ? 1 : -1,
          button.dataset.productId,
          button.dataset.stockQuantity
        );
      }, speed);
    }, delay);
  };

  const endLongPress = () => {
    console.log("End Long Press Event Triggered"); // デバッグ用ログ
    clearTimeout(timer);
    clearInterval(interval);
  };

  const handleClick = (e) => {
    if (!longPress && !preventClick) {
      console.log("Click Event Triggered", action); // デバッグ用ログ
      changeQuantity(
        button,
        action === "increment" ? 1 : -1,
        button.dataset.productId,
        button.dataset.stockQuantity
      );
      preventClick = true; // 連続タップを防ぐフラグをセット
      setTimeout(() => {
        preventClick = false; // 一定時間後にフラグをリセット
      }, tapDelay);
    }
    endLongPress();
  };

  button.addEventListener("mousedown", startLongPress);
  button.addEventListener("mouseup", handleClick);
  button.addEventListener("mouseleave", endLongPress);
  button.addEventListener("touchstart", startLongPress);
  button.addEventListener("touchend", handleClick);
  button.addEventListener("touchcancel", endLongPress);
}

let totalCount = 0;
let totalPrice = 0;
let productCounter = 0;

function addToCart(productName, price, stockQuantity, productId) {
  const cartTable = document
    .getElementById("cart-table")
    .getElementsByTagName("tbody")[0];
  let existingRow = null;
  for (let row of cartTable.rows) {
    if (row.cells[0] && row.cells[0].innerText === productName) {
      existingRow = row;
      break;
    }
  }
  if (existingRow) {
    const currentQuantity = parseInt(
      existingRow.cells[2].children[1].innerText
    );
    if (currentQuantity < stockQuantity) {
      changeQuantity(
        existingRow.cells[2].children[1],
        1,
        productId,
        stockQuantity
      );
    } else {
      showCustomAlert("在庫が足りません。");
    }
  } else {
    if (stockQuantity > 0) {
      const row = document.createElement("tr");
      row.innerHTML = `<td>${productName}</td><td>${price}円</td><td class="quantity-column"><button class="btn btn-outline-success btn-lg quantity-button increment" data-product-id="${productId}" data-stock-quantity="${stockQuantity}">＋</button><span>1個</span><button class="btn btn-outline-success btn-lg quantity-button decrement" data-product-id="${productId}" data-stock-quantity="${stockQuantity}">－</button></td><td class="delete-column"><button class="btn btn-outline-danger" onclick="removeFromCart(this, ${price}, ${productId})">削除</button></td>`;
      cartTable.appendChild(row);
      updateTotals(price, 1);
      updateStockDisplay(productId, -1);
      addHiddenInputs(productId, 1);
      // 追加されたボタンに長押しイベントを設定
      const newIncrementButton = row.querySelector(
        ".quantity-button.increment"
      );
      const newDecrementButton = row.querySelector(
        ".quantity-button.decrement"
      );
      addLongPressEvent(newIncrementButton, "increment");
      addLongPressEvent(newDecrementButton, "decrement");
    } else {
      showCustomAlert("在庫が足りません。");
    }
  }
}

function addHiddenInputs(productId, quantity) {
  const form = document.getElementById("form");
  productCounter++;
  const productIdInput = document.createElement("input");
  productIdInput.type = "hidden";
  productIdInput.name = "product_id[]";
  productIdInput.id = `product_id_${productCounter}`;
  productIdInput.value = productId;
  const quantityInput = document.createElement("input");
  quantityInput.type = "hidden";
  quantityInput.name = "quantity[]";
  quantityInput.id = `quantity_${productCounter}`;
  quantityInput.value = quantity;
  form.appendChild(productIdInput);
  form.appendChild(quantityInput);
}

function updateForm(productId, quantity) {
  const productIdInputs = document.getElementsByName("product_id[]");
  const quantityInputs = document.getElementsByName("quantity[]");
  for (let i = 0; i < productIdInputs.length; i++) {
    if (productIdInputs[i].value == productId) {
      quantityInputs[i].value = quantity;
      return;
    }
  }
  addHiddenInputs(productId, quantity);
}

function updateStockDisplay(productId, change) {
  const stockElement = document.getElementById(`stock-${productId}`);
  const currentStock = parseInt(stockElement.innerText.match(/\d+/)[0]);
  stockElement.innerText = `【残${currentStock + change}個】`;
}

function updateTotals(price, quantity) {
  totalCount += quantity;
  totalPrice += price;
  const totalCountCell = document.getElementById("total-count");
  const totalPriceCell = document.getElementById("total-price");
  totalCountCell.innerText = `${Math.max(totalCount, 0)}個`;
  totalPriceCell.innerText = `${Math.max(totalPrice, 0)}円`;
}

function removeFromCart(button, price, productId) {
  const row = button.parentNode.parentNode;
  const quantity = parseInt(row.cells[2].children[1].innerText);
  row.remove();
  updateTotals(-price * quantity, -quantity); // 合計金額と合計個数を正しく更新
  updateStockDisplay(productId, quantity); // 在庫を戻す
  const productIdInputs = document.getElementsByName("product_id[]");
  const quantityInputs = document.getElementsByName("quantity[]");
  for (let i = 0; i < productIdInputs.length; i++) {
    if (productIdInputs[i].value == productId) {
      productIdInputs[i].remove();
      quantityInputs[i].remove();
      break;
    }
  }
}

function showCustomAlert(message) {
  const alertBox = document.createElement("div");
  alertBox.classList.add("custom-alert");
  alertBox.innerText = message;
  document.body.appendChild(alertBox);
  alertBox.style.display = "block";

  setTimeout(() => {
    alertBox.style.display = "none";
    alertBox.remove();
  }, 2000); // 2秒後に自動的に消える
}

function changeQuantity(button, change, productId, stockQuantity) {
  console.log("changeQuantity called", {
    button,
    change,
    productId,
    stockQuantity,
  }); // デバッグ用ログ
  const quantityCell = button.parentNode.children[1];
  let currentQuantity = parseInt(quantityCell.innerText);
  const price = parseInt(button.parentNode.parentNode.cells[1].innerText); // 価格を取得
  const newQuantity = currentQuantity + change;
  if (newQuantity < 1) {
    return; // 最小個数を1に制限
  } else if (newQuantity > stockQuantity) {
    if (!button.hasAttribute("data-alert-shown")) {
      // アラートが未表示の場合のみ表示
      showCustomAlert("在庫が足りません。");
      button.setAttribute("data-alert-shown", "true");
      setTimeout(() => {
        button.removeAttribute("data-alert-shown");
      }, 2000); // 2秒後にフラグをリセット
    }
    return;
  }
  quantityCell.innerText = `${newQuantity}個`;
  updateTotals(price * change, change);
  updateStockDisplay(productId, -change); // 在庫数を更新
  updateForm(productId, newQuantity); // フォームを更新
}