var total = 0;

var user_id;
const userCookie = document.cookie
  .split(";")
  .find((cookie) => cookie.includes("user_id="));

if (userCookie) {
  user_id = userCookie.split("=")[1];
} else {
  user_id = "";
}

var returnBtn = document.getElementById("return-btn");
var confirmBtn = document.getElementById("confirm-btn");

returnBtn.addEventListener("click", function () {
  window.location.href = "cart.html";
});

const section = document.querySelector("section"),
  overlay = document.querySelector(".overlay"),
  showBtn = document.querySelector(".show-modal"),
  closeBtn = document.querySelector(".close-btn");

overlay.addEventListener("click", () => section.classList.remove("active"));

closeBtn.addEventListener("click", () => {
  section.classList.remove("active");
  setTimeout(function () {
    window.location.href = "product.html";
  }, 2000);
});

confirmBtn.addEventListener("click", function () {
  var formData = {
    recieve_address: $("#address").val(),
    recieve_phonenum: $("#phonenum").val(),
    note: $("#note").val(),
    ship_fee: Math.round(total * 0.1),
    userID_ordcus: user_id,
  };
  $.ajax({
    url: "http://localhost/dallas-organic/server/order/createItem",
    type: "POST",
    data: JSON.stringify(formData),
    contentType: "application/json",
    success: function (result) {
      section.classList.add("active");
    },
    error: function (xhr, status, error) {
      alert(
        "Đơn hàng tạo thất bại. Vui lòng kiểm tra lại số lượng sản phẩm trong giỏ hàng!"
      );
    },
  });
});

$.ajax({
  url: "http://localhost/dallas-organic/server/auth/info",
  type: "GET",
  success: function (info) {
    try {
      // Xử lý kết quả trả về từ REST API
      var customerHtml = "";
      customerHtml =
        customerHtml +
        ` <div>
                        <label for="name">Tên người nhận:</label>
                        <input id="name" name="name" type="text" value="` +
        info.name +
        `">
                    </div>
                    <div>
                        <label for="phonenum">Số điện thoại:</label>
                        <input id="phonenum" type="text" value="` +
        info.phone +
        `">
                    </div>
                    <div>
                        <label for="address">Địa chỉ:</label>
                        <input id="address" type="text" value="` +
        info.address +
        `">
                    </div>
                     <div>
                      <label for="note">Ghi chú:</label>
                      <textarea class="form-control mt-1" placeholder="Để lại ghi chú..." id="note"
                                        style="height: 100px"></textarea>            
                       </div>`;
      $("#customer-info").html(customerHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});

$.ajax({
  url:
    "http://localhost/dallas-organic/server/cart/getAll/" +
    user_id,
  type: "GET",
  dataType: "json",
  success: function (products) {
    try {
      // Xử lý kết quả trả về từ REST API
      var productListHtml = ``;
      $.each(products, function (index, product) {
        total += product.price * product.quantity;
        // Tạo HTML cho từng sản phẩm
        productListHtml =
          productListHtml +
          `<div class="order-item">
                        <div class="order-product-info">
                            <p style="font-weight: bold;">` +
          product.quantity +
          `x</p>
                            <h5>` +
          product.product_name +
          `</h5>
                            <p>` +
          Number(product.price * product.quantity).toLocaleString("en-US") +
          `vnđ</p>
                        </div>
                        <div class="order-product-img">
                            <div style="margin-top: 10px">
                                <p style="margin-bottom: 10px">Đơn vị: ` +
          product.unit +
          `</p>
                                <p style="margin-bottom: 10px">Giá: ` +
          Number(product.price).toLocaleString("en-US") +
          `/` +
          product.unit +
          `</p>
                            </div>
                            <img src="` +
          product.image +
          `" alt="">
                        </div>
                    </div>`;
      });
      // Hiển thị danh sách sản phẩm trên giao diện
      $("#order-info").html(productListHtml);

      $("#price-overall").html(
        `<div class="price-overall-item">
                        <p class="price-overall-label">Tiền hàng:</p>
                        <p class="price-overall-value">` +
          Number(total).toLocaleString("en-US") +
          ` vnđ</p>
                    </div>
                    <div class="price-overall-item">
                        <p class="price-overall-label">Phí giao hàng:</p>
                        <p class="price-overall-value">` +
          Number(total * 0.1).toLocaleString("en-US") +
          ` vnđ</p>
                    </div>
                    <hr>
                    <div class="price-overall-item">
                        <p style="font-weight: bold" class="price-overall-label">Tổng đơn hàng:</p>
                        <p style="font-weight: bold" class="price-overall-value">` +
          Number(total + total * 0.1).toLocaleString("en-US") +
          ` vnđ</p>
                    </div>`
      );
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
