<template>
  <v-dialog :value="beforeDelete" max-width="610">
    <v-card>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/e8f2d1f13b8ec9969b70e9b0e791eb4958cb760378a08537602787e8bfeefd1b?apiKey=c828819b5944477ab9c72fd951f3ee71&"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">
            Bạn có chắc muốn xóa ảnh này không ? Ảnh này sẽ bị
            <span style="color: rgba(255, 77, 77, 1)">xóa vĩnh viễn</span>.
          </div>
          <div class="div-4b">
            <button type="button" @click="closePopup" class="popup-detail-btn cancel">
              Hủy
            </button>
            <button type="button" @click="removeImageMethod" class="popup-detail-btn submit">
              Xóa
            </button>
          </div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "DeleteImage",
  props: ["beforeDelete", "removeImage", "selectedImageIndex"],
  methods: {
    closePopup() {
      this.$emit("close");
    },
    removeImageMethod() {
      this.$emit('removeImage', this.selectedImageIndex);
      this.closePopup();
    },
  },
};
</script>
