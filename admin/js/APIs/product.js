var product_per_page = 10;

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
          "http://localhost/dallas-organic/server/product/getAdmin?offset=" +
          offset,
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

$(document).ready(function () {
  $.ajax({
    url: "http://localhost/dallas-organic/server/product/getAdmin?offset=0",
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
    var productListHtml = `<tr>
                    <th>ID</th>
                    <th style="width: 35%">Tên sản phẩm</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Đơn vị</th>
                    <th>Đã bán</th>
                    <th>Kho</th>
                    <th>Thao tác</th>
                </tr>`;
    $.each(products, function (index, product) {
      // Tạo HTML cho từng sản phẩm
      productListHtml =
        productListHtml +
        `<tr id=` +
        product.id +
        `>
                    <td>
                        ` +
        product.id +
        `
                    </td>
                    <td style="font-weight: bold">
                       ` +
        product.product_name +
        `
                    </td> 
                    <td style="width: 20%;">
                        ` +
        product.cate_name +
        `
                    </td>
                    <td>
                        ` +
        Number(product.price).toLocaleString("en-US") +
        `
                    </td>
                    <td style="width: 8%;">
                        ` +
        product.unit +
        `
                    </td>
                    <td>
                        ` +
        product.sold_number +
        `
                    </td>
                    <td>
                        ` +
        product.remain_number +
        `
                    </td>
                    <td style="width: 30%;">
                        <a href="product_detail.php?id=` +
        product.id +
        `"><button
                                style="background-color: green">Chi tiết</button></a>
                        <a href="product_edit.php?id=` +
        product.id +
        `"><button
                                style="background-color: orange">Chỉnh sửa</button></a>
                        <button value=` +
        product.id +
        ` style="background-color: red" onClick="displayDeleteModal();" class="btn-delete">Xóa</button>
                    </td>
                    </tr>`;
    });
    // Hiển thị danh sách sản phẩm trên giao diện
    $("#product-list").html(productListHtml);
    const button = document.querySelectorAll(".btn-delete");

    button.forEach((element) => {
      element.addEventListener("click", function () {
        // Your event handler code here
        localStorage.setItem("product_id", this.value);
      });
    });
  } catch (e) {
    console.log("Error parsing JSON response:", e);
  }
}

function removeDeleteModal() {
  document.querySelector(".delete-modal.open").classList.remove("open");
}

function deleteModal() {
  let id = localStorage.getItem("product_id");
  removeDeleteModal();
  $.ajax({
    url: "http://localhost/dallas-organic/server/product/removeItem?id=" + id,
    type: "DELETE",
    success: function (result) {
      alert("Product deleted successfully!");
      var element = document.getElementById(id);
      var parent = element.parentNode;
      parent.removeChild(element);
    },
    error: function (xhr, status, error) {
      alert("An error occurred while deleting the product: " + error);
    },
  });
}
