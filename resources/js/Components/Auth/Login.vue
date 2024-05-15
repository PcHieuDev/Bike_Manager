<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Login NCC</h2>

        <div class="card my-5">
          <form class="card-body cardbody-color p-lg-5" @submit="validateForm">
            <div class="text-center">
              <img
                src="storage/images/logo.png"
                class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="200px"
                alt="profile"
              />
            </div>

            <div class="mb-3">
              <input
                type="email"
                class="form-control"
                id="email"
                aria-describedby="emailHelp"
                placeholder="Email Address"
              />
              <span id="emailError" class="text-danger d-none" style="color: white"
                >Please enter a valid email address.</span
              >
            </div>
            <div class="mb-3">
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Password"
              />
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-color px-5 mb-5 w-100">Login</button>
            </div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">
              Not Registered?
              <router-link to="/register" class="text-dark fw-bold">
                Create an Account</router-link
              >
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    validateForm(event) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const emailError = document.getElementById("emailError");

      if (!emailPattern.test(this.email)) {
        emailError.classList.remove("d-none");
        event.preventDefault(); // Ngăn chặn gửi form nếu có lỗi
      } else {
        emailError.classList.add("d-none");
      }
    },
  },
};
</script>

<style scoped>
.btn-color {
  background-color: #0e1c36;
  color: #fff;
}

.profile-image-pic {
  height: 200px;
  width: 200px;
  object-fit: cover;
}

.cardbody-color {
  background-color: #00ade8;
}

a {
  text-decoration: none;
}
</style>
