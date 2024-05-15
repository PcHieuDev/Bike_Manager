<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Register NCC</h2>

        <div class="card my-5">
          <form class="card-body cardbody-color p-lg-5" @submit.prevent="register">
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
                v-model="user.name"
                class="form-control"
                id="username"
                aria-describedby="usernameHelp"
                placeholder="User Name"
              />
            </div>
            <div class="mb-3">
              <input
                type="email"
                v-model="user.email"
                class="form-control"
                id="email"
                aria-describedby="emailHelp"
                placeholder="Email Address"
              />
            </div>
            <div class="mb-3">
              <input
                type="password"
                v-model="user.password"
                class="form-control"
                id="password"
                placeholder="Password"
              />
            </div>
            <div class="mb-3">
              <input
                type="password"
                v-model="user.password_confirmation"
                class="form-control"
                id="confirmPassword"
                placeholder="Confirm Password"
              />
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-color px-5 mb-3 w-100">
                Register
              </button>
              <p class="text-dark mb-0">
                Already have an account?
                <routerLink to="/login" class="text-dark fw-bold">Login</routerLink>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      user: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
      },
      errors: {},
    };
  },
  methods: {
    register() {
      axios
        .post("/api/register", this.user)
        .then((response) => {
          this.$router.push("/login");
        })
        .catch((error) => {
          this.errors = error.response.data;
        });
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
