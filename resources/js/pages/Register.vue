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
                v-model="user.name"
                placeholder="Full Name"
              />
            </div>
            <div class="mb-3">
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="user.email"
                aria-describedby="emailHelp"
                placeholder="Email Address"
                autocomplete="off"
              />
            </div>
            <div class="mb-3">
              <input
                type="password"
                class="form-control"
                id="password"
                v-model="user.password"
                placeholder="Password"
                autocomplete="off"
              />
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-color px-5 mb-5 w-100">Đăng Ký</button>
              <!-- <p class="canh-bao" v-if="error">{{ error }}</p> -->
            </div>
            <div id="emailHelp" class="form-text text-center mb-5 text-dark">
              Đã có tài khoản?
              <router-link to="/login" class="text-dark fw-bold"> Đăng Nhập</router-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- popup RegisterSucess -->
  <RegisterPopupSuccess
    v-model="RegisterSucess"
    @close="RegisterSucess = false"
  ></RegisterPopupSuccess>
  <!-- popup RegisterSucess -->

  <!-- popup ErrorRegister -->
  <ErrorRegister v-model="RegisterFail" @close="RegisterFail = false"></ErrorRegister>
</template>

<script>
import RegisterPopupSuccess from "../Components/Popup/Register/RegisterPopupSuccess.vue";
import apiClient from "../axios-interceptor.js";
import ErrorRegister from "../Components/Popup/Register/ErrorRegister.vue";
export default {
  data() {
    return {
      RegisterSucess: false,
      RegisterFail: false,
      user: {
        name: "",
        email: "",
        password: "",
      },
      error: "",
    };
  },
  components: {
    RegisterPopupSuccess,
    ErrorRegister,
  },
  methods: {
    handleRegister() {
      // Kiểm tra tính hợp lệ của email và mật khẩu
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(this.user.email)) {
        this.RegisterFail = true;
        return;
      }

      if (this.user.password.length < 6) {
        this.RegisterFail = true;
        return;
      }

      apiClient
        .post("Register", this.user)
        .then((response) => {
          if (response.status === 201) {
            this.RegisterSucess = true;
            setTimeout(() => {
              this.$router.push("/Login");
            }, 700);
          } else {
            this.error = response.data.message;
          }
        })
        .catch((error) => {
          if (error.response) {
            this.error = error.response.data.message;
          } else {
            this.error = "An error occurred.";
          }
        });
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

.canh-bao {
  margin-top: 0;
  margin-bottom: 1rem;
  color: white;
  font-weight: bold;
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
.container {
  max-width: 900px; /* Đặt độ rộng tối đa cho container */
  margin: 100px 0 0 0 ; /* Đảm bảo container nằm giữa màn hình */
  padding: 0 15px; /* Thêm padding nếu cần thiết */
}
</style>
