<template>
  <div class="header-product d-flex justify-content-between">
    <SearchBar @handleSearch="handleSearch"></SearchBar>
  </div>

  <!-- productlisst -->
  <ProductItem :products="products" :showButton="false"></ProductItem>
  <!-- productlisst -->

  <!-- button paginate -->
  <div class="text-xs-center">
    <v-pagination v-model="page" :length="countRercord" :total-visible="5"></v-pagination>
  </div>
  <!-- button paginate -->

  <!--  loading page-->
  <div v-if="isLoading" class="loading-page">
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </div>
  <!--  loading page-->

  <!-- popup add ProductActions -->
  <AddProductDialog
    v-model="isShowDialog"
    :product="product"
    @addProduct="addProduct"
  ></AddProductDialog>

  <!-- Use PupupAddSuccess component -->
  <PupupAddSuccess v-model="afterAddProduct"></PupupAddSuccess>
</template>

<script>
import axios from "axios";
import ProductItem from "./ProductItem.vue";
import Paginate from "vuejs-paginate";
import SearchBar from "../common/header/SearchBar.vue";
import AddProductDialog from "./AddProductDialog.vue";
import PupupAddSuccess from "../Popup/AddProduct/PupupAddSuccess.vue";

export default {
  name: "list",
  components: {
    ProductItem,
    Paginate,
    SearchBar,
    AddProductDialog,
    PupupAddSuccess,
    // SearchBar,
  },

  data() {
    return {
      products: [],
      page: 1,
      totalPages: null,
      countRercord: 0,
      itemsPerPage: 12,
      isLoading: false,
      isShowDialog: false,
      addCucces: false,
      afterAddProduct: false,
      showErrorPopup: false,
      searchTerm: "",
      currentCategoryId: null,
      product: {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
      },
      categories: [],
      brands: [],
      keyword: "",
    };
  },
  watch: {
    page(newData) {
      this.page = newData;
      this.getProducts();
    },
  },

  created() {
    this.getProducts();
  },
  mounted() {
    this.getListCategory();
    this.getListBrands();
    var token = localStorage.getItem("token");
    var user = localStorage.getItem("user");
    this.user = JSON.parse(localStorage.getItem("user"));
  },

  methods: {
    onChange(e) {
      this.product.image = e.target.files[0];
    },
    handleSearch(key) {
      this.keyword = key;
      this.page = 1;
      this.getProducts();
    },
    async getProducts() {
      this.isLoading = true;
      let url = "http://127.0.0.1:8000/api/productsFree";
      await axios
        .get(url, {
          params: {
            page: this.page,
            keyword: this.keyword,
            category_id: this.currentCategoryId,
          },
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
    onImageChange(e) {
      this.product.image = e.target.files[0];
    },

    changeDialog() {
      this.isShowDialog = !this.isShowDialog;
    },

    getListCategory() {
      axios
        .get("http://127.0.0.1:8000/api/categories")
        .then((response) => {
          this.categories = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getListBrands() {
      axios
        .get("http://127.0.0.1:8000/api/brands")
        .then((response) => {
          this.brands = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    selectCategory(categoryId) {
      this.currentCategoryId = categoryId;
      this.getProducts();
    },
  },
};
</script>

<style scoped>
.custom-search {
  width: 445px !important;
  margin-left: 650px;
}
</style>
