<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link
      rel="stylesheet"
      href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/bs-brain@2.0.4/components/logins/login-10/assets/css/login-10.css"
    />
    <link rel="stylesheet" href="main.css" />
    <script src="jquery-min.js"></script>
    <script src="main.js"></script>
  </head>
  <!-- Login 10 - Bootstrap Brain Component -->
  <section class="bg-light py-3 py-md-5 py-xl-8">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
          <div class="card border border-light-subtle rounded-4">
            <div class="card-body p-3 p-md-4 p-xl-5">
              <form action="" id="login-form">
                <h3 class="text-center mb-4">Login</h3>
                <p class="error text-center mb-4"></p>
                <div class="row gy-3 overflow-hidden">
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input
                        type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        value=""
                        placeholder="name@example.com"
                        
                      />
                      <label for="email" class="form-label">Email</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input
                        type="password"
                        class="form-control"
                        name="password"
                        id="password"
                        value=""
                        placeholder="Password"
                        
                      />
                      <label for="password" class="form-label">Password</label>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="d-grid">
                      <button
                        class="btn btn-primary btn-lg log-btn"
                        type="submit"
                      >
                        Log in
                      </button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div
            class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-4"
          ></div>
        </div>
      </div>
    </div>
  </section>
</html>
