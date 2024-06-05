<template>
  <ProductList />
</template>

<script>
import axios from "axios";
import ProductList from "../../Components/ProductActions/ListProduct.vue";
import { BASE_URL } from "../../configUrl.js";

export default {
  name: "products",
  data() {
    return {
      products: [],
      page: 1,
      totalPages: null,
    };
  },
  components: {
    ProductList,
  },
  created() {
    this.getProducts();
  },
  methods: {
    async getProducts() {
      var token = localStorage.getItem("token");
      this.isLoading = true;
      let url = BASE_URL + "productsFree";
      await axios
        .get(url, {
          params: {
            page: this.page,
            keyword: this.keyword,
          },
          // headers: { Authorization: `Bearer ${token}` },
        })
        .then((response) => {
          this.products = response.data.contents;
          this.countRercord = Math.ceil(response.data.count / this.itemsPerPage);
          console.log(this.products);
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
  mounted() {
    console.log("Component productList mounted.");
  },
};
</script>

<style></style>
