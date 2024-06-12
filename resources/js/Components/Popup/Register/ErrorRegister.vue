<!-- LoginPopupSuccess.vue -->
<template>
  <v-dialog :value="RegisterFail" max-width="610">
    <v-card>
      <v-btn icon class="close-btn" @click="closePopup" style="margin-left: 560px">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c6/OOjs_UI_icon_alert-warning-black.svg/1138px-OOjs_UI_icon_alert-warning-black.svg.png"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">
            có lỗi, hãy kiểm tra lại, email không hợp lệ , tài khoản đã tồn tại hoặc
            password bạn nhập ngắn hơn 6 ký tự
          </div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "ErrorRegister",
  props: ["RegisterFail"],
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
