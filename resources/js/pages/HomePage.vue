<template>
  <div>
    <div class="header-product d-flex justify-content-between">
      <SearchBar @input="handleInput" @clear="clearSearchTerm"></SearchBar>
    </div>
    <!-- Danh sách sản phẩm và phân trang trong khung màu trắng -->
    <div class="white-box">
      <!-- Chỉ hiển thị ProductList và Pagination nếu có sản phẩm -->
      <ProductList
        v-if="products && products.length > 0"
        :products="products"
      ></ProductList>
      <Pagination
        v-if="products && products.length > 0"
        :page="page"
        :totalVisible="5"
        :totalPages="totalPages"
        @updatePage="updatePage"
      ></Pagination>
      <!-- Hiển thị thông báo nếu không có sản phẩm -->
      <div v-else class="no-products">Không có sản phẩm</div>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading" class="loading-page">
      <v-progress-circular indeterminate color="primary"></v-progress-circular>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import debounce from "lodash/debounce";
import ProductList from "../Components/Product/ProductList.vue";
import SearchBar from "../Components/common/SearchBar.vue";
import Pagination from "../Components/common/Pagination.vue";
import { toast } from "vue3-toastify";

export default {
  components: {
    ProductList,
    SearchBar,
    Pagination,
  },
  data() {
    return {
      debouncedSearch: null,
    };
  },
  computed: {
    ...mapState(["products", "totalPages", "page", "isLoading"]),
  },
  watch: {
    page(newPage) {
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
    },
    setSearchTermAndFetch(searchTerm) {
      if (searchTerm === null || /^\s+$/.test(searchTerm)) {
        toast.error("Không tìm thấy sản phẩm");
        return;
      } else if (/[!@#$%^&*(),.?":{}|<>]/.test(searchTerm)) {
        toast.error("Không được nhập ký tự đặc biệt");
        return;
      }

      this.setSearchTerm(searchTerm);
      this.fetchProducts()
        .then(() => {
          if (this.products.length === 0) {
            // toast.error("Không tìm thấy sản phẩm");
            setTimeout(() => {
              this.setSearchTerm("");
              this.fetchProducts();
            }, 3000);
          }
        })
        .catch((error) => {
          console.error("Error fetching products:", error);
        });
    },
    updatePage(newPage) {
      this.setPage(newPage);
      this.fetchProducts();
    },
    changeBrand(brandId) {
      this.setBrandId(brandId);
    },
    changeCategory(categoryId) {
      this.setCategoryId(categoryId);
    },
  },
};
</script>

<style scoped>
.custom-search {
  width: 445px !important;
  margin-left: 750px;
  margin-bottom: 30px;
}

.white-box {
  background-color: white;
  border-radius: 20px;
  padding: 20px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.no-products {
  padding-top: 150px;
  text-align: center;
  font-size: 18px;
  color: #666;
  height: 300px;
  font-weight: bold;
}
</style>
