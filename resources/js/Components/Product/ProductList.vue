<template>
  <div class="product-list">
    <template v-for="product in products" :key="product.id">
      <!-- ProductItem component -->
      <ProductItem
        :product="product"
        :showPrice="showPrice"
        :showButton="showButton"
        @showModalDelete="showModalDelete"
      ></ProductItem>

      <!-- ProductItem component -->
    </template>
  </div>
</template>

<script>
import ProductItem from "./ProductItem.vue";

export default {
  name: "ProductList",
  components: {
    ProductItem,
  },
  props: {
    showPrice: {
      type: Boolean,
      default: true,
    },
    showButton: {
      type: Boolean,
      default: false,
    },
    products: {
      type: Array,
      required: true,
    },
  },
  methods: {
    showModalDelete(id) {
      this.$emit("showModalDelete", id);
    },
  },
};
</script>

<!--<style scoped>-->
<!--/* Add any styles specific to ProductList here */-->
<!--.product-list {-->
<!--  background-color: white; /* Màu nền trắng */-->
<!--  padding: 10px; /* Khoảng cách giữa khung và sản phẩm */-->
<!--  border-radius: 20px; /* Viền bo tròn */-->
<!--  margin-top: 20px; /* Khoảng cách giữa khung và sản phẩm */-->
<!--  padding-top: 40px;-->
<!--  padding-left: 5px;-->
<!--  padding-bottom: 40px;-->
<!--}-->
<!--</style>-->
