const params = new URLSearchParams(window.location.search);
const id = params.get("id");
var cate_name;
var average_rating;

var user_id;
const userCookie = document.cookie
  .split(";")
  .find((cookie) => cookie.includes("user_id="));

if (userCookie) {
  user_id = userCookie.split("=")[1];
} else {
  user_id = "";
}

function turnCart() {
  const quantityEle = document.querySelector("[name=quantity]");
  $.ajax({
    url: "http://localhost/dallas-organic/server/cart/createItem",
    type: "POST",
    data: JSON.stringify({
      productID: Number(id),
      userID: Number(user_id),
      quantity: Number(quantityEle.value),
    }),
    contentType: "application/json",
    success: function (result) {
      console.log("Cart updated successfully!");
      window.location.href = "cart.html";
    },
    error: function (xhr, status, error) {
      alert("An error occurred while updating the product: " + error);
    },
  });
}

function postReview() {
  var FormData = {
    productID: Number(id),
    customerID: Number(user_id),
    comment: $("#message").val(),
    rating: $("#point").val(),
  };
  $.ajax({
    url: "http://localhost/dallas-organic/server/feedback/createItem",
    type: "POST",
    data: JSON.stringify(FormData),
    contentType: "application/json",
    success: function (result) {
      alert("Đăng tải bình luận thành công!");
      window.location.href = "product-detail.html?id=" + FormData.productID;
    },
    error: function (xhr, status, error) {
      alert("Đăng tải bình luận thất bại!");
    },
  });
}

function decQuantity() {
  const quantityEle = document.querySelector("[name=quantity]");
  if (quantityEle.value > 0) quantityEle.value = Number(quantityEle.value) - 1;
}

function incQuantity() {
  const quantityEle = document.querySelector("[name=quantity]");
  quantityEle.value = Number(quantityEle.value) + 1;
}

