<template>
  <div class="header-product d-flex justify-content-between">
    <button
      v-if="user"
      type="button"
      @click="changeDialog"
      class="btn btn-outline-primary"
    >
      Thêm sản phẩm
    </button>
    <SearchBar @handleSearch="handleSearch"></SearchBar>
  </div>
  <!-- List san pham -->
  <ProductItem
    :products="products"
    :showActions="true"
    :showPrice="false"
    @showModalDelete="showModalDelete"
  >
  </ProductItem>

  <div class="text-xs-center">
    <v-pagination v-model="page" :length="countRercord" :total-visible="5"></v-pagination>
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
</template>

<script>
import axios from "axios";
import ProductItem from "../../Components/ProductActions/ProductItem.vue";
import Paginate from "vuejs-paginate";
import AddProductDialog from "../../Components/ProductActions/AddProductDialog.vue";
import PupupAddSuccess from "../../Components/Popup/AddProduct/PupupAddSuccess.vue";
import PopupDeleteSuccess from "../../Components/Popup/DeleteProduct/PopupDeleteSuccess.vue";
import PopupBeforeDelete from "../../Components/Popup/DeleteProduct/PopupBeforeDelete.vue";
import AddProductError from "../../Components/Popup/AddProduct/AddProductError.vue";
import Popupaddmissing from "../../Components/Popup/AddProduct/AddproductMissing.vue";
import { BASE_URL } from "../../configUrl.js";
import SearchBar from "../../Components/common/header/SearchBar.vue";
import { validateProduct } from "../../validateProduct.js";

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
      afterAddProduct: false,
      addCucces: false,
      searchTerm: "",
      beforeDelete: false,
      AddError: false,
      AddMissing: false,
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
    };
  },
  watch: {
    page(newData) {
      this.page = newData;
      this.getProducts();
    },
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
    showModalDelete(id) {
      console.log(id);
      this.beforeDelete = true;
      this.productIdDelete = id;
    },

    openEditPopup() {
      this.editproduct = !this.editproduct;
    },
    handleSearch(key) {
      this.keyword = key;
      this.page = 1;
      this.getProducts();
    },
    async getProducts() {
      var token = localStorage.getItem("token");
      this.isLoading = true;
      let url = BASE_URL + "products";
      await axios
        .get(url, {
          params: {
            page: this.page,
            keyword: this.keyword,
          },
          headers: { Authorization: `Bearer ${token}` },
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
      axios
        .post(BASE_URL + "saveProduct", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          this.afterAddProduct = true;
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.isShowDialog = false;
          this.page = 1;
          setTimeout(() => {
            this.getProducts().then(() => {
              if (this.products.length > 0) {
                console.log(this.products[0].id); // In ra id của sản phẩm đầu tiên trong mảng products
                this.$router.push(`/product/details/${this.products[0].id}`); // Chuyển hướng đến trang /product/details/$product.id sau 1 giây
              }
            });
          }, 800);
        });
    },

    onChange(e) {
      this.product.image = e.target.files[0];
    },

    getListCategory() {
      axios
        .get(BASE_URL + "categories")
        .then((response) => {
          this.categories = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getListBrands() {
      axios
        .get(BASE_URL + "brands")
        .then((response) => {
          this.brands = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    searchProducts(query) {
      axios
        .get(BASE_URL + "search", {
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
      let url = BASE_URL + `delete_products/${this.productIdDelete}`;
      let token = localStorage.getItem("token");
      axios
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
.header-product {
  padding-left: 347px;
  padding-top: 100px;
  width: 95%;
  margin-left: 0px;
}
</style>
