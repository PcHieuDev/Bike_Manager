<template>
  <div class="header-product d-flex justify-content-between">
    <AddProductButton @changeDialog="changeDialog" :user="user"></AddProductButton>
    <SearchBar @input="handleInput" @clear="clearSearchTerm"></SearchBar>
  </div>

  <div class="white-box">
    <!-- List san pham -->
    <ProductList
      v-if="products && products.length > 0"
      :products="products"
      :showButton="true"
      :showPrice="false"
      @showModalDelete="showModalDelete"
    >
    </ProductList>

    <!-- paginate -->
    <div v-if="products && products.length > 0" class="text-xs-center">
      <!-- Paginate Component -->
      <Pagination
        :pages="countRercord"
        :initial-page="page"
        @update:page="handlePageChange"
      ></Pagination>
    </div>
    <div v-else class="no-products">Không có sản phẩm</div>
  </div>

  <!--  loading page-->
  <div v-if="isLoading" class="loading-page">
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </div>
  <!--  loading page-->

  <!-- popup add ProductActions -->
  <AddProductDialog
    v-model="isShowDialog"
    @submit="addProduct"
    @close="isShowDialog = false"
    :categories="categories"
    :brands="brands"
  >
  </AddProductDialog>

  <!-- popup beforeDelete -->
  <PopupBeforeDelete
    v-model="beforeDelete"
    :deleteProduct="deleteProduct"
    :afterDelete="afterDelete"
    @close="beforeDelete = false"
  ></PopupBeforeDelete>

  <!-- popup afterdelete -->
  <PopupDeleteSuccess
    v-model="afterDelete"
    @close="afterDelete = false"
  ></PopupDeleteSuccess>
  <!-- popup afterdelete -->

  <!-- popup afterAddProduct -->
  <PupupAddSuccess
    v-model="afterAddProduct"
    @close="afterAddProduct = false"
  ></PupupAddSuccess>
  <!-- popup afterAddProduct -->

  <!-- popup AddError -->
  <AddProductError v-model="AddError" @close="AddError = false"></AddProductError>
  <!-- popup AddError -->

  <!-- popup AddMissing -->
  <Popupaddmissing v-model="AddMissing" @close="AddMissing = false"></Popupaddmissing>
  <!-- popup AddMissing -->

  <!-- popup NoProducts -->
  <!--  <NoProducts v-model="noProductsFound" @close="noProductsFound = false"></NoProducts>-->
</template>

<script>
import ProductList from "../Components/Product/ProductList.vue";
import Paginate from "vuejs-paginate";
import AddProductDialog from "./product/AddProductDialog.vue";
import PupupAddSuccess from "../Components/Popup/AddProduct/PupupAddSuccess.vue";
import PopupDeleteSuccess from "../Components/Popup/DeleteProduct/PopupDeleteSuccess.vue";
import PopupBeforeDelete from "../Components/Popup/DeleteProduct/PopupBeforeDelete.vue";
import AddProductError from "../Components/Popup/AddProduct/AddProductError.vue";
import Popupaddmissing from "../Components/Popup/AddProduct/AddproductMissing.vue";
import apiClient from "../axios-interceptor";
import debounce from "lodash/debounce";
import SearchBar from "../Components/common/SearchBar.vue";
import AddProductButton from "../Components/common/AddProductButton.vue";
import { validateProduct } from "../validateProduct";
import { toast } from "vue3-toastify";
import { mapActions, mapGetters } from "vuex";
import Pagination from "../Components/common/Paginate.vue";

