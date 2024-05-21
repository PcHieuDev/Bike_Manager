<template>
  <div class="product-list">
    <template v-for="product in products" :key="product.id">
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
                <RouterLink
                  :to="`/product/edit/${product.id}`"
                  v-if="showButton"
                  class="edit-button"
                  >Sửa
                </RouterLink>
                <button
                  v-if="showButton"
                  @click.prevent="showModalDelete(product.id)"
                  class="delete-button"
                >
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>
      </router-link>
    </template>
  </div>
</template>

<script>
import axios from "axios";
export default {
  name: "ProductItem",
  props: {
    showPrice: {
      type: Boolean,
      default: true,
    },
    showButton: {
      type: Boolean,
      default: true,
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
    handleSearch() {
      this.$emit("handleSearch");
    },
  },
};
</script>
