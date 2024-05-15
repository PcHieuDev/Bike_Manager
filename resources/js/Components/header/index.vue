<template>
  <header class="header">
    <div class="overlap">
      <div
        class="s-n-ph-m-2"
        @click="goProductAction"
        @mouseenter="changeCursor"
        @mouseleave="restoreCursor"
      >
        QUẢN LÝ SẢN PHẨM
      </div>
      <div
        class="s-n-ph-m"
        @click="goHome"
        @mouseenter="changeCursor"
        @mouseleave="restoreCursor"
      >
        SẢN PHẨM
      </div>
      <!-- <div class="s-n-ph-m3" >HOME</div> -->

      <div class="overlap-group-3">
        <div
          class="text-wrapper-6"
          @click="goHome"
          @mouseenter="changeCursor"
          @mouseleave="restoreCursor"
        >
          NCC
        </div>
        <img
          @click="goHome"
          @mouseenter="changeCursor"
          @mouseleave="restoreCursor"
          class="image-3"
          alt="Image"
          src="https://ncc.asia/assets/images/logo.png"
        />
      </div>
      <ul class="nav" style="padding-left: 965px">
        <!--        <li class="nav-item">-->
        <!--          <router-link to="/product/add" class="nav-link" style="margin-left: 45px; font-size: 27px; color: white" href="path_to_your_add_product_page">Thêm sản phẩm</router-link>-->
        <!--        </li>-->
        <li class="nav-item">
          <RouterLink
            to="/login"
            class="nav-link"
            style="
              margin-left: 362px;
              font-size: 19px;
              color: black;
              margin-top: 8px;
              background-color: rgb(255 255 255);
              border: none;
              padding: 10px 20px;
              border-radius: 5px;
              cursor: pointer;
            "
          >
            LOGIN
          </RouterLink>
        </li>
      </ul>
    </div>
  </header>

  <!--  popuplogin v-model="ShowLogin"  -->

  <!-- <v-dialog v-model="ShowLogin" max-width="674">
    <v-card>
      <v-card-title class="headline">Login</v-card-title>
      <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="548" rounded="lg">
        <div class="text-subtitle-1 text-medium-emphasis">Account</div>

        <v-text-field
          density="compact"
          placeholder="Email address"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
        ></v-text-field>

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Password

          <a
            class="text-caption text-decoration-none text-blue"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
            style="padding-left: 120px"
          >
            Forgot login password?</a
          >
        </div>

        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
        ></v-text-field>

        <v-card class="mb-12" color="surface-variant" variant="tonal"> </v-card>

        <v-btn class="mb-8" color="blue" size="large" variant="tonal" block>
          Log In
        </v-btn>

        <v-card-text class="text-center">
          <a
            class="text-blue text-decoration-none"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
          >
            Sign up now
            <v-icon icon="mdi-chevron-right"></v-icon>
          </a>
        </v-card-text>
      </v-card>
    </v-card>

    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn
        color="black"
        text
        @click="ShowLogin = false"
        style="background-color: white"
      >
        Close
      </v-btn>
    </v-card-actions>
  </v-dialog> -->
</template>

<script>
export default {
  name: "Header",
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
      visible: false,
    };
  },

  methods: {
    goHome() {
      this.$router.push("/");
    },

    goProductAction() {
      this.$router.push("/product/actions");
    },
    changeCursor() {
      // Thay đổi kiểu con trỏ thành bàn tay chỉ
      document.body.style.cursor = "pointer";
    },
    restoreCursor() {
      // Khôi phục lại kiểu con trỏ mặc định
      document.body.style.cursor = "auto";
    },
  },
};
</script>

<style>
.header {
  background-color: transparent;
  height: 64px;
  left: 0;
  position: absolute;
  top: 0;
  width: 100%;
}

.header .overlap {
  /* background-image: url(https://c.animaapp.com/6fNIys5x/img/rectangle-1.svg); */
  background-size: 100% 100%;
  height: 64px;
  position: relative;
  /* width: 1440px; */
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
}

.header .image-3 {
  height: 34px;
  left: 70px;
  object-fit: cover;
  position: absolute;
  top: 14px;
  width: 34px;
}
</style>
