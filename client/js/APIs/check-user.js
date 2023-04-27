function setPopUpNotLogin() {
  $("#modal-body")
    .html(`  <div style="display: flex; flex-direction: column; align-items: center;">
                        <a class="btn btn-primary w-75 mb-3" href="signin.html">Đăng nhập</a>
                        <a class="btn btn-secondary w-75" href="signup.html">Đăng ký</a>
                    </div>`);
  $("#cart-button")
    .html(`<button type="button" class="btn-sm-square bg-white rounded-circle ms-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        <small class="fa fa-shopping-bag text-body"></small>
                    </button>`);
}

function setPopUpLogin() {
  $("#modal-body")
    .html(`  <div style="display: flex; flex-direction: column; align-items: center;">
                        <a class="btn btn-primary w-75 mb-3" href="profile.html">Xem profile</a>
                         <a class="btn btn-primary w-75 mb-3" href="order-track.html">Đơn hàng của bạn</a>
                        <button type="button" class="btn btn-secondary w-75" onClick="logout();">Đăng xuất</button>
                        </div>`);
  $("#cart-button").html(
    ` <button class="btn-sm-square bg-white rounded-circle ms-3" onclick="window.location.href = '` +
      `cart.html` +
      `';">
                            <small class="fa fa-shopping-bag text-body"></small>
                        </button>`
  );
}

$(document).ready(function () {
  // setPopUpLogin();
  const userCookie = document.cookie
    .split(";")
    .find((cookie) => cookie.includes("user_id="));

  if (userCookie) {
    const userId = userCookie.split("=")[1];
    setPopUpLogin();
  } else {
    setPopUpNotLogin();
    console.log("Cookie 'user_id' not found.");
  }
});

function logout() {
  $.ajax({
    url: "http://localhost/dallas-organic/server/auth/logout",
    type: "GET",
    success: function (response) {
      window.location.href = "signin.html";
      document.cookie =
        "user_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
}
