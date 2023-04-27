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

$("#contact-btn").click(function (event) {
  event.preventDefault();
  createToast(
    "Chúng tôi đã nhận được thông tin từ bạn, chúng tôi sẽ cố gắng phản hồi bạn trong thời gian sớm nhất!"
  );
});
