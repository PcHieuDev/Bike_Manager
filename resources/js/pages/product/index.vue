<template>
  <ProductList />
</template>

<script>
import axios from "axios";
import  ProductList from '../../Components/product/ListProduct.vue'

export default {
  name: 'products',
  data() {
    return {
      products: [],
      page: 1,
      totalPages: null,
      // page: 1,
      // totalPages: null,
    }
  },
  components: {
    ProductList
  },
  created() {
    this.getProducts();
    // this.fetchData();

  },

  methods: {
    async getProducts() {
      let url = 'http://127.0.0.1:8000/api/products';
      await axios.get(url).then(response => {
        this.products = response.data.products;
        console.log(this.products);
      }).catch(error => {
        console.log(error);
      });
    },
    // async searchProducts() {
    //   try {
    //     let url = `http://127.0.0.1:8000/api/products?search=${this.searchTerm}`;
    //     const response = await axios.get(url);
    //     this.products = response.data.products;
    //   } catch (error) {
    //     console.error(error);
    //   }
    // }
  },



  mounted() {
    console.log('Component productList mounted.')
  }
}
</script>

<style>

</style>