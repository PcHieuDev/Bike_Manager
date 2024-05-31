<template>
  <v-dialog :value="afterUpdateProduct" max-width="610">
    <v-card>
      <v-btn
          icon
          class="close-btn"
          @click="closePopup"
          style="margin-left: 560px"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="/storage/images/thanhcongcapnhat.png"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">Bạn đã cập nhật thành công</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  props: {
    afterUpdateProduct: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    closePopup() {
      this.$emit("closePopup");
    },
  },
};
</script>