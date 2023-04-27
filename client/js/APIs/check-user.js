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
  if (localStorage.getItem("user_id") === null) {
    $.ajax({
      url: "http://localhost/dallas-organic/server/auth/check",
      type: "GET",
      success: function (response) {
        if (response === "not set") {
          setPopUpNotLogin();
        } else {
          localStorage.setItem("user_id", response);
          setPopUpLogin();
        }
      },
      error: function (xhr, status, error) {
        console.log("Error:", error);
      },
    });
  } else {
    setPopUpLogin();
  }
});

function logout() {
  $.ajax({
    url: "http://localhost/dallas-organic/server/auth/logout",
    type: "GET",
    success: function (response) {
      window.location.href = "signin.html";
      localStorage.removeItem("user_id");
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
  });
}
