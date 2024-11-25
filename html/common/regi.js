// カートの初期化
let cart = {};

// グローバルに interval 変数を宣言
let incrementInterval;
let decrementInterval;
let incrementTimeout;
let decrementTimeout;

let currentIncrementInterval = null;
let currentDecrementInterval = null;

// 非同期更新で使うやつ
function run_custom_function() {
  adjustCartForStock();
  setupEventHandlers();
  updateStock();
}
const custom_check_update_path = "/common/index/check_update.php";
const custom_updated_data_path = "/common/index/updated_data.php";

// 非同期更新で使うやつ　ここまで

// カスタムアラートの関数
// ユーザーにメッセージを表示し、2秒後に自動で消える
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

// 作成しなおし　カート系処理
function updateCart() {
  const cartTableBody = document.querySelector("#cart-table tbody");
  const totalCountSpan = document.querySelector("#total-count");
  const totalPriceSpan = document.querySelector("#total-price");
  const form = document.querySelector("#form");

  // カートテーブルとフォーム入力をクリア
  cartTableBody.innerHTML = "";
  form.innerHTML = "";

  let totalCount = 0;
  let totalPrice = 0;

  // カート内の各商品の情報をテーブルとフォームに追加
  for (const id in cart) {
    const product = cart[id];
    totalCount += product.quantity;
    totalPrice += product.price * product.quantity;

    // カートテーブルに追加
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${product.name}</td>
      <td>${product.price}円</td>
      <td class="quantity-column">
        <button class="btn btn-outline-success btn-lg quantity-button increment" data-product-id="${id}">＋</button>
        <span>${product.quantity}個</span>
        <button class="btn btn-outline-success btn-lg quantity-button decrement" data-product-id="${id}">－</button>
      </td>
      <td class="delete-column">
        <button class="btn btn-outline-danger" data-product-id="${id}">削除</button>
      </td>
    `;
    cartTableBody.appendChild(row);

    // フォームに入力フィールドを追加
    const inputId = document.createElement("input");
    inputId.type = "hidden";
    inputId.name = "product_id[]";
    inputId.value = id;
    form.appendChild(inputId);

    const inputQuantity = document.createElement("input");
    inputQuantity.type = "hidden";
    inputQuantity.name = "quantity[]";
    inputQuantity.value = product.quantity;
    form.appendChild(inputQuantity);
  }

  totalCountSpan.textContent = totalCount;
  totalPriceSpan.textContent = totalPrice;

  // 数量ボタンと削除ボタンにイベントリスナーを追加
  document.querySelectorAll(".quantity-button.increment").forEach((button) => {
    button.addEventListener("mousedown", function () {
      if (currentIncrementInterval) {
        clearTimeout(incrementTimeout);
        clearInterval(currentIncrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      incrementTimeout = setTimeout(() => {
        currentIncrementInterval = setInterval(() => {
          incrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("mouseup", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("touchstart", function () {
      if (currentIncrementInterval) {
        clearTimeout(incrementTimeout);
        clearInterval(currentIncrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      incrementTimeout = setTimeout(() => {
        currentIncrementInterval = setInterval(() => {
          incrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("touchend", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("touchcancel", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("click", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval); // クリックイベントの直前にインターバルをクリア
      const id = this.getAttribute("data-product-id");
      incrementProductQuantity(id);
    });
  });

  document.querySelectorAll(".quantity-button.decrement").forEach((button) => {
    button.addEventListener("mousedown", function () {
      if (currentDecrementInterval) {
        clearTimeout(decrementTimeout);
        clearInterval(currentDecrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      decrementTimeout = setTimeout(() => {
        currentDecrementInterval = setInterval(() => {
          decrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("mouseup", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("touchstart", function () {
      if (currentDecrementInterval) {
        clearTimeout(decrementTimeout);
        clearInterval(currentDecrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      decrementTimeout = setTimeout(() => {
        currentDecrementInterval = setInterval(() => {
          decrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("touchend", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("touchcancel", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("click", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval); // クリックイベントの直前にインターバルをクリア
      const id = this.getAttribute("data-product-id");
      decrementProductQuantity(id);
    });
  });

  document.querySelectorAll(".delete-column button").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-product-id");
      removeProductFromCart(id);
    });
  });

  // Adjust stock after updating the cart
  updateStock();
  adjustCartForStock();
}

// 在庫の更新処理
function updateStock() {
  const productElements = document.querySelectorAll(".product");
  productElements.forEach((productElement) => {
    const id = productElement.getAttribute("id").split("-")[1];
    const stockElement = productElement.querySelector("p span");
    const originalStock = parseInt(
      stockElement.getAttribute("data-original-stock"),
      10
    );
    let currentStock = originalStock;
    if (cart[id]) {
      currentStock -= cart[id].quantity;
    }
    stockElement.textContent = currentStock;
  });
}

// カート内商品の在庫調整処理
function adjustCartForStock() {
  const productElements = document.querySelectorAll(".product");
  let cartUpdated = false;
  productElements.forEach((productElement) => {
    const id = productElement.getAttribute("id").split("-")[1];
    const stockElement = productElement.querySelector("p span");
    const originalStock = parseInt(
      stockElement.getAttribute("data-original-stock"),
      10
    );
    let currentStock = originalStock;
    if (cart[id]) {
      currentStock -= cart[id].quantity;
    }
    // 在庫不足があればカートを調整
    if (currentStock < 0) {
      console.log(`在庫不足: ${id}, 調整前カート: ${cart[id].quantity}`);
      const adjustment = cart[id].quantity + currentStock;
      if (adjustment > 0) {
        cart[id].quantity = adjustment;
        console.log(`調整後カート: ${cart[id].quantity}`);
      } else {
        delete cart[id];
        console.log(`商品削除: ${id}`);
      }
      cartUpdated = true;
      currentStock = 0; // 在庫をゼロに設定
      // カスタムアラートを表示
      showCustomAlert("在庫不足のため、購入できません。");
    }
  });

  // カートが更新された場合、強制的にカートを更新
  if (cartUpdated) {
    updateCart();
  }
}

// 商品をカートに追加
function addProductToCart(id, name, price) {
  if (id in cart) {
    const productStock = parseInt(
      document
        .querySelector(`#product-${id} p span`)
        .getAttribute("data-original-stock"),
      10
    );
    if (cart[id].quantity + 1 > productStock) {
      showCustomAlert("在庫数が不足しています。");
      return;
    }
    cart[id].quantity++;
  } else {
    cart[id] = {
      name: name,
      price: price,
      quantity: 1,
    };
  }
  updateCart();
}

