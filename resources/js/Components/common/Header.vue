<template>
  <header class="header">
    <div class="overlap">
      <div
        v-if="user"
        class="s-n-ph-m-2"
        @click="goProductAction"
        :class="{ clicked: productClicked }"
      >
        QUẢN LÝ SẢN PHẨM
      </div>
      <div class="s-n-ph-m" @click="goHome" :class="{ clicked: homeClicked }">
        SẢN PHẨM
      </div>

      <div class="overlap-group-3">
        <div class="text-wrapper-6" @click="goHome">NCC</div>
        <img
          @click="goHome"
          class="image-3"
          alt="Image"
          src="https://ncc.asia/assets/images/logo.png"
        />
      </div>

      <div class="custom-profile">
        <template v-if="user">
          <span class="mr-2">{{ user.name }}</span>
          <button @click="handleLogout" class="btn btn-custom">Logout</button>
        </template>
        <template v-else>
          <RouterLink to="/Login" class="btn btn-custom"> Login </RouterLink>
        </template>
      </div>
    </div>
  </header>

  <!-- popup LogoutSuccess -->
  <LogoutPopup v-model="LogoutSuccess"></LogoutPopup>
  <!-- popup LogoutSuccess -->
</template>

<script>
import { RouterLink, useRoute } from "vue-router";
import LogoutPopup from "../Popup/Logout/LogoutPopup.vue";

export default {
  name: "Header",

  components: {
    LogoutPopup,
  },

  computed: {
    product() {
      return product;
    },
  },

  data() {
    return {
      email: "",
      password: "",
      ShowLogin: false,
      LogoutSuccess: false,
      visible: false,
      user: null,
      route: useRoute(),
      productClicked: false,
      homeClicked: false,
    };
  },

  watch: {
    route: {
      handler: function (val) {
        this.getAuth();
      },
      deep: true,
    },
  },

  mounted() {
    this.getAuth();
  },

  methods: {
    goHome() {
      this.$router.push("/");
      this.homeClicked = true;
      this.productClicked = false;
    },
    getAuth() {
      var token = localStorage.getItem("token");
      var user = localStorage.getItem("user");
      if (token && user) {
        this.user = JSON.parse(localStorage.getItem("user"));
      } else {
        this.user = null;
        if (this.$route.path !== "/Register" && this.$route.path !== "/") {
          this.$router.push("/Login");
        }
      }
    },

    handleLogout() {
      localStorage.removeItem("token");
      localStorage.removeItem("user");
      this.LogoutSuccess = true;
      setTimeout(() => {
        this.LogoutSuccess = false;
      }, 600);
      setTimeout(() => {
        this.$router.push("/Login");
      }, 600);

      this.user = null;
    },

    goProductAction() {
      this.$router.push("/product-management/actions");
      this.productClicked = true;
      this.homeClicked = false;
    },
  },
};
</script>

<style scoped>
.header .s-n-ph-m-2.clicked,
.header .s-n-ph-m.clicked {
  color: black;
}

.mr-2 {
  margin-right: 8px !important;
  color: white;
  font-size: 23px;
  font-weight: bold;
}

.header {
  background-color: transparent;
  height: 64px;
  left: 0;
  position: fixed; /* Changed from absolute to fixed */
  top: 0;
  width: 100%;
  z-index: 1000; /* Added to ensure the header stays on top */
}

.header .overlap {
  background-size: 100% 100%;
  height: 64px;
  position: relative;
  background-color: #00ade8;
}

.header .qu-n-l-s-n-ph-m {
  color: var(--white);
  font-family: "Roboto", Helvetica;
  font-size: 18px;
  font-weight: 400;
  height: 64px;
  left: 497px;
  letter-spacing: 0;
  line-height: 16px;
  opacity: 0.75;
  position: absolute;
  top: 25px;
  width: 247px;
  cursor: pointer;
}

.header .s-n-ph-m {
  color: var(--white);
  font-family: var(--active-font-family);
  font-size: var(--active-font-size);
  font-style: var(--active-font-style);
  font-weight: var(--active-font-weight);
  height: 64px;
  left: 363px;
  letter-spacing: var(--active-letter-spacing);
  line-height: var(--active-line-height);
  position: absolute;
  top: 25px;
  width: 125px;
  cursor: pointer;
}

.header .s-n-ph-m-2 {
  color: var(--white);
  font-family: var(--active-font-family);
  font-size: var(--active-font-size);
  font-style: var(--active-font-style);
  font-weight: var(--active-font-weight);
  height: 64px;
  left: 500px;
  letter-spacing: var(--active-letter-spacing);
  line-height: var(--active-line-height);
  position: absolute;
  top: 25px;
  width: 220px;
  cursor: pointer;
}

.header .overlap-group-3 {
  height: 64px;
  left: 0;
  position: absolute;
  top: 0;
  width: 318px;
}

.header .text-wrapper-6 {
  color: var(--white);
  font-family: "Petrona", Helvetica;
  font-size: 48px;
  font-weight: 700;
  height: 64px;
  left: 0;
  letter-spacing: 0;
  line-height: normal;
  position: absolute;
  text-align: center;
  top: 5px;
  width: 318px;
  cursor: pointer;
}

.header .image-3 {
  height: 34px;
  left: 70px;
  object-fit: cover;
  position: absolute;
  top: 14px;
  width: 34px;
}

.header .btn-custom {
  background-color: white;
  color: #00ade8;
  border: 2px solid #00ade8;
  border-radius: 5px;
  padding: 5px 10px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease, color 0.3s ease;
}

.header .btn-custom:hover {
  background-color: #00ade8;
  color: white;
}
</style>
