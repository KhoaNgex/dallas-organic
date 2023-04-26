const createToast = (msg, errsta = false) => {
  if (errsta) {
    document.getElementById("toast-container").style =
      "background-color: rgba(250, 123, 123, 0.778);";
    document.getElementById("toast-msg").style = "color: greenyellow;";
  } else {
    document.getElementById("toast-container").style =
      "background-color: greenyellow;";
    document.getElementById("toast-msg").style = "";
  }
  document.getElementById("toast-msg").innerHTML = msg;
  let bsAlert = new bootstrap.Toast(document.querySelector(".toast"));
  bsAlert.show();
};

$(document).ready(function () {
  $.ajax({
    url:
      "http://localhost/dallas-organic/server/user/getItem/" +
      localStorage.getItem("user_id"),
    type: "GET",
    dataType: "json",
    success: function (infos) {
      try {
        var info = infos[0];
        info.DoB = info.DoB.split("-").reverse().join("/");
        // Xử lý kết quả trả về từ REST API
        var infoHtml = "";
        infoHtml =
          infoHtml +
          ` <img src="` +
          info.avatar +
          `"
                            class="rounded-circle" style="width: 200px; margin-top: 30px; margin-bottom: 20px;"
                            alt="Avatar" />
                        <h2 class="text-white fw-bold mb-2" id="name-left">` +
          info.fullname +
          `</h2>
                        <h5 class="mb-4"><i>@` +
          info.username +
          `</i></h5>
                        <div class="mb-5">
                            <h5 class="text-light mb-2">Giới tính: ` +
          info.sex +
          `</h5>
                            <h5 class="text-light">Ngày sinh: ` +
          info.DoB +
          `</h5>
                        </div>`;
        // Hiển thị danh sách sản phẩm trên giao diện
        $("#profile-info").html(infoHtml);
        var editHtml = "";
        editHtml =
          editHtml +
          `<div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" value="` +
          info.fullname +
          `" required>
                                    <label for="name">Họ và tên</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" value="` +
          info.email +
          `" required>
                                    <label for="email">Email của tôi</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="username" value="` +
          info.username +
          `" disabled>
                                    <label for="username">Tên tài khoản</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="phone" value="` +
          info.phonenumber +
          `">
                                    <label for="phone">Số điện thoại</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="address" value="` +
          info.address +
          `">
                                    <label for="address">Địa chỉ (mặc định)</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button id="edit-btn" class="btn btn-primary rounded-pill py-2 px-3" style="margin-right: 10px;">Cập
                                    nhật profile</button>

                                <a class="btn btn-secondary rounded-pill py-2 px-3" data-bs-toggle="modal"
                                    data-bs-target="#passmodal">Đổi mật khẩu</a>
                            </div>`;
        $("#edit-form").html(editHtml);
        $("#edit-btn").click(function (e) {
          e.preventDefault();
          var formData = {
            fullname: $("#name").val(),
            email: $("#email").val(),
            phonenumber: $("#phone").val(),
            address: $("address").val(),
          };
          $.ajax({
            url:
              "http://localhost/dallas-organic/server/user/editItem?id=" +
              localStorage.getItem("user_id"),
            type: "PUT",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function (result) {
              document.getElementById("name-left").innerHTML = $("#name").val();
              createToast("Cập nhật thông tin thành công!");
            },
            error: function (xhr, status, error) {
              createToast("Cập nhật thông tin thất bại: " + error, true);
            },
          });
        });
      } catch (e) {
        console.log("Error parsing JSON response:", e);
      }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
});

$("#upd-pass-btn").click(function (e) {
  e.preventDefault();
  var passwordPattern =
    /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
  var pass = $("#password").val();
  var pass2 = $("#password2").val();
  if (!pass || !pass2 || pass != pass2) {
    createToast("Mật khẩu mới không khớp!", true);
  } else if (!passwordPattern.test(pass)) {
    createToast(
      "Vui lòng nhập mật khẩu có ít nhất 8 ký tự và bao gồm ít nhất một chữ cái và một chữ số.",
      true
    );
  } else {
    $.ajax({
      url:
        "http://localhost/dallas-organic/server/user/editItem?id=" +
        localStorage.getItem("user_id"),
      type: "PUT",
      data: JSON.stringify({ password: pass }),
      contentType: "application/json",
      success: function (result) {
        createToast("Thay đổi mật khẩu thành công!");
      },
      error: function (xhr, status, error) {
        createToast("Thay đổi mật khẩu thất bại: " + error, true);
      },
    });
  }
});
