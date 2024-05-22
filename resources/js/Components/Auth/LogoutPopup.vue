<template>
  <v-dialog :value="LogoutSuccess" max-width="610">
    <v-card>
      <v-btn
        icon
        class="close-btn"
        @click="$emit('update:LogoutSuccess', false)"
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
          <div class="div-3h" style="color: black">Bạn đã đăng xuất</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "LogoutPopup",
  props: ["LogoutSuccess"],
  methods: {
    closePopup() {
      this.$emit("update:LogoutSuccess", false);
    },
  },
};
</script>
