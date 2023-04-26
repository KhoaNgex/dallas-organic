const params = new URLSearchParams(window.location.search);
const id = params.get("id");

$.ajax({
    url: "http://localhost/dallas-organic/server/user/getItem/" + id,
    type: "GET",
    dataType: "json",
    success: function (users) {
        try {
        // Xử lý kết quả trả về từ REST API
            var user = users[0];
            document.querySelector("[name=user-name]").value = user.username;
            document.querySelector("[name=user-fullname]").value = user.fullname;
            document.querySelector("[name=user-sex]").value = user.sex;
            document.querySelector("[name=user-dob]").value = user.DoB;
            document.querySelector("[name=user-phonenumber]").value = user.phonenumber;
            document.querySelector("[name=user-email]").value = user.email;
            document.querySelector("[name=user-address]").value = user.address;
            document.querySelector("[name=user-avatar]").value = user.avatar;
        } catch (e) {
            console.log("Error parsing JSON response:", e);
        }
    },
    error: function (xhr, status, error) {
      console.log("Error:", error);
    },
});

$(function () {
    $("#edit-user-form").submit(function (event) {
        event.preventDefault();
        // Get form data
        var formData = {
            username: document.querySelector("[name=user-name]").value,
            fullname: document.querySelector("[name=user-fullname]").value,
            sex: document.querySelector("[name=user-sex]").value,
            DoB: document.querySelector("[name=user-dob]").value,
            phonenumber: document.querySelector("[name=user-phonenumber]").value,
            email: document.querySelector("[name=user-email]").value,
            address: document.querySelector("[name=user-address]").value,
            avatar: document.querySelector("[name=user-avatar]").value,
        };
      // Send the data to the server using AJAX
        $.ajax({
            url: "http://localhost/dallas-organic/server/user/editItem?id=" + id,
            type: "PUT",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function (result) {
            alert("Account updated successfully!");
            },
            error: function (xhr, status, error) {
            alert("An error occurred while updating the account: " + error);
            },
        });
    });
});