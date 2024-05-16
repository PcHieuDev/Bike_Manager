<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Register NCC</h2>

        <div class="card my-5">
          <form class="card-body cardbody-color p-lg-5" @submit.prevent="handleRegister">
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
                type="text"
                class="form-control"
                id="fullname"
                v-model="fullname"
                placeholder="Full Name"
              />
              <div v-if="fullnameErrorMessage" class="dfsdfsd">
                {{ fullnameErrorMessage }}
              </div>
            </div>
            <div class="mb-3">
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="email"
                aria-describedby="emailHelp"
                placeholder="Email Address"
              />
              <div v-if="emailErrorMessage" class="dfsdfsd">
                {{ emailErrorMessage }}
              </div>
            </div>
            <div class="mb-3">
              <input
                type="password"
                class="form-control"
                id="password"
                v-model="password"
                placeholder="Password"
              />
              <div v-if="passwordErrorMessage" class="dfsdfsd">
                {{ "*" + passwordErrorMessage }}
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-color px-5 mb-5 w-100">
                Register
              </button>
            </div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">
              Already Registered?
              <router-link to="/login" class="text-dark fw-bold"> Log In</router-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      fullname: "",
      email: "",
      password: "",
      fullnameErrorMessage: "",
      emailErrorMessage: "",
      passwordErrorMessage: "",
    };
  },
  methods: {
    handleRegister() {
      // Add your registration logic here
      // For example, you can validate the input fields
      if (!this.fullname.trim()) {
        this.fullnameErrorMessage = "Please enter your full name.";
        return;
      }

      // You can add more validation for email and password here

      // If all fields are valid, you can proceed with registration
      // For example, you can send a request to your backend API to register the user
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
