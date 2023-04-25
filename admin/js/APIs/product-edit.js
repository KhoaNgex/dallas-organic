const params = new URLSearchParams(window.location.search);
const id = params.get("id");

$.ajax({
  url: "http://localhost/dallas-organic/server/category/getAll",
  type: "GET",
  dataType: "json",
  async: false,
  success: function (cates) {
    try {
      // Xử lý kết quả trả về từ REST API
      var cateHtml = ` <option value="" style="display: none">Phân loại</option>`;
      $.each(cates, function (index, cate) {
        // Tạo HTML cho từng sản phẩm
        cateHtml =
          cateHtml +
          `<option value=` +
          cate.id +
          `>` +
          cate.cate_name +
          `</option>`;
      });
      $("#product-cate").html(cateHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});

$.ajax({
  url: "http://localhost/dallas-organic/server/product/getItem/" + id,
  type: "GET",
  dataType: "json",
  success: function (products) {
    try {
      // Xử lý kết quả trả về từ REST API
      var product = products[0];
      document.querySelector("[name=product-name]").value =
        product.product_name;
      document.querySelector("[name=product-price]").value = product.price;
      document.querySelector("[name=product-unit]").value = product.unit;
      document.querySelector("[name=product-description]").value =
        product.description;
      document.querySelector("[name=product-origin]").value = product.origin;
      document.querySelector("[name=product-cate]").value = product.category_id;
      document.querySelector("[name=product-image]").value = product.image;
      document.querySelector("[name=product-remain]").value =
        product.remain_number;
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});

$(function () {
  $("#edit-product-form").submit(function (event) {
    event.preventDefault();
    // Get form data
    var formData = {
      product_name: document.querySelector("[name=product-name]").value,
      price: document.querySelector("[name=product-price]").value,
      unit: document.querySelector("[name=product-unit]").value,
      description: document.querySelector("[name=product-description]").value,
      origin: document.querySelector("[name=product-origin]").value,
      category_id: document.querySelector("[name=product-cate]").value,
      image: document.querySelector("[name=product-image]").value,
      remain_number: document.querySelector("[name=product-remain]").value,
    };
    // Send the data to the server using AJAX
    $.ajax({
      url: "http://localhost/dallas-organic/server/product/editItem?id=" + id,
      type: "PUT",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: function (result) {
        alert("Product updated successfully!");
      },
      error: function (xhr, status, error) {
        alert("An error occurred while updating the product: " + error);
      },
    });
  });
});
