var user_id;
const userCookie = document.cookie
  .split(";")
  .find((cookie) => cookie.includes("user_id="));

if (userCookie) {
  user_id = userCookie.split("=")[1];
} else {
  user_id = "";
}

$.ajax({
  url: "http://localhost/dallas-organic/server/order/filterItem?id=" + user_id,
  type: "GET",
  dataType: "json",
  success: function (orders) {
    try {
      // Xử lý kết quả trả về từ REST API
      var orderListHtml = `<div class="section-header text-center mx-auto wow fadeIn" data-wow-delay="0.1s"
        style="max-width: 500px; margin-bottom: 0px">
        <h1 class="display-5 mb-3">Danh sách đơn hàng</h1>
        <p>Theo dõi danh sách đơn hàng của bạn!</p>
    </div>`;
      $.each(orders, function (index, order) {
        orderListHtml =
          orderListHtml +
          `<div class="order-card" data-wow-delay="0.1s">
                <div class="order-header">
                    <div>
                        <span><b>Mã đơn hàng: </b></span>
                        <span><i>#` +
          order.id +
          `</i></span>
                    </div>
                    <span class="fw-bold text-"><i>` +
          order.order_status +
          `</i></span>
                </div>
                <hr class="dashed">
                <div class="row">
                    <div class="col-md-6 order-info">
                        <div>
                            <span><b>Địa chỉ nhận: </b></span>
                            <span>` +
          order.recieve_address +
          `</span>
                        </div>
                        <div>
                            <span><b>SĐT liên hệ: </b></span>
                            <span>` +
          order.recieve_phonenum +
          `</span>
                        </div>
                        <div>
                            <span><b>Ngày nhận: </b></span>
                            <span>` +
          order.order_date +
          `</span>
                        </div>
                        <a class="btn btn-primary mt-3" style="width: 50%;" href="order-detail.html?id=` +
          order.id +
          `">Xem chi tiết</a>
                    </div>
                    <div class="col-md-6 price-container">
                        <div>
                            <span><b>Tiền hàng: </b></span>
                            <span class="text-dark">` +
          Number(order.total_price).toLocaleString("en-US") +
          `vnđ</span>
                        </div>
                        <div>
                            <span><b>Phí ship: </b></span>
                            <span class="text-secondary">` +
          Number(order.ship_fee).toLocaleString("en-US") +
          `vnđ</span>
                        </div>
                        <div>
                            <span><b>Tổng tiền: </b></span>
                            <span class="text-primary">` +
          Number(order.ship_fee + order.total_price).toLocaleString("en-US") +
          `vnđ</span>
                        </div>
                    </div>
                </div>
            </div>`;
      });
      $("#order-list").html(orderListHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
