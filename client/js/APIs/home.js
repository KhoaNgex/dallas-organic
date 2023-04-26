$(document).ready(function () {
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
                                            <a class="text-body" href="product.html"><i
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
