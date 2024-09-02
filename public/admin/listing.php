<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mangae User</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="main.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="main.js"></script>
  </head>

  <body>
    <div class="container">
      <div class="table-wrapper">
        <div class="table-title">
          <div class="row">
            <div class="col-sm-6">
              <h2>Manage <b>User</b></h2>
            </div>
            <div class="col-sm-6">
               <button id="logout" class="btn btn-success" style="font-size:15px;background:red;">Logout<i class="fa fa-sign-out"></i></button>
              <a
                href="#addUserModal"
                class="btn btn-success"
                data-toggle="modal"
                ><i class="material-icons">&#xE147;</i>
                <span>Add New User</span></a
              >
            </div>
          </div>
        </div>
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="table-body"></tbody>
        </table>
      </div>
    </div>
        <!-- Message !-->

      <div id="successPopup" class="popup">
        <div class="popup-content">
            <span class="close-btn" id="closePopup">&times;</span>
            <h4 id="pop_msg">User has been created successfully.</h4>
        </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="addUserModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="add-user" method="post" action="">
            <div class="modal-header">
              <h4 class="modal-title">Add User</h4>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                &times;
              </button>
            </div>
            <div class="modal-body">
              <p class="error text-center mb-4"></p>
              <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" id="name" />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input
                  type="email"
                  name="email"
                  id="email"
                  class="form-control"
                  
                />
              </div>

              <div class="form-group">
                <label>Role</label>
                <select name="role" id="role" class="form-control">
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <input
                id="model-close"
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                value="Cancel"
              />
              <input type="submit" class="btn btn-success" value="Add" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Edit Modal HTML -->
    <div id="editUserModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="edit-user" method="post" action="">
            <div class="modal-header">
              <h4 class="modal-title">Edit User</h4>
              <button
                type="button"
                class="close"
                id = "edit-model-close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                &times;
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Name</label>
                <input
                  type="text"
                  id="edit-name"
                  class="form-control"
                  
                />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input
                  type="email"
                  id="edit-email"
                  class="form-control"
                  readonly
                />
              </div>
              <div class="form-group">
                <label>Role</label>
                <select name="role" id="edit-role" class="form-control">
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
                </select>
              </div>
              <input type="hidden" id="edit_user_id" value="" />
            </div>
            <div class="modal-footer">
              <input
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                value="Cancel"
              />
              <input type="submit" class="btn btn-info" value="Save" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Delete Modal HTML -->
    <div id="deleteUserModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="delete-user" method="post" >
            <div class="modal-header">
              <h4 class="modal-title">Delete User</h4>
              <button
                type="button"
                class="close"
                id="delete-model-close"
                data-dismiss="modal"
                aria-hidden="true"
              >
                &times;
              </button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete these Records?</p>
              <p class="text-warning">
                <small>This action cannot be undone.</small>
              </p>
            </div>
            <div class="modal-footer">
              <input
                type="button"
                class="btn btn-default"
                data-dismiss="modal"
                value="Cancel"
              />
              <input type="submit" class="btn btn-danger" value="Delete" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
