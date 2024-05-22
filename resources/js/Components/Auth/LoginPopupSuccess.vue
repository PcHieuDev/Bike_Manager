<!-- LoginPopupSuccess.vue -->
<template>
  <v-dialog v-model="LoginSuccess" max-width="610">
  <v-card>
    <v-btn
        icon
        class="close-btn"
        @click="LoginSuccess = false"
        style="margin-left: 560px"
    >
      <v-icon>mdi-close</v-icon>
    </v-btn>
    <div class="divqw popup-detail">
      <div class="div-2d">
        <img
            loading="lazy"
            src="https://icons.veryicon.com/png/o/miscellaneous/8atour/success-35.png"
            class="imgsd"
        />
        <div class="div-3h" style="color: black">Bạn đăng nhập thành công</div>
      </div>
    </div>
  </v-card>
</v-dialog>
</template>

<script>
export default {
  name: "LoginPopupSuccess",
  props: ["LoginSuccess"],
  methods: {
    closePopup() {
      this.$emit("close");
    },
  },
};
</script>

<style scoped>
.close-btn {
  margin-left: 560px;
}
.popup-detail {
  text-align: center;
}
.imgsd {
  width: 100px;
  height: 100px;
}
</style>
