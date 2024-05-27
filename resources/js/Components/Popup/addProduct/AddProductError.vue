<template>
  <v-dialog :value="AddError" max-width="610">
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
          <div class="div-3h" style="color: black">Thêm Thất Bại</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "AddproductError",
  props: ["AddError"],

  methods: {
    closePopup() {
      this.$emit("close");
    },
  },
};
</script>
