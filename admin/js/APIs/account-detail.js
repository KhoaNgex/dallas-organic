const params = new URLSearchParams(window.location.search);
const id = params.get("id");

$.ajax({
  url: "http://localhost/dallas-organic/server/user/getItem/" + id,
  type: "GET",
  dataType: "json",
  success: function (users) {
    try {
        // Xử lý kết quả trả về từ REST API
        var userHtml = "";
        var user = users[0];

        userHtml = userHtml +
            `<div class="avatar-img">
                <img src="` + user.avatar + `" alt="">
                <div class="btn-container" style="padding-top:20px;">
                    <a href="account_edit.php?id=` + user.id +`"><button style="background-color: orange">Chỉnh sửa</button></a>
                    <button style="background-color: red" onClick="displayDeleteModal()">Xóa</button>
                    <a href="account.php"><button style="background-color: grey">Quay lại</button></a>
                </div>
            </div>
            <div class="detail-account-info">
                <h1>`+ user.fullname + `</h1>
                <h3>`+ user.username + `</h3>
                <p>Ngày sinh: ` + getFormattedDate(new Date(user.DoB)) + `</p>
                <p>Giới tính: ` + user.sex + `</p>
                <p>Số điện thoại: ` + user.phonenumber + `</p>
                <p>Email: ` + user.email + `</p>
                <p>Địa chỉ: ` + user.address + `</p>
            </div>`

        $("#account-detail").html(userHtml);
    } catch (e) {
      console.log("Error parsing JSON response:", e);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error:", error);
  },
});


function getFormattedDate(date) {
    var year = date.getFullYear();
  
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;
  
    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    
    return month + '/' + day + '/' + year;
  }