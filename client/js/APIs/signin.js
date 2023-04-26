const passwordInput = document.querySelector("#floatingPassword");
const togglePassword = document.querySelector("#togglePassword");
togglePassword.addEventListener("click", function () {
  const type =
    passwordInput.getAttribute("type") === "password" ? "text" : "password";
  passwordInput.setAttribute("type", type);
  togglePassword.querySelector("i").classList.toggle("fa-eye");
  togglePassword.querySelector("i").classList.toggle("fa-eye-slash");
  document.getElementById("floatingPassword").focus();
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
  var passwordPattern =
    /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;

  // Validate username
  if (!usernamePattern.test(formData.username)) {
    createToast(
      "Vui lòng nhập tên tài khoản từ 3-16 ký tự, chỉ bao gồm chữ cái thường, chữ in hoa, số và dấu gạch dưới.",
      true
    );
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

  return true;
}

$(document).ready(function () {
  // Handle form submission
  $("#form-signin").submit(function (event) {
    // Prevent default form submission
    event.preventDefault();

    // Get form data
    var formData = {
      username: $("#floatingInput").val(),
      password: $("#floatingPassword").val(),
    };

    // Validate form data
    if (!validateFormData(formData)) {
      return;
    }

    // Send data to server using AJAX
    $.ajax({
      url: "http://localhost/dallas-organic/server/auth/login",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(formData),
      success: function (result) {
        createToast("Đăng nhập tài khoản thành công!");
        setTimeout(function () {
          window.location.href = "index.html";
        }, 1000);
      },
      error: function (xhr, status, error) {
        createToast("Sai tên đăng nhập hoặc mật khẩu!", true);
      },
    });
  });
});
