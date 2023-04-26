$(document).ready(function () {
    // Handle form submission
    $("#add-user-form").submit(function (event) {
        // Prevent default form submission
        event.preventDefault();
        // Get form data
        var formData = {
            username: document.querySelector("[name=user-name]").value,
            password: document.querySelector("[name=user-pw]").value,
            fullname: document.querySelector("[name=user-fullname]").value,
            sex: document.querySelector("[name=user-sex]").value,
            DoB: document.querySelector("[name=user-dob]").value,
            phonenumber: document.querySelector("[name=user-phonenumber]").value,
            email: document.querySelector("[name=user-email]").value,
            address: document.querySelector("[name=user-address]").value,
            avatar: document.querySelector("[name=user-avatar]").value,
        };
        $.ajax({
            url: "http://localhost/dallas-organic/server/user/createItem",
            type: "POST",
            data: JSON.stringify(formData),
            contentType: "application/json",
            success: function (result) {
                alert("Account added successfully!");
            },
            error: function (xhr, status, error) {
                alert("An error occurred while adding the account: " + error);
            },
        });
    });
});