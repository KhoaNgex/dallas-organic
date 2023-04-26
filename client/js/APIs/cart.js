var total = 0;

$.ajax({
  url:
    "http://localhost/dallas-organic/server/cart/getAll/" +
    localStorage.getItem("user_id"),
  type: "GET",
  dataType: "json",
  success: function (products) {
    try {
      // Xử lý kết quả trả về từ REST API
      var productListHtml = `<div class="cart-product-item-header" id="cart-list">
                <div class="product-info">
                    <p>Sản phẩm</p>
                </div>
                <div class="product-price">
                    <p>Giá</p>
                </div>
                <div class="product-unit">
                    <p>Đơn vị</p>
                </div>
                <div class="product-amount">
                    <p>Số lượng</p>
                </div>
                <div class="product-total">
                    <p>Tổng tiền</p>
                </div>
                <div class="product-delete"></div>
            </div>`;
      $.each(products, function (index, product) {
        total += product.price * product.quantity;
        // Tạo HTML cho từng sản phẩm
        productListHtml =
          productListHtml +
          `    <div class="cart-product-item" id=` +
          product.id +
          `>
        <div class="product-info">
            <img src="` +
          product.image +
          `"
                alt="">
            <h5>` +
          product.product_name +
          `</h5>
        </div>
        <div data-value=` +
          product.price +
          ` class="product-price" id="price-` +
          product.id +
          `">
            <p>` +
          Number(product.price).toLocaleString("en-US") +
          `</p>
        </div>
        <div class="product-unit">
            <p>` +
          product.unit +
          `</p>
        </div>
        <div class="product-amount" style="width:125px; margin-top:32px;">
                <input name="` +
          product.id +
          `" type="number" min="0" value="` +
          product.quantity +
          `">
        </div>
        <div data-value=` +
          product.price * product.quantity +
          ` class="product-total" id="total-` +
          product.id +
          `">
            <p>` +
          Number(product.price * product.quantity).toLocaleString("en-US") +
          `</p>
        </div>
        <div class="product-delete">
            <p><button class="fas fa-times" style="font-size: 25px; color: red; border: 0;" onclick="deleteCartItem(` +
          product.id +
          `);"></button></p>
        </div>
    </div>`;
      });
      productListHtml =
        productListHtml +
        ` <div class="order-info">
                <div class="order-info-container">
                    <div class="order-info-item">
                        <p class="order-info-label">Tổng tiền đơn hàng:</p>
                        <p class="order-info-value" id="total-cart">` +
        Number(total).toLocaleString("en-US") +
        ` vnđ</p>
                    </div>
                </div>
                <div class="btn-container">
                    <button class="cart-btn" style="background-color: #e7671d;" onclick="deleteCard();">Xóa hết</button>
                    <button class="cart-btn" style="background-color: #80b500;" onclick="proceedOrder();">Xác nhận</button>
                </div>
            </div>`;
      // Hiển thị danh sách sản phẩm trên giao diện
      $("#cart-list").html(productListHtml);
      $("input[type='number']").inputSpinner();
      const inputs = document.querySelectorAll("input");
      inputs.forEach((input) => {
        input.addEventListener("input", function (event) {
          let total_id = "total-" + event.target.name;

          let price = document
            .getElementById("price-" + event.target.name)
            .getAttribute("data-value");

          let prev_total_price = document
            .getElementById(total_id)
            .getAttribute("data-value");

          let new_total_price = price * event.target.value;

          $("#" + total_id).html(
            `<p>
                  ` +
              Number(new_total_price).toLocaleString("en-US") +
              `
                </p>`
          );

          total = total - prev_total_price + new_total_price;
          $("#total-cart").html(Number(total).toLocaleString("en-US") + ` vnđ`);

          document
            .getElementById(total_id)
            .setAttribute("data-value", new_total_price);

          // backend
          $.ajax({
            url: "http://localhost/dallas-organic/server/cart/editItem",
            type: "PUT",
            data: JSON.stringify({
              productID: event.target.name,
              userID: localStorage.getItem("user_id"),
              quantity: event.target.value,
            }),
            contentType: "application/json",
            success: function (result) {
              console.log("Cart updated successfully!");
            },
            error: function (xhr, status, error) {
              alert("An error occurred while updating the product: " + error);
            },
          });
        });
      });
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    $("#cart-list").html(`Không tìm thấy sản phẩm nào trong giỏ hàng!`);
  },
});

function deleteCartItem(p_id) {
  $.ajax({
    url:
      "http://localhost/dallas-organic/server/cart/removeItem?userID=" +
      localStorage.getItem("user_id") +
      "&productID=" +
      p_id,
    type: "DELETE",
    success: function (result) {
      console.log("Product deleted successfully!");
      let prev_total_price = document
        .getElementById("total-" + p_id)
        .getAttribute("data-value");
      total = total - prev_total_price;
      $("#total-cart").html(Number(total).toLocaleString("en-US") + ` vnđ`);
      var element = document.getElementById(p_id);
      var parent = element.parentNode;
      parent.removeChild(element);
    },
    error: function (xhr, status, error) {
      alert("An error occurred while deleting the product: " + error);
    },
  });
}
