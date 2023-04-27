var product_per_page = 6;
var price_filter = "-1";
var cate_filter = "-1";
var product_name = "";
var sort_field = "id";
var sort_order = "asc";
var product_count;

function setListAttribute() {
  sort_field = $("#sort-select").val();
  price_filter = $("input[name=price]:checked").val();
  cate_filter = $("input[name=cate]:checked").val();
  product_name = $("input[name=productname]").val();
  sort_order = $("#sort-select-order").val();
}

function turnCart() {
  const quantityEle = document.querySelector("[name=quantity]");
  $.ajax({
    url: "http://localhost/dallas-organic/server/cart/createItem",
    type: "POST",
    data: JSON.stringify({
      productID: Number(id),
      userID: Number(localStorage.getItem("user_id")),
      quantity: 1,
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

function filterProduct() {
  setListAttribute();
  $("#pagination").twbsPagination("destroy");
  initPagination();
  $.ajax({
    url:
      "http://localhost/dallas-organic/server/product/getAllTitle?offset=" +
      0 +
      "&so='" +
      sort_order +
      "'&sf='" +
      sort_field +
      "'&price=" +
      price_filter +
      "&cate=" +
      cate_filter +
      "&name='" +
      product_name +
      "'",
    method: "GET",
    success: function (products) {
      productRender(products);
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
}

$(document).ready(function () {
  $.ajax({
    url:
      "http://localhost/dallas-organic/server/product/getAllTitle?offset=" +
      0 +
      "&so='" +
      sort_order +
      "'&sf='" +
      sort_field +
      "'&price=" +
      price_filter +
      "&cate=" +
      cate_filter +
      "&name='" +
      product_name +
      "'",
    method: "GET",
    success: function (products) {
      productRender(products);
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
});

function productRender(products) {
  try {
    // Xử lý kết quả trả về từ REST API
    var productListHtml = "";
    $.each(products, function (index, product) {
      // Tạo HTML cho từng sản phẩm
      var product_chip =
        product.sold_number >= 100
          ? `<div
                                            class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">
                                            Hot</div>`
          : ``;
      productListHtml =
        productListHtml +
        `<div class="col-lg-4 col-md-6 wow fadeInUp product-item-container" data-wow-delay="0.1s">
                                <div class="product-item">
                                    <div class="position-relative bg-light overflow-hidden" style="min-height: 265px;">
                                        <img class="img-fluid w-100" src="` +
        product.image +
        `" alt="">
                                        ` +
        product_chip +
        `
                                    </div>
                                    <div class="text-center p-4">
                                        <a class="d-block h5 mb-2" style="height: 65px;" href="product-detail.html?id=` +
        product.id +
        `">` +
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
                                            <div class="text-body"><i
                                                    class="fa fa-shopping-bag text-primary me-2"></i>Giao 24H</div>
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
}

function initPagination() {
  window.pagObj = $("#pagination")
    .twbsPagination({
      totalPages: product_count / product_per_page + 1,
      visiblePages: 5,
      onPageClick: function (event, page) {},
    })
    .on("page", function (event, page) {
      let offset = page - 1;
      $.ajax({
        url:
          "http://localhost/dallas-organic/server/product/getAllTitle?offset=" +
          offset +
          "&so='" +
          sort_order +
          "'&sf='" +
          sort_field +
          "'&price=" +
          price_filter +
          "&cate=" +
          cate_filter +
          "&name='" +
          product_name +
          "'",
        type: "GET",
        dataType: "json",
        success: function (products) {
          productRender(products);
        },
        error: function (xhr, status, error) {
          console.log("Error:", error);
        },
      });
    });
}

$.ajax({
  url: "http://localhost/dallas-organic/server/category/getAll",
  type: "GET",
  dataType: "json",
  success: function (cates) {
    try {
      // Xử lý kết quả trả về từ REST API
      var cateListHtml = `<div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="cate" id="all-cate" checked
                                    value="-1">
                                <label class="form-check-label" for="all-cate">
                                    Tất cả sản phẩm
                                </label>
                            </div>`;
      $.each(cates, function (index, cate) {
        cateListHtml =
          cateListHtml +
          `<div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="cate" id="cate-` +
          cate.id +
          `" 
                                    value="` +
          cate.id +
          `">
                                <label class="form-check-label" for="cate-` +
          cate.id +
          `">
                                    ` +
          cate.cate_name +
          `
                                </label>
                            </div>`;
      });
      $("#cate-form").html(cateListHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});

$.ajax({
  url: "http://localhost/dallas-organic/server/product/getCount",
  type: "GET",
  dataType: "json",
  success: function (count) {
    try {
      // Xử lý kết quả trả về từ REST API
      product_count = count[0]["count_products()"];
      $(function () {
        initPagination();
      });
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
