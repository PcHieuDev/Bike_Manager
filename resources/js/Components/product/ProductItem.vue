<template>
  <div class="product-list">
    <template v-for="product in products" :key="product.id">
      <router-link :to="`/product/details/${product.id}`" style="text-decoration: none">
        <div class="column-4">
          <div class="div-19">
            <img
              :src="product.image"
              style="height: 146px; width: 230px"
              class="product-image"
            />
            <div class="product-details">
              <span class="product-name">{{ product.name }}</span>
              <span class="product-price">$ {{ product.price }}</span>
            </div>
          </div>
        </div>
      </router-link>
    </template>
  </div>
</template>

<script>
export default {
  name: "ProductItem",
  props: {
    products: {
      type: Array,
      required: true,
    },
  },
};
</script>
