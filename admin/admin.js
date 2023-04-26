function turnBack() {
  history.back();
}

function addProductHandler() {
  preventDefault();
}

function displayDeleteModal() {
  document.querySelector(".delete-modal").classList.add("open");
}

function removeDeleteModal() {
  document.querySelector(".delete-modal.open").classList.remove("open");
}

function directToProductCate() {
  const cate = document.getElementById("cate");
  if (cate.value != "") {
    window.location = "product_cate.php?cate_id=" + cate.value;
  }
  else {
    window.location = "product.php";
  }
}