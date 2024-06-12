<template>
  <div>
    <div class="header-product d-flex justify-content-between">
      <SearchBar @input="handleInput" @clear="clearSearchTerm"></SearchBar>
    </div>
    <!-- Product List -->
    <ProductItem :products="products" :showButton="false"></ProductItem>

    <!-- Pagination -->
    <div class="text-xs-center">
      <v-pagination
        v-model="pageClient"
        :length="totalPages"
        :total-visible="5"
      ></v-pagination>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="loading-page">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from "vuex";
import debounce from "lodash/debounce";
import ProductItem from "./ProductItem.vue";
import SearchBar from "../common/header/SearchBar.vue";
import { toast } from "vue3-toastify";

export default {
  components: {
    ProductItem,
    SearchBar,
  },
  data() {
    return {
      isShowDialog: false,
      afterAddProduct: false,
      pageClient: this.page,
      product: {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
      },
      debouncedSearch: null, // Chứa hàm debounce
    };
  },
  computed: {
    ...mapState(["products", "totalPages", "page", "isLoading"]),
    ...mapGetters(["products", "totalPages", "isLoading"]),
  },
  watch: {
    pageClient(newPage) {
      this.setPage(newPage);
      this.fetchProducts();
    },
    "$route.query.brandId": {
      immediate: true,
      handler(newBrandId) {
        if (newBrandId) {
          this.changeBrand(newBrandId);
        }
      },
    },
    "$route.query.categoryId": {
      immediate: true,
      handler(newCategoryId) {
        if (newCategoryId) {
          this.changeCategory(newCategoryId);
        }
      },
    },
  },
  created() {
    this.debouncedSearch = debounce(this.setSearchTermAndFetch, 1000);
    this.fetchProducts();
  },
  methods: {
    ...mapActions([
      "fetchProducts",
      "setPage",
      "setSearchTerm",
      "setBrandId",
      "setCategoryId",
    ]),
    handleInput(event) {
      const searchTerm = event.target.value;
      this.debouncedSearch(searchTerm);
    },
    clearSearchTerm() {
      this.setSearchTerm("");
      this.fetchProducts();
    },
    setSearchTermAndFetch(searchTerm) {
      // Xác thực searchTerm
      if (searchTerm === null || /^\s+$/.test(searchTerm)) {
        // Nếu searchTerm là null hoặc chỉ chứa dấu cách, hiển thị thông báo không tìm thấy sản phẩm
        toast.error("Không tìm thấy sản phẩm");
        return;
      } else if (/[!@#$%^&*(),.?":{}|<>]/.test(searchTerm)) {
        // Nếu searchTerm chứa ký tự đặc biệt, hiển thị toast thông báo không được nhập ký tự đặc biệt
        toast.error("Không được nhập ký tự đặc biệt");
        return;
      }

      // Nếu không phải các trường hợp trên, thực hiện việc gán searchTerm và fetchProducts
      this.setSearchTerm(searchTerm);
      this.fetchProducts()
        .then(() => {
          // Kiểm tra nếu không có sản phẩm được tìm thấy, hiển thị thông báo toast
          if (this.products.length === 0) {
            toast.error("Không tìm thấy sản phẩm");
          }
        })
        .catch((error) => {
          // Xử lý lỗi nếu cần
          console.error("Error fetching products:", error);
        });
    },

    changeBrand(brandId) {
      this.setBrandId(brandId);
    },
    // addProduct(product) {
    //   this.$store.dispatch("addProduct", product);
    //   this.isShowDialog = false;
    //   this.afterAddProduct = true;
    // },
    changeCategory(categoryId) {
      this.setCategoryId(categoryId);
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
