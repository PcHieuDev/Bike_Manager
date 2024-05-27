<template>
  <!-- popup RegisterSucess -->
  <v-dialog :value="RegisterSucess" max-width="610">
    <v-card>
      <v-btn
        icon
        class="close-btn"
        @click="RegisterSucess = false"
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
          <div class="div-3h" style="color: black">Bạn đăng ký thành công</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "RegisterPopupSuccess",
  props: ["RegisterSucess"],
  methods: {
    closePopup() {
      this.$emit("close");
    },
  },
};
</script>
