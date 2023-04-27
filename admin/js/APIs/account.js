$.ajax({
    url:"http://localhost/dallas-organic/server/user/getAll",
    type: "GET",
    dataType:"json",
    success: function (users) {
        try {
            // Xử lý kết quả trả về từ REST API
            var userListHtml = `<tr>
                                    <th>ID</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Tên người dùng</th>
                                    <th>SĐT</th>
                                    <th>Email</th>
                                    <th>Thao tác</th>
                                </tr>`;
            $.each(users, function (index, user) {
                // Tạo HTML cho từng người dùng
                userListHtml = userListHtml +
                `<tr id=` + user.id +  `>
                    <td>` + user.id + `</td>
                    <td  style="font-weight: bold">` + user.username + `</td>
                    <td>` + user.fullname + `</td>
                    <td>` + user.phonenumber + `</td>
                    <td>` + user.email + `</td>
                    <td>
                        <a href="account_detail.php?id=`+ user.id +`"><button style="background-color: blue">Chi tiết</button></a>
                        <a href="account_edit.php?id=`+ user.id +`"><button style="background-color: orange">Chỉnh sửa</button></a>
                        <button value=` + user.id +` style="background-color: red" onClick="displayDeleteModal();" class="btn-delete">Xóa</button>
                    </td>
                </tr>`
            });
            // Hiển thị danh sách sản phẩm trên giao diện
            $("#user-list").html(userListHtml);
            const button = document.querySelectorAll(".btn-delete");

            button.forEach((element) => {
                element.addEventListener("click", function () {
                    // Your event handler code here
                    localStorage.setItem("user_id", this.value);
                });
            });
        }
        catch(e) {
            console.log("Error parsing JSON response:", e)
        }
    }
})

function removeDeleteModal() {
    document.querySelector(".delete-modal.open").classList.remove("open");
}

function deleteModal() {
    let id = localStorage.getItem("user_id");
    removeDeleteModal();
    $.ajax({
      url: "http://localhost/dallas-organic/server/user/removeItem?id=" + id,
      type: "DELETE",
      success: function (result) {
        alert("Account deleted successfully!");
        var element = document.getElementById(id);
        var parent = element.parentNode;
        parent.removeChild(element);
      },
      error: function (xhr, status, error) {
        alert("An error occurred while deleting the account: " + error);
      },
    });
  }