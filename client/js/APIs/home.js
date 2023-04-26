function logout() {
  $.ajax({
    url: "http://localhost/dallas-organic/server/auth/logout",
    type: "GET",
    success: function (response) {
      window.location.href = "signin.html";
      localStorage.removeItem("user_id");
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
}

$(document).ready(function () {
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
          $("#cart-button")
            .html(`<button type="button" class="btn-sm-square bg-white rounded-circle ms-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <small class="fa fa-shopping-bag text-body"></small>
                    </button>`);
      } else {
        localStorage.setItem("user_id", response);
        $("#modal-body")
          .html(`  <div style="display: flex; flex-direction: column; align-items: center;">
                        <a class="btn btn-primary w-75 mb-3" href="profile.html">Xem profile</a>
                        <button type="button" class="btn btn-secondary w-75" onClick="logout();">Đăng xuất</button>
                    </div>`);
        $("#cart-button").html(
          ` <button class="btn-sm-square bg-white rounded-circle ms-3" onclick="window.location.href = '` +
            `cart.html` +
            `';">
                            <small class="fa fa-shopping-bag text-body"></small>
                        </button>`
        );
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
  $.ajax({
    url: "http://localhost/dallas-organic/server/product/getBest",
    type: "GET",
    dataType: "json",
    success: function (products) {
      try {
        // Xử lý kết quả trả về từ REST API
        var productListHtml = "";
        $.each(products, function (index, product) {
          // Tạo HTML cho từng sản phẩm
          productListHtml =
            productListHtml +
            `<div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="product-item">
                                    <div class="position-relative bg-light overflow-hidden">
                                        <img class="img-fluid w-100" src="` +
            product.image +
            `" alt="">
                                        <div
                                            class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                            Best Seller</div>
                                    </div>
                                    <div class="text-center p-4">
                                        <a class="d-block h5 mb-2" style="height: 60px;" href="">` +
            product.product_name +
            `</a>
                                        <span class="text-primary me-1">` +
            Number(product.price).toLocaleString("en-US") +
            `đ</span>
                                        <span class="text-body text-decoration-line-through">` +
            Number(product.price + 10000).toLocaleString("en-US") +
            `đ</span>
                                    </div>
                                    <div class="d-flex border-top">
                                        <small class="w-50 text-center border-end py-2">
                                            <a class="text-body" href="product-detail.html?id=` +
            product.id +
            `"><i class="fa fa-eye text-primary me-2"></i>Xem
                                                thêm</a>
                                        </small>
                                        <small class="w-50 text-center py-2">
                                            <a class="text-body" href=""><i
                                                    class="fa fa-shopping-bag text-primary me-2"></i>Mua ngay</a>
                                        </small>
                                    </div>
                                </div>
                            </div>`;
        });
        // Hiển thị danh sách sản phẩm trên giao diện
        $("#product-list").html(productListHtml);
      } catch (e) {
        console.log("Error parsing JSON response:", e);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });

  $.ajax({
    url: "http://localhost/dallas-organic/server/blog/getTitle",
    type: "GET",
    dataType: "json",
    success: function (blogs) {
      try {
        // Xử lý kết quả trả về từ REST API
        var blogListHtml = "";
        $.each(blogs, function (index, blog) {
          blogListHtml =
            blogListHtml +
            `<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <img class="img-fluid" src="` +
            blog.image +
            `" alt="">
                    <div class="bg-light p-4">
                        <a class="d-block h5 lh-base mb-4" style="height: 60px;" href="blog-detail.html?id=` +
            blog.id +
            `">` +
            blog.title +
            `</a>
                        <div class="text-muted border-top pt-4">
                            <small class="me-3"><i class="fa fa-user text-primary me-2"></i>` +
            blog.created_by +
            `</small>
                            <small class="me-3"><i class="fa fa-calendar text-primary me-2"></i>` +
            blog.created_at +
            `</small>
                        </div>
                    </div>
                </div>`;
        });
        // Hiển thị danh sách sản phẩm trên giao diện
        $("#blog-list").html(blogListHtml);
      } catch (e) {
        console.log("Error parsing JSON response:", e);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
});
