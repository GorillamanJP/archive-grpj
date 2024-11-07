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
      alert("在庫が足りません。");
    }
  } else {
    if (stockQuantity > 0) {
      const row = document.createElement("tr");
      row.innerHTML = `<td>${productName}</td><td>${price}円</td><td class="quantity-column"><button class="btn btn-outline-success quantity-button" onclick="changeQuantity(this, -1, ${productId}, ${stockQuantity})">－</button><span>1個</span><button class="btn btn-outline-success quantity-button" onclick="changeQuantity(this, 1, ${productId}, ${stockQuantity})">＋</button></td><td class="delete-column"><button class="btn btn-outline-danger" onclick="removeFromCart(this, ${price}, ${productId})" class="btn btn-danger">削除</button></td>`;
      cartTable.appendChild(row);
      updateTotals(price, 1);
      updateStockDisplay(productId, -1);
      addHiddenInputs(productId, 1);
    } else {
      alert("在庫が足りません。");
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
  updateTotals(-price * quantity, -quantity);
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

function changeQuantity(button, change, productId, stockQuantity) {
  const quantityCell = button.parentNode.children[1];
  let currentQuantity = parseInt(quantityCell.innerText);
  const price = parseInt(button.parentNode.parentNode.cells[1].innerText);
  const newQuantity = currentQuantity + change;
  if (newQuantity < 1) {
    return; // 最小個数を1に制限
  } else if (newQuantity > stockQuantity) {
    alert("在庫が足りません。");
    return;
  }
  quantityCell.innerText = `${newQuantity}個`;
  updateTotals(change * price, change);
  updateStockDisplay(productId, -change); // 在庫数を更新
  updateForm(productId, newQuantity); // フォームを更新
}
