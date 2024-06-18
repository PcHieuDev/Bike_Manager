<template>
  <v-dialog :value="afterAddProduct" max-width="610">
    <v-card>
      <v-btn icon class="close-btn" @click="closePopup" style="margin-left: 560px">
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/c874e8cc8a811eff704f0e5510fc452ecb9a60a6139ea7d524e2755e2a1051fd?apiKey=c828819b5944477ab9c72fd951f3ee71&"
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
  methods: {
    closePopup() {
      this.$emit("close");
    },
  },
};
</script>
