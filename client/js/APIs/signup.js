const createToast = (msg) => {
  document.getElementById("toast-msg").innerHTML = msg;
  let bsAlert = new bootstrap.Toast(document.querySelector(".toast"));
  bsAlert.show();
};

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
      phone: $("#phone").val(),
      date: $("#date").val(),
      address: $("#address").val(),
      fullname: $("#fullname").val(),
      sex: document.querySelector("[name=gender]").value,
    };

    // Validate form data
    if (!validateFormData(formData)) {
      return;
    }

    // Hash password using bcrypt
    // var hashedPassword = bcrypt.hashSync(formData.password, 10);

    // // Add hashed password to form data
    // formData.password = hashedPassword;

    // // Send data to server using AJAX
    // $.ajax({
    //   url: "register.php",
    //   type: "POST",
    //   data: formData,
    //   success: function (response) {
    //     // Handle success response from server
    //     console.log(response);
    //   },
    //   error: function (xhr, status, error) {
    //     // Handle error response from server
    //     console.log(error);
    //   },
    // });
  });

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
      );
      return false;
    }

    // Validate email
    if (formData.email && !emailPattern.test(formData.email)) {
      createToast("Vui lòng nhập email hợp lệ.");
      return false;
    }

    // Validate password
    if (!passwordPattern.test(formData.password)) {
      createToast(
        "Vui lòng nhập mật khẩu có ít nhất 8 ký tự và bao gồm ít nhất một chữ cái và một chữ số."
      );
      return false;
    }

    // Validate phone number
    if (formData.phone && !phonePattern.test(formData.phone)) {
      createToast("Số điện thoại chỉ gồm ký tự số và có ít nhất 10 chữ số.");
      return false;
    }

    return true;
  }
});
