<template>
  <v-dialog v-model="afterAddProduct" max-width="610">
    <v-card>
      <v-btn
        icon
        class="close-btn"
        @click="afterAddProduct = false"
        style="margin-left: 560px"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/e8f2d1f13b8ec9969b70e9b0e791eb4958cb760378a08537602787e8bfeefd1b?apiKey=c828819b5944477ab9c72fd951f3ee71&"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">Bạn đã thêm thành công</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "PupupAddSuccess",
  props: ["afterAddProduct"],
};
</script>
