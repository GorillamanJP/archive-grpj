// カートの初期化
let cart = {};

// グローバルに interval 変数を宣言
let incrementInterval;
let decrementInterval;

// 非同期更新で使うやつ
function run_custom_function() {
  adjustCartForStock();
  updateStock();
  setupEventHandlers();
}

const custom_check_update_path = "/common/index/check_update.php";
const custom_updated_data_path = "/common/index/updated_data.php";
// 非同期更新で使うやつ　ここまで

// カスタムアラートの関数
function showCustomAlert(message) {
  const alertBox = document.createElement("div");
  alertBox.classList.add("custom-alert");
  alertBox.innerText = message;
  document.body.appendChild(alertBox);
  alertBox.style.display = "block";

  setTimeout(() => {
    alertBox.style.display = "none";
    alertBox.remove();
  }, 5000); // 5秒後に自動的に消える
}

// 作成しなおし　カート系処理
function updateCart() {
  const cartTableBody = document.querySelector("#cart-table tbody");
  const totalCountSpan = document.querySelector("#total-count");
  const totalPriceSpan = document.querySelector("#total-price");
  const form = document.querySelector("#form");

  // Clear the cart table and form inputs
  cartTableBody.innerHTML = "";
  form.innerHTML = "";

  let totalCount = 0;
  let totalPrice = 0;

  for (const id in cart) {
    const product = cart[id];
    totalCount += product.quantity;
    totalPrice += product.price * product.quantity;

    // Add to cart table
    const row = document.createElement("tr");
    row.innerHTML = `
              <td>${product.name}</td>
              <td>${product.price}円</td>
              <td class="quantity-column">
                  <button class="btn btn-outline-success btn-lg quantity-button increment" data-product-id="${id}">＋</button>
                  <span>${product.quantity}個</span>
                  <button class="btn btn-outline-success btn-lg quantity-button decrement" data-product-id="${id}">－</button>
              </td>
              <td class="delete-column"><button class="btn btn-outline-danger" data-product-id="${id}">削除</button></td>
          `;
    cartTableBody.appendChild(row);

    // Add to form inputs
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

  // Add event listeners for quantity buttons and remove buttons
  document.querySelectorAll(".quantity-button.increment").forEach((button) => {
    button.addEventListener("mousedown", function () {
      const id = this.getAttribute("data-product-id");
      incrementInterval = setInterval(() => {
        incrementProductQuantity(id);
      }, 200);
    });

    button.addEventListener("mouseup", function () {
      clearInterval(incrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearInterval(incrementInterval);
    });

    button.addEventListener("click", function () {
      const id = this.getAttribute("data-product-id");
      incrementProductQuantity(id);
    });
  });

  document.querySelectorAll(".quantity-button.decrement").forEach((button) => {
    button.addEventListener("mousedown", function () {
      const id = this.getAttribute("data-product-id");
      decrementInterval = setInterval(() => {
        decrementProductQuantity(id);
      }, 200);
    });

    button.addEventListener("mouseup", function () {
      clearInterval(decrementInterval);
    });

    button.addEventListener("mouseout", function () {
      clearInterval(decrementInterval);
    });

    button.addEventListener("click", function () {
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

  // Recalculate stock
  adjustCartForStock();
  updateStock();
}

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

    // Check for stock issues and adjust cart if necessary
    if (currentStock < 0) {
      console.log(`在庫不足: ${id}, 調整前カート: ${cart[id].quantity}`);
      const adjustment = cart[id].quantity + currentStock; // Calculate possible adjustment
      if (adjustment > 0) {
        cart[id].quantity = adjustment;
        console.log(`調整後カート: ${cart[id].quantity}`);
      } else {
        delete cart[id]; // Remove from cart if quantity is zero or less
        console.log(`商品削除: ${id}`);
      }
      cartUpdated = true;
      currentStock = 0; // Set stock to zero

      // Show custom alert
      showCustomAlert("在庫不足のため、カートが更新されました。");
    }
  });

  // If cart was updated, force update the cart
  if (cartUpdated) {
    updateCart();
  }
}

function addProductToCart(id, name, price) {
  if (id in cart) {
    const productStock = parseInt(
      document
        .querySelector(`#product-${id} p span`)
        .getAttribute("data-original-stock"),
      10
    );
    if (cart[id].quantity + 1 > productStock) {
      showCustomAlert("在庫数を超えています。");
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

function removeProductFromCart(id) {
  delete cart[id];
  updateCart();
}

function incrementProductQuantity(id) {
  const productStock = parseInt(
    document
      .querySelector(`#product-${id} p span`)
      .getAttribute("data-original-stock"),
    10
  );
  if (cart[id] && cart[id].quantity + 1 > productStock) {
    showCustomAlert("在庫数を超えています。");
    return;
  }
  if (cart[id]) {
    cart[id].quantity++;
    updateCart();
  }
}

function decrementProductQuantity(id) {
  if (cart[id] && cart[id].quantity > 1) {
    cart[id].quantity--;
    updateCart();
  } else {
    removeProductFromCart(id);
  }
}

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
}