$.ajax({
  url: "http://localhost/dallas-organic/server/product/getItem/" + id,
  type: "GET",
  dataType: "json",
  success: function (products) {
    try {
      // Xử lý kết quả trả về từ REST API
      var productHtml = "";
      var productDesc = "";
      var product = products[0];
      $.ajax({
        url:
          "http://localhost/dallas-organic/server/category/getItem/" +
          product.category_id,
        type: "GET",
        async: false,
        dataType: "json",
        success: function (cates) {
          try {
            cate_name = cates[0]["cate_name"];
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
          "http://localhost/dallas-organic/server/feedback/getAvgRating/" + id,
        type: "GET",
        dataType: "json",
        async: false,
        success: function (rating) {
          try {
            average_rating = rating[0]["avg_rating"];
          } catch (e) {
            console.log("Error parsing JSON response:", e);
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });

      var avg_rating = "";

      average_rating = Math.round(average_rating);

      for (let i = 0; i < average_rating; i++) {
        avg_rating += `<i class="fas fa-star"> </i>`;
      }
      for (let i = average_rating; i < 5; i++) {
        avg_rating += `<i class="far fa-star"> </i>`;
      }

      productHtml =
        productHtml +
        `<div class="mx-lg-5 col-lg-5 about-img position-relative overflow-hidden p-3 pe-0 d-flex justify-content-center">
                <img style="border-radius: 24px;" class="img-fluid" src="` +
        product.image +
        `" alt="Image">
            </div>

            <div class="col-lg-5 py-5 d-flex justify-content-center border border-primary">
                <div> 
                    <h2 class="fw-bold mb-4">` +
        product.product_name +
        `</h2>
                    <div class="mb-4 mt-3">
                        <span class="chip origin-chip">` +
        product.origin +
        `</span>
                        <span class="chip cate-chip">` +
        cate_name +
        `</span>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="text-primary mr-2">` +
        avg_rating +
        `</div>
                    </div>

                    <h3 class="font-weight-semi-bold mb-4">` +
        Number(product.price).toLocaleString("en-US") +
        `đ</h3>
                    <p class="mb-4"><i class="fa fa-check text-primary me-3"></i>Đơn vị: ` +
        product.unit +
        `</p>
                    <p class="mb-4"><i class="fa fa-check text-primary me-3"></i>Số lượng đã bán: ` +
        product.sold_number +
        `</p>
                    <p class="mb-4"><i class="fa fa-check text-primary me-3"></i>Số lượng còn lại: ` +
        product.remain_number +
        `</p>

                    <div class="d-flex align-items-center mb-4 pt-2">
                        <div class="input-group quantity mr-3" style="width: 130px;">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-minus" onclick="decQuantity();">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <input name="quantity" type="text" id="quantity-input" class="form-control bg-light text-center" value="1">
                            <div class="input-group-btn">
                                <button class="btn btn-primary btn-plus" onclick="incQuantity();">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div id="sell-button-container">
                        <button class="btn btn-primary" style="margin-left: 20px;" onclick="turnCart();"><i
                                class="fa fa-shopping-cart mr-1"></i> Mua ngay</button>
                        </div>
                    </div>
                    <div class="d-flex pt-2">
                        <p class="text-dark font-weight-medium mb-0 mr-2">Chia sẻ:</p>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>`;

      productDesc = productDesc + product.description;
      // Hiển thị danh sách sản phẩm trên giao diện
      $("#product-detail").html(productHtml);
      $("#product-desc").html(productDesc);
      $.ajax({
        url: "http://localhost/dallas-organic/server/auth/check",
        type: "GET",
        success: function (response) {
          if (response === "not set") {
            $("#modal-body")
              .html(`  <div style="display: flex; flex-direction: column; align-items: center;">
                        <a class="btn btn-primary w-75 mb-3" href="signin.html">Đăng nhập</a>
                        <a class="btn btn-secondary w-75" href="signup.html">Đăng ký</a>
                    </div>`);
            $("#sell-button-container")
              .html(`<button type="button" class="btn btn-primary" style="margin-left: 20px;" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Mua ngay
                    </button>`);
            $("#review-button")
              .html(`<button type="button" class="btn btn-primary w-100 mt-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Gửi lại đánh giá
                    </button>`);
          }
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});

$.ajax({
  url: "http://localhost/dallas-organic/server/feedback/getForDisplay/" + id,
  type: "GET",
  dataType: "json",
  success: function (reviews) {
    try {
      // Xử lý kết quả trả về từ REST API
      var reviewHtml = "";
      var revireNumHtml = reviews.length + ` đánh giá cho sản phẩm này`;
      $("#review-count").html(revireNumHtml);

      $.each(reviews, function (index, review) {
        var rating = "";
        for (let i = 0; i < review.rating; i++) {
          rating += `<i class="fas fa-star"></i>`;
        }
        for (let i = review.rating; i < 5; i++) {
          rating += `<i class="far fa-star"></i>`;
        }
        // Tạo HTML cho từng sản phẩm
        reviewHtml =
          reviewHtml +
          ` <div class="media mb-4">
                                    <div class="row">
                                        <img src="` +
          review.avatar +
          `" alt="Image" class="img-fluid mr-2 col-6"
                                            style="width: 100px;">
                                        <div class="col-6">
                                            <h5 class="mb-2 mt-2">` +
          review.fullname +
          `</h5>
                                            <h6 class="mb-2">@` +
          review.username +
          `</h6>
                                            <small><i>Thời gian đăng tải: ` +
          review.feedback_datetime +
          `</i></small>
                                        </div>
                                    </div>
                                    <div class="media-body mt-3">
                                        <div class="text-primary mb-2">` +
          rating +
          `</div>
                                        <p>` +
          review.comment +
          `</p>
                                    </div>
                                </div>`;
      });

      $("#product-review").html(reviewHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    var revireNumHtml = `<i>0 đánh giá cho sản phẩm này</i>`;
    $("#review-count").html(revireNumHtml);
  },
});
