<template>
  <HeaderProduct
      :user="user"
      @changeDialog="changeDialog"
      @input="handleInput"
      @clear="clearSearchTerm"
  ></HeaderProduct>
  <!-- List san pham -->
  <ProductItem
      :products="products"
      :showActions="true"
      :showPrice="false"
      @showModalDelete="showModalDelete"
  >
  </ProductItem>

  <!-- paginate -->
  <div class="text-xs-center">
    <v-pagination
        v-model="page"
        :length="countRercord"
        :total-visible="5"
    ></v-pagination>
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
import ProductItem from "../Components/Product/ProductItem.vue";
import Paginate from "vuejs-paginate";
import AddProductDialog from "./product/AddProductDialog.vue"
import PupupAddSuccess from "../Components/Popup/AddProduct/PupupAddSuccess.vue"
import PopupDeleteSuccess from "../Components/Popup/DeleteProduct/PopupDeleteSuccess.vue";
import PopupBeforeDelete from "../Components/Popup/DeleteProduct/PopupBeforeDelete.vue";
import AddProductError from "../Components/Popup/AddProduct/AddProductError.vue";
import Popupaddmissing from "../Components/Popup/AddProduct/AddproductMissing.vue";
// import NoProducts from "../Components/Popup/Search/noProduct.vue";
import apiClient from "../axios-interceptor"
import debounce from "lodash/debounce";
import HeaderProduct from "../Components/common/HeaderProduct.vue";
import SearchBar from "../Components/common/SearchBar.vue";
import {validateProduct} from "../validateProduct"
import {toast} from "vue3-toastify";
import {mapActions} from "vuex";

export default {
  name: "list",
  components: {
    Paginate,
    ProductItem,
    AddProductDialog,
    PupupAddSuccess,
    PopupDeleteSuccess,
    PopupBeforeDelete,
    AddProductError,
    Popupaddmissing,
    SearchBar,
    HeaderProduct,
    // NoProducts,
  },

  data() {
    return {
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
    this.getProducts();
    this.getListCategory();
    this.getListBrands();
    var token = localStorage.getItem("token");
    var user = localStorage.getItem("user");
    this.user = JSON.parse(localStorage.getItem("user"));
  },

  methods: {
    ...mapActions([
      "setCategoryId",
      "setBrandId",
    ]),

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
        toast.error("Không tìm thấy sản phẩm");
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
              toast.error("Không tìm thấy sản phẩm");
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
            },
            headers: {Authorization: `Bearer ${token}`},
          })
          .then((response) => {
            this.products = response.data.contents;
            this.totalRecords = response.data.count;
            this.countRercord = Math.ceil(response.data.count / this.itemsPerPage);
            // this.noProductsFound = this.totalRecords === 0;
            console.log(this.countRercord);
            console.log(response.data.contents);
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            this.isLoading = false;
          });
    },

    computed: {
      isProductActionsPath() {
        return this.$route.path === "/ProductActions/actions";
      },
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
              this.$router.push({path: `/product/details/${productId}`});
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
            headers: {Authorization: `Bearer ${token}`},
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
.header-product {
  padding-top: 60px;
  width: 92%;
  margin-left: 20px;
}
</style>
