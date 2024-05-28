<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="text-center text-dark mt-5">Login NCC</h2>
        <div class="card my-5">
          <form class="card-body cardbody-color p-lg-5" @submit.prevent="handleLogin">
            <div class="text-center">
              <img
                src="storage/images/logo.png"
                class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3"
                width="200px"
                alt="profile"
              />
            </div>
            <div v-if="errorMessage" class="alert alert-danger text-center">
              {{ errorMessage }}
            </div>
            <div class="mb-3">
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="email"
                aria-describedby="emailHelp"
                placeholder="Email Address"
                autocomplete="off"
              />
              <div v-if="emailErrorMessage" class="dfsdfsd">
                {{ emailErrorMessage }}
              </div>
              <span id="emailError" class="text-danger d-none" style="color: white">
                Please enter a valid email address.
              </span>
            </div>
            <div class="mb-3">
              <input
                type="password"
                class="form-control"
                id="password"
                v-model="password"
                placeholder="Password"
                autocomplete="off"

              />
              <div v-if="passwordErrorMessage" class="dfsdfsd">
                {{ passwordErrorMessage }}
              </div>
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-color px-5 mb-5 w-100">
                Đăng Nhập
              </button>
            </div>
            <div id="emailHelp" class="form-text text-center mb-5 text-light">
              Chưa có tài khoản?
              <router-link
                to="/Register"
                class="text-light fw-bold"
                style="margin-left: 5px"
              >
                Tạo tài khoản
              </router-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- popup Login success -->
  <LoginPopupSuccess
    v-model="LoginSuccess"
    @close="LoginSuccess = false"
  ></LoginPopupSuccess>
  <!-- popup Login success -->
</template>
<script>
import axios from "axios";
import LoginPopupSuccess from "../../Components/Popup/Login/LoginPopupSuccess.vue";

export default {
  components: {
    LoginPopupSuccess,
  },
  data() {
    return {
      LoginSuccess: false,
      email: "",
      password: "",
      emailErrorMessage: "",
      passwordErrorMessage: "",
      errorMessage: "",
    };
  },
  methods: {
    handleLogin() {
      if (this.email === "") {
        this.emailErrorMessage = "Làm ơn nhập email.";
      } else {
        this.emailErrorMessage = "";
      }

      if (this.password === "") {
        this.passwordErrorMessage = "Làm ơn nhập mật khẩu.";
      } else {
        this.passwordErrorMessage = "";
      }

      if (this.email !== "" && this.password !== "") {
        axios
          .post("/api/Login", {
            email: this.email,
            password: this.password,
          })
          .then((response) => {
            console.log(response.data);
            localStorage.setItem("token", response.data.token);
            localStorage.setItem("user", JSON.stringify(response.data.user));
            this.user = JSON.parse(localStorage.getItem("user"));
            this.LoginSuccess = true;
            setTimeout(() => {
              this.$router.push("/ProductActions/actions");
            }, 1000);
          })
          .catch((error) => {
            if (error.response) {
              if (error.response.status === 401) {
                // Unauthorized
                this.errorMessage = "Email hoặc mật khẩu không đúng.";
              } else {
                this.errorMessage = "Đã xảy ra lỗi. Vui lòng thử lại.";
              }
            } else {
              this.errorMessage = "Đã xảy ra lỗi. Vui lòng thử lại.";
            }
            console.log(error);
          });
      }
    },
  },
};
</script>
<style scoped>
.row {
  padding-top: 50px;
}
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
.dfsdfsd {
  padding: 0;
  margin: 0;
  margin-top: 0;
  margin-bottom: 1rem;
  color: white;
  font-weight: bold;
}
a {
  text-decoration: none;
}
</style>