export default {
  name: "list",
  components: {
    Pagination,
    ProductList,
    AddProductDialog,
    PupupAddSuccess,
    PopupDeleteSuccess,
    PopupBeforeDelete,
    AddProductError,
    Popupaddmissing,
    AddProductButton,
    SearchBar,
    // NoProducts,
  },

  data() {
    return {
      searchKeyword: "",
      noProductsFound: false,
      user: null,
      productId: null,
      products: [],
      page: 1,
      totalPages: null,
      countRercord: 0,
      itemsPerPage: 12,
      isLoading: false,
      isShowDialog: false,
      afterAddProduct: false,
      addCucces: false,
      searchTerm: "",
      beforeDelete: false,
      AddError: false,
      AddMissing: false,
      NoProducts: false,
      afterDelete: false,
      editproduct: false,
      productIdDelete: null,
      // ShowLogin: false,
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
      debouncedSearch: null,
      imageNull: "/storage/images/noproduct.png",
    };
  },

  watch: {
    page(newData) {
      this.page = newData;
      this.getProducts();
    },
  },
  created() {
    this.debouncedSearch = debounce(this.setSearchTermAndFetch, 1000);
  },

  mounted() {
    if (localStorage.getItem("searchKeyword")) {
      this.keyword = localStorage.getItem("searchKeyword");
    }
    this.getProducts();
    this.getListCategory();
    this.getListBrands();
    var token = localStorage.getItem("token");
    var user = localStorage.getItem("user");
    this.user = JSON.parse(localStorage.getItem("user"));
  },
  computed: {
    isProductActionsPath() {
      return this.$route.path === "/ProductActions/actions";
    },
  },

  methods: {
    handlePageChange(newPage) {
      this.page = newPage;
      this.getProducts(); // Hàm lấy dữ liệu sản phẩm dựa trên trang mới
    },

    handleInput(event) {
      const searchTerm = event.target.value;
      this.debouncedSearch(searchTerm);
    },
    clearSearchTerm() {
      this.searchTerm = ""; // Update the searchTerm directly
      this.getProducts();
    },
    setSearchTermAndFetch(searchTerm) {
      // Xác thực searchTerm
      if (searchTerm === null || /^\s+$/.test(searchTerm)) {
        // Nếu searchTerm là null hoặc chỉ chứa dấu cách, hiển thị thông báo không tìm thấy sản phẩm
        toast.error("Nhập gì đó khác ik");
        return;
      } else if (/[!@#$%^&*(),.?":{}|<>]/.test(searchTerm)) {
        // Nếu searchTerm chứa ký tự đặc biệt, hiển thị toast thông báo không được nhập ký tự đặc biệt
        toast.error("Không được nhập ký tự đặc biệt");
        return;
      }

      // Nếu không phải các trường hợp trên, thực hiện việc gán searchTerm và fetchProducts
      this.searchTerm = searchTerm; // Update the searchTerm directly
      this.getProducts()
        .then(() => {
          // Kiểm tra nếu không có sản phẩm được tìm thấy, hiển thị thông báo toast
          if (this.products.length === 0) {
            // toast.error("Không tìm thấy sản phẩm");
            setTimeout(() => {
              this.searchTerm = ""; // Reset searchTerm
              this.getProducts();
            }, 3000);
          }
        })
        .catch((error) => {
          // Xử lý lỗi nếu cần
          console.error("Error fetching products:", error);
        });
    },
    debouncedSetSearchTermAndFetch(searchTerm) {
      this.debouncedSearch(searchTerm);
    },

    showModalDelete(id) {
      console.log(id);
      this.beforeDelete = true;
      this.productIdDelete = id;
    },

    openEditPopup() {
      this.editproduct = !this.editproduct;
    },

    async getProducts() {
      var token = localStorage.getItem("token");
      this.isLoading = true;
      let url = "products";
      await apiClient
        .get(url, {
          params: {
            page: this.page,
            keyword: this.searchTerm,
            itemsPerPage: this.itemsPerPage,
          },
          headers: { Authorization: `Bearer ${token}` },
        })
        .then((response) => {
          this.products = response.data.contents;
          this.totalRecords = response.data.count;
          this.countRercord = Math.ceil(response.data.count / this.itemsPerPage);
          console.log(response.data.contents);
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },

    changeDialog() {
      this.isShowDialog = !this.isShowDialog;
    },

    addProduct(product) {
      // Kiểm tra xem các trường bắt buộc có bị thiếu không
      if (!validateProduct(product)) {
        this.AddMissing = true;
        return;
      }
      const formData = new FormData();
      formData.append("name", product.name);
      formData.append("note", product.note || ""); // Đảm bảo note luôn có giá trị để tránh lỗi
      formData.append("price", product.price);
      formData.append("category_id", product.category_id);
      formData.append("brand_id", product.brand_id);
      formData.append("image", product.image);
      let token = localStorage.getItem("token");
      apiClient
        .post("saveProduct", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          this.getProducts();
          const productId = response.data.product.id;
          // open popup afterAddProduct
          console.log(productId); // Đảm bảo rằng productId được log ra đúng
          setTimeout(() => {
            // Thiết lập this.afterAddProduct sau 1 giây
            this.afterAddProduct = true;

            // Chuyển hướng tới trang /product/details/:id
            this.$router.push({ path: `/product/details/${productId}` });
          }, 1000);
          this.afterAddProduct = true;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.isShowDialog = false;
          this.page = 1;
          this.getProducts();
        });
    },

    onChange(e) {
      this.product.image = e.target.files[0];
    },

    getListCategory() {
      apiClient
        .get("categories")
        .then((response) => {
          this.categories = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getListBrands() {
      apiClient
        .get("brands")
        .then((response) => {
          this.brands = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    searchProducts(query) {
      apiClient
        .get("search", {
          params: {
            query: query,
          },
        })
        .then((response) => {
          this.products = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    deleteProduct() {
      let url = `delete_products/${this.productIdDelete}`;
      let token = localStorage.getItem("token");
      apiClient
        .delete(url, {
          headers: { Authorization: `Bearer ${token}` },
        })
        .then(() => {
          this.beforeDelete = false;
          this.afterDelete = true;
          console.log("delete success");
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          if (this.products.length === 1 && this.page > 1) {
            this.page--;
          }
          this.getProducts();
        });
    },
  },
};
</script>

<style scoped>
.no-products {
  text-align: center; /* Căn giữa nội dung ngang */
  padding: 20px;
  font-size: 18px;
  color: #666;
  display: flex; /* Sử dụng flexbox */
  align-items: center; /* Căn giữa các item theo chiều ngang */
  justify-content: center; /* Căn giữa các item theo chiều dọc */
  height: 300px; /* Đặt chiều cao div tối đa hoặc theo nhu cầu */
}

.header-product {
  padding-top: 60px;
  width: 100%;
  margin-left: 0px;
  margin-bottom: 30px;
}
.product-list {
  padding-left: 10px;
}

.white-box {
  background-color: white;
  border-radius: 20px;
  padding: 20px 15px 15px 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

.text-xs-center {
  margin-top: 20px;
}
</style>
