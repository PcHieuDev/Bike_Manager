<template>
  <router-link :to="`/product/details/${product.id}`" style="text-decoration: none">
    <div class="column-4">
      <div class="div-19">
        <img
            :src="product.image"
            style="height: 140px; width: 227px"
            class="product-image"
        />
        <div class="product-details">
          <span class="product-name">{{ product.name }}</span>
          <span v-if="showPrice" class="product-price">$ {{ product.price }}</span>
          <div class="product-actions">
            <router-link
                :to="`/product/edit/${product.id}`"
                v-if="showButton"
                class="edit-button"
            >Sửa</router-link>
            <button
                v-if="showButton"
                @click.prevent="showModalDelete(product.id)"
                class="delete-button"
            >Xóa</button>
          </div>
        </div>
      </div>
    </div>
  </router-link>
</template>

<script>
export default {
  name: "ProductItem",
  props: {
    product: {
      type: Object,
      required: true,
    },
    showPrice: {
      type: Boolean,
      default: true,
    },
    showButton: {
      type: Boolean,
      default: true,
    },
  },
  methods: {
    showModalDelete(id) {
      this.$emit("showModalDelete", id);
    },
  },
};
</script>

<style scoped>
/* Add any styles specific to ProductItem here */
</style>
