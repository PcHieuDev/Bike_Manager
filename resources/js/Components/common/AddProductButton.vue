<template>
  <button v-if="user" type="button" @click="changeDialog" class="btn btn-outline-primary">
    Thêm sản phẩm
  </button>
</template>

<script>
export default {
  name: "AddProductButton",
  props: ["user"],
  methods: {
    changeDialog() {
      this.$emit("changeDialog");
    },
  },
};
</script>
