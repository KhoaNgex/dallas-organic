$.ajax({
  url: "http://localhost/dallas-organic/server/category/getAll",
  type: "GET",
  dataType: "json",
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

$(document).ready(function () {
  // Handle form submission
  $("#product-form").submit(function (event) {
    // Prevent default form submission
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
      sold_number: 0,
    };
    $.ajax({
      url: "http://localhost/dallas-organic/server/product/createItem",
      type: "POST",
      data: JSON.stringify(formData),
      contentType: "application/json",
      success: function (result) {
        alert("Product added successfully!");
      },
      error: function (xhr, status, error) {
        alert("An error occurred while adding the product: " + error);
      },
    });
  });
});
