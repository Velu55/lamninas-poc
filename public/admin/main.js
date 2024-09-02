$(document).ready(function () {
  var currentUrl = window.location.href;
  var filename = currentUrl.split("/").pop();
  $(document).click(function () {
    getItemWithExpiry("token");
  });
  if (localStorage.getItem("ses_msg")) {
    if (localStorage.getItem("ses_msg") == 1) {
      $(".error").text("Session Expired Please Login..!");
    } else {
      $(".error").text("Logged Out successfully");
      $(".error").css("color", "green");
    }
    localStorage.removeItem("ses_msg");
  }
  $("#login-form").submit(function (e) {
    e.preventDefault();
    var email = $("#email").val().trim();
    var password = $("#password").val().trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      $(".error").text("Please enter a valid email address.");
      return false;
    }
    if (password == "") {
      $(".error").text("Please enter a password.");
      return false;
    }
    $.ajax({
      url: "/login",
      method: "POST",
      datatype: "json",
      data: {
        email: email,
        password: password,
      },
      success: function (result) {
        setItemWithExpiry("token", result.token, 60 * 60 * 1000);
        localStorage.setItem("logged", 1);
        window.location.href = "/admin/listing.php";
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log(xhr);
        $(".error").text(xhr.responseJSON.message);
        $(".error").focus();
      },
    });
  });

  if (filename == "listing.php") {
    if (localStorage.getItem("logged")) {
      $("#pop_msg").text("Success! You has been logged in successfully.");
      $("#successPopup").fadeIn();
    }
    loaddata();
  }
  $("#name,#email,#edit-email,#edit-name").keypress(function () {
    $(this).css("border", "1px solid #ccc");
  });
  $("#logout").click(function () {
    localStorage.removeItem("token");
    localStorage.setItem("ses_msg", 2);
    window.location.href = "/admin/";
  });
  $("#add-user").submit(function (e) {
    e.preventDefault();
    var name = $("#name").val().trim();
    var email = $("#email").val().trim();
    var role = $("#role").val().trim();
    var pass = "test@123";
    if (name == "") {
      $("#name").css("border", "1px solid red");
      return false;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      $("#email").css("border", "1px solid red");
      return false;
    }
    $.ajax({
      url: "/users/create",
      method: "POST",
      datatype: "json",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      data: {
        name: name,
        email: email,
        password: pass,
        role: role,
      },
      success: function (result) {
        console.log(result);
        $("#model-close").trigger("click");
        $("#name").val("");
        $("#email").val("");
        $("#pop_msg").text("Success! User has been created successfully.");
        $("#successPopup").fadeIn();
        loaddata();
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log(xhr);
        $(".error").text(xhr.responseJSON.message);
      },
    });
  });
  $("#closePopup").on("click", function () {
    $("#successPopup").fadeOut();
  });
  setTimeout(function () {
    if ($("#successPopup").is(":visible")) {
      $("#successPopup").fadeOut();
    }
  }, 2000);
  $("#edit-user").submit(function (e) {
    e.preventDefault();
    var id = $("#edit_user_id").val().trim();
    var name = $("#edit-name").val().trim();
    var email = $("#edit-email").val().trim();
    var role = $("#edit-role").val().trim();
    if (name == "") {
      $("#edit-name").css("border", "1px solid red");
      return false;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      $("#edit-email").css("border", "1px solid red");
      return false;
    }
    $.ajax({
      url: "/users/update",
      method: "POST",
      datatype: "json",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      data: {
        id: id,
        name: name,
        email: email,
        role: role,
      },
      success: function (result) {
        console.log(result);
        $("#edit-model-close").trigger("click");
        $("#pop_msg").text("Success! User has been updated successfully.");
        $("#successPopup").fadeIn();
        loaddata();
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log(xhr);
        $(".error").text(xhr.responseJSON.message);
      },
    });
  });
  $(document).on("click", ".edit ", function (event) {
    var id = $(this).closest("tr").find("#userid").val();
    $("#edit_user_id").val(id);

    $.ajax({
      url: "/users/get/" + id,
      method: "GET",
      datatype: "json",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (result) {
        $("#edit-name").val(result.data.name);
        $("#edit-email").val(result.data.email);
        $("#edit-role").val(result.data.role);
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log(xhr);
        $(".error").text(xhr.responseJSON.message);
      },
    });
  });
  $("#delete-user").submit(function (e) {
    e.preventDefault();
    var id = $("#edit_user_id").val();
    $.ajax({
      url: "/users/delete/" + id,
      method: "DELETE",
      datatype: "json",
      headers: {
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      success: function (result) {
        $("#delete-model-close").trigger("click");
        $("#pop_msg").text("Success! User has been deleted successfully.");
        $("#successPopup").fadeIn();
        loaddata();
      },
      error: function (xhr, status, error) {
        console.log(error);
        console.log(xhr);
        $(".error").text(xhr.responseJSON.message);
      },
    });
  });
  $(document).on("click", ".delete ", function (event) {
    var id = $(this).closest("tr").find("#userid").val();
    $("#edit_user_id").val(id);
  });
});

function setItemWithExpiry(key, value, expiryInMillis) {
  const now = new Date();
  const item = {
    value: value,
    expiry: now.getTime() + expiryInMillis,
  };
  localStorage.setItem(key, JSON.stringify(item));
}

// Function to get data and check expiration
function getItemWithExpiry(key) {
  const itemStr = localStorage.getItem(key);
  if (!itemStr) {
    return null;
  }
  const item = JSON.parse(itemStr);
  const now = new Date();
  if (now.getTime() > item.expiry) {
    localStorage.removeItem(key);
    localStorage.setItem("ses_msg", 1);
    window.location.href = "/admin/";
  }
  return item.value;
}

function loaddata() {
  var token = getItemWithExpiry("token");
  if (!token) {
    window.location.href = "/admin/";
  }
  $.ajax({
    url: "/users/list",
    method: "GET",
    headers: {
      Authorization: "Bearer " + token,
    },
    success: function (result) {
      var userdata = "";
      result.data.forEach((element) => {
        userdata +=
          "<tr><td>" +
          element.name +
          "</td><td>" +
          element.email +
          "</td><td>" +
          element.role +
          "</td><td><a href='#editUserModal'  class='edit' data-toggle='modal'><i  class='material-icons' data-toggle='tooltip' title='Edit'>&#xE254;</i></a><a href='#deleteUserModal' class='delete' data-toggle='modal'><i class='material-icons'data-toggle='tooltip'title='Delete'>&#xE872;</i></a></td><input type='hidden' id='userid' value=" +
          element.id +
          "></tr>";
      });
      $("#table-body").html(userdata);
      localStorage.removeItem("logged");
    },
    error: function (xhr, status, error) {
      console.log(error);
      $(".error").text(xhr.responseJSON.message);
    },
  });
}
