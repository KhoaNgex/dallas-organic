const params = new URLSearchParams(window.location.search);
const id = params.get("id");
var fullname = "";
var user_id;
const userCookie = document.cookie
  .split(";")
  .find((cookie) => cookie.includes("user_id="));

if (userCookie) {
  user_id = userCookie.split("=")[1];
} else {
  user_id = "";
}
$(document).ready(function () {
  $.ajax({
    url:
      "http://localhost/dallas-organic/server/user/getItem/" +
      user_id,
    type: "GET",
    dataType: "json",
    success: function (infos) {
      try {
        var info = infos[0];
        fullname = info.fullname;
      } catch (e) {
        console.log("Error parsing JSON response:", e);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });

  $.ajax({
    url: "http://localhost/dallas-organic/server/order/getItem/" + id,
    type: "GET",
    dataType: "json",
    success: function (order) {
      try {
        var order_info = order[0];
        var products = order.slice(1);
        var productListHtml = ``;
        $.each(products, function (index, product) {
          productListHtml =
            productListHtml +
            `<div class="order-item">
                    <div class="order-item-info">
                        <img src="` +
            product.image +
            `"
                            alt="">
                        <div class="order-item-info-text">
                            <h6 class="fw-bold">` +
            product.product_name +
            `</h6>
                            <div>
                                <span class="text-secondary">` +
            product.quantity +
            ` </span>
                                <span>` +
            product.unit +
            ` </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-primary">` +
            Number(product.price).toLocaleString("en-US") +
            `vnđ</p>
                    </div>
                </div>`;
        });
        $("#product-list").html(productListHtml);
        var orderInfoHtml = ``;
        orderInfoHtml =
          orderInfoHtml +
          `<div>
                    <div>
                        <span><b>Họ tên khách hàng: </b></span>
                        <span>` +
          fullname +
          `</span>
                    </div>
                    <div>
                        <span><b>SĐT liên hệ: </b></span>
                        <span>` +
          order_info.recieve_phonenum +
          `</span>
                    </div>
                    <div>
                        <span><b>Địa chỉ nhận: </b></span>
                        <span>` +
          order_info.recieve_address +
          `</span>
                    </div>
                </div>
                <hr class="dashed">
                <div class="order-sum">
                    <div>
                        <p><b>Mã đơn hàng : </b></p>
                        <p><i>#` +
          order_info.id +
          `</i></p>
                    </div>
                    <div>
                        <p><b>Ngày đặt : </b></p>
                        <p>` +
          order_info.order_date +
          `</p>
                    </div>
                    <div>
                        <p><b>Trạng thái : </b></p>
                        <p class="fw-bold text-secondary">` +
          order_info.order_status +
          `</p>
                    </div>
                </div>`;
        $("#order-shipping").html(orderInfoHtml);

        var orderPriceHtml = ``;
        orderPriceHtml =
          orderPriceHtml +
          `<div>
                        <span><b>Tổng cộng: </b></span>
                        <span>` +
          Number(order_info.total_price).toLocaleString("en-US") +
          `</span>
                    </div>
                    <div>
                        <span><b>Phí vận chuyển: </b></span>
                        <span>` +
          Number(order_info.ship_fee).toLocaleString("en-US") +
          `</span>
                    </div>
                    <div>
                        <span><b>Thành tiền: </b></span>
                        <span class="text-primary"><b>` +
          Number(order_info.ship_fee + order_info.total_price).toLocaleString(
            "en-US"
          ) +
          `</b></span>
                    </div>`;
        $("#order-price").html(orderPriceHtml);
      } catch (e) {
        console.log("Error parsing JSON response:", e);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
});
