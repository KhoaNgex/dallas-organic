function displayDeleteModal() {
    document.querySelector(".delete-modal").classList.add("open");
}

function removeDeleteModal() {
    document.querySelector(".delete-modal.open").classList.remove("open");
}

function turnBack() {
    history.back();
}

function addProductHandler() {
    preventDefault();
}