// 商品をカートから削除
function removeProductFromCart(id) {
  delete cart[id];
  updateCart();
}

// 商品の数量を増やす
function incrementProductQuantity(id) {
  const productStock = parseInt(
    document
      .querySelector(`#product-${id} p span`)
      .getAttribute("data-original-stock"),
    10
  );
  if (cart[id] && cart[id].quantity + 1 > productStock) {
    showCustomAlert("在庫数が不足しています。");
    return;
  }
  if (cart[id]) {
    cart[id].quantity++;
    updateCart();
  }
}

// 商品の数量を減らす
function decrementProductQuantity(id) {
  if (cart[id] && cart[id].quantity > 1) {
    cart[id].quantity--;
    updateCart();
  } else {
    clearInterval(decrementInterval); // 削除前にインターバルをクリア
    delete cart[id];
    updateCart();
  }
}

// イベントハンドラの設定
function setupEventHandlers() {
  const productElements = document.querySelectorAll(".product");
  productElements.forEach((productElement) => {
    productElement.addEventListener("click", function () {
      const id = productElement.getAttribute("id").split("-")[1];
      const name = productElement.querySelector("p").textContent;
      const price = parseInt(
        productElement
          .querySelector("p:nth-of-type(2)")
          .textContent.replace("円", ""),
        10
      );
      const stockElement = productElement.querySelector("p span");
      const originalStock = parseInt(stockElement.textContent, 10);
      if (!stockElement.hasAttribute("data-original-stock")) {
        stockElement.setAttribute("data-original-stock", originalStock);
      }
      addProductToCart(id, name, price);
    });
  });

  document.querySelectorAll(".quantity-button.increment").forEach((button) => {
    button.addEventListener("mousedown", function () {
      if (currentIncrementInterval) {
        clearTimeout(incrementTimeout);
        clearInterval(currentIncrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      incrementTimeout = setTimeout(() => {
        currentIncrementInterval = setInterval(() => {
          incrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("mouseup", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("touchstart", function () {
      if (currentIncrementInterval) {
        clearTimeout(incrementTimeout);
        clearInterval(currentIncrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      incrementTimeout = setTimeout(() => {
        currentIncrementInterval = setInterval(() => {
          incrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("touchend", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("touchcancel", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval);
    });

    button.addEventListener("click", function () {
      clearTimeout(incrementTimeout);
      clearInterval(currentIncrementInterval); // クリックイベントの直前にインターバルをクリア
      const id = this.getAttribute("data-product-id");
      incrementProductQuantity(id);
    });
  });

  document.querySelectorAll(".quantity-button.decrement").forEach((button) => {
    button.addEventListener("mousedown", function () {
      if (currentDecrementInterval) {
        clearTimeout(decrementTimeout);
        clearInterval(currentDecrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      decrementTimeout = setTimeout(() => {
        currentDecrementInterval = setInterval(() => {
          decrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("mouseup", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("touchstart", function () {
      if (currentDecrementInterval) {
        clearTimeout(decrementTimeout);
        clearInterval(currentDecrementInterval);
      }
      const id = this.getAttribute("data-product-id");
      decrementTimeout = setTimeout(() => {
        currentDecrementInterval = setInterval(() => {
          decrementProductQuantity(id);
        }, 100); // 長押しの間隔を100ミリ秒に設定
      }, 500); // 長押しと判定されるまでの間隔を500ミリ秒に設定
    });

    button.addEventListener("touchend", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("touchcancel", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval);
    });

    button.addEventListener("click", function () {
      clearTimeout(decrementTimeout);
      clearInterval(currentDecrementInterval); // クリックイベントの直前にインターバルをクリア
      const id = this.getAttribute("data-product-id");
      decrementProductQuantity(id);
    });
  });

  document.querySelectorAll(".delete-column button").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-product-id");
      removeProductFromCart(id);
    });
  });
}

// 初期化処理の呼び出し
setupEventHandlers();
