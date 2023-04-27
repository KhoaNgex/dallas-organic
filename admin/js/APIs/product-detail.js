const params = new URLSearchParams(window.location.search);
const id = params.get("id");
var cate_name;

$.ajax({
  url: "http://localhost/dallas-organic/server/product/getItem/" + id,
  type: "GET",
  dataType: "json",
  success: function (products) {
    try {
      // Xử lý kết quả trả về từ REST API
      var productHtml = "";
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

      productHtml =
        productHtml +
        `<div class="big-img">
                    <img src="` +
        product.image +
        `" alt="">
                    <div class="btn-container" style="padding-top:20px;">
                        <a href="product_edit.php?id=` +
        product.id +
        `"><button
                                style="background-color: orange">Chỉnh sửa</button></a>
                        <button style="background-color: red" onClick="displayDeleteModal()">Xóa</button>
                        <button style="background-color: grey" onClick="turnBack()">Quay lại</button>
                    </div>
                </div>
                <div class="detail-info">
                    <h1>
                        ` +
        product.product_name +
        `
                    </h1>
                    <h3>Giá:
                        ` +
        Number(product.price).toLocaleString("en-US") +
        ` đồng/` +
        product.unit +
        `
                    </h3>
                    <p>Phân loại: ` +
        cate_name +
        `
                    </p>
                    <p>Đã bán: ` +
        product.sold_number +
        `
                    </p>
                    <p>Kho: ` +
        product.remain_number +
        `
                    </p>
                    <p>Xuất xứ: ` +
        product.origin +
        `
                    </p>
                </div>
                <div class="detail-desc">
                    <p>Mô tả:</p>
                    <p>
                        ` +
        product.description +
        `
                    </p>
                </div>`;
      $("#product-detail").html(productHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});
