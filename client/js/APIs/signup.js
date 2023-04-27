const passwordInput = document.querySelector("#password");
const togglePassword = document.querySelector("#togglePassword");
togglePassword.addEventListener("click", function () {
  const type =
    passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);
  togglePassword.querySelector("i").classList.toggle("fa-eye");
  togglePassword.querySelector("i").classList.toggle("fa-eye-slash");
  document.getElementById("password").focus();
});

const createToast = (msg, errsta = false) => {
  if (errsta) {
    document.getElementById("toast-container").style =
      "background-color: rgba(250, 123, 123, 0.778);";
    document.getElementById("toast-msg").style = "color: white;";
  } else {
    document.getElementById("toast-container").style = "";
    document.getElementById("toast-msg").style = "";
  }
  document.getElementById("toast-msg").innerHTML = msg;
  let bsAlert = new bootstrap.Toast(document.querySelector(".toast"));
  bsAlert.show();
};

// Validate form data
function validateFormData(formData) {
  // Regular expression patterns for validation
  var usernamePattern = /^[a-zA-Z0-9_-]{3,16}$/;
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var passwordPattern =
    /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
  var phonePattern = /^[0-9]{10,}$/;

  // Validate username
  if (!usernamePattern.test(formData.username)) {
    createToast(
      "Vui lòng nhập tên tài khoản từ 3-16 ký tự, chỉ bao gồm chữ cái thường, chữ in hoa, số và dấu gạch dưới."
    , true
      );
    return false;
  }

  // Validate email
  if (formData.email && !emailPattern.test(formData.email)) {
    createToast("Vui lòng nhập email hợp lệ.", true);
    return false;
  }

  // Validate password
  if (!passwordPattern.test(formData.password)) {
    createToast(
      "Vui lòng nhập mật khẩu có ít nhất 8 ký tự và bao gồm ít nhất một chữ cái và một chữ số.",
      true
    );
    return false;
  }

  // Validate phone number
  if (!phonePattern.test(formData.phonenumber)) {
    createToast(
      "Số điện thoại chỉ gồm ký tự số và có ít nhất 10 chữ số.",
      true
    );
    return false;
  }

  return true;
}

$(function () {
  $("#datepicker").datepicker();
});

$(document).ready(function () {
  // Handle form submission
  $("#signup-form").submit(function (event) {
    // Prevent default form submission
    event.preventDefault();

    // Get form data
    var formData = {
      username: $("#username").val(),
      email: $("#email").val(),
      password: $("#password").val(),
      phonenumber: $("#phone").val(),
      DoB: $("#date").val(),
      address: $("#address").val(),
      fullname: $("#fullname").val(),
      sex: document.querySelector("[name=gender]").value,
    };

    // Validate form data
    if (!validateFormData(formData)) {
      return;
    }

    formData.DoB = formData.DoB.replace(/(\d\d)\/(\d\d)\/(\d{4})/, "$3-$1-$2");

    console.log(formData);

    // Send data to server using AJAX
    $.ajax({
      url: "http://localhost/dallas-organic/server/auth/register",
      type: "POST",
      headers: {
        "Access-Control-Allow-Origin": "*",
        "Content-Type": "application/json",
      },
      data: JSON.stringify(formData),
      success: function (result) {
        createToast(
          "Đăng ký tài khoản thành công. Bạn có thể trở về trang đăng nhập!"
        );
      },
      error: function (xhr, status, error) {
        createToast(
          "Tên tài khoản đã tồn tại hoặc thông tin đăng ký không hợp lệ!",
          true
        );
      },
    });
  });
});
