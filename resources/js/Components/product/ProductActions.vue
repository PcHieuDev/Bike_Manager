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
    <div class="input-group custom-search">
      <input
        type="text"
        class="form-control"
        placeholder="Tìm kiếm"
        aria-label="Username"
        @keyup.enter="handleSearch"
        aria-describedby="basic-addon1"
        v-model="keyword"
      />
    </div>
  </div>
  <!-- List san pham -->
  <ProductItem :products="products" :showActions="true" :showPrice="false"></ProductItem>

  <div class="text-xs-center">
    <v-pagination v-model="page" :length="countRercord" :total-visible="5"></v-pagination>
  </div>

  <!--  loading page-->
  <div v-if="isLoading" class="loading-page">
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </div>
  <!--  loading page-->

  <!-- popup add product-->
  <!-- <v-dialog v-model="isShowDialog" max-width="674">
    <v-card>
      <v-card-title class="headline">Thêm sản phẩm</v-card-title>
      <v-card-text>
        <form>
          <fieldset>
            <div class="form-group">
              <label class="form-label mt-4">Name</label>
              <input
                type="text"
                class="form-control"
                v-model="product.name"
                placeholder="Enter name"
              />
            </div>
            <div class="form-group">
              <label class="form-label mt-4">Price</label>
              <input
                type="text"
                class="form-control"
                v-model="product.price"
                placeholder="Enter price"
              />
            </div>

            <div class="form-group">
              <label class="form-label mt-4">image</label>
              <input
                type="file"
                class="form-control"
                @change="onChange"
                placeholder="Enter image"
              />
            </div>
            <div class="form-group">
              <label class="form-label mt-4">category</label>
              <select class="form-select" v-model="product.category_id">
                <template v-for="(item, index) in categories" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label mt-4">Hãng sản xuất</label>
              <select class="form-select" v-model="product.brand_id">
                <template v-for="(item, index) in brands" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label mt-4">Note</label>
              <textarea class="form-control" v-model="product.note" rows="3"></textarea>
            </div>
          </fieldset>
        </form>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <button @click="addProduct" type="button" class="btn btn-outline-primary">
          Thêm sản phẩm
        </button>
      </v-card-actions>
    </v-card>
  </v-dialog> -->

  <AddProductDialog
    @click="changeDialog"
    :isShowDialog="isShowDialog"
    :getListCategory="getListCategory"
    :getListBrands="getListBrands"
    :addProduct="addProduct"
    :onChange="onChange"
    :product="product"
    :getProducts="getProducts"
    :changeDialog="changeDialog"
  ></AddProductDialog>

  <!-- popup beforeDelete -->
  <v-dialog v-model="beforeDelete" max-width="610">
    <v-card>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/e8f2d1f13b8ec9969b70e9b0e791eb4958cb760378a08537602787e8bfeefd1b?apiKey=c828819b5944477ab9c72fd951f3ee71&"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">
            Bạn có chắc muốn xóa sản phẩm
            <span style="color: rgba(255, 77, 77, 1)">Streetfighter V4</span>?<br />Sản
            phẩm sẽ bị <span style="color: rgba(255, 77, 77, 1)">xóa vĩnh viễn</span>.
          </div>
          <div class="div-4b">
            <button
              type="button"
              @click="beforeDelete = !beforeDelete"
              class="popup-detail-btn cancel"
            >
              Hủy
            </button>
            <button type="button" @click="deleteProduct" class="popup-detail-btn submit">
              Xóa
            </button>
          </div>
        </div>
      </div>
    </v-card>
  </v-dialog>

  <!-- popup afterdelete -->
  <v-dialog v-model="afterDelete" max-width="610">
    <v-card>
      <v-btn
        icon
        class="close-btn"
        @click="afterDelete = false"
        style="margin-left: 560px"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/e8f2d1f13b8ec9969b70e9b0e791eb4958cb760378a08537602787e8bfeefd1b?apiKey=c828819b5944477ab9c72fd951f3ee71&"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">Bạn đã xóa thành công</div>
        </div>
      </div>
    </v-card>
  </v-dialog>

  <!-- popup afterAddProduct  -->

  <!-- popup afterAddProduct -->
  <v-dialog v-model="afterAddProduct" max-width="610">
    <v-card>
      <v-btn
        icon
        class="close-btn"
        @click="afterAddProduct = false"
        style="margin-left: 560px"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
      <div class="divqw popup-detail">
        <div class="div-2d">
          <img
            loading="lazy"
            src="https://cdn.builder.io/api/v1/image/assets/TEMP/e8f2d1f13b8ec9969b70e9b0e791eb4958cb760378a08537602787e8bfeefd1b?apiKey=c828819b5944477ab9c72fd951f3ee71&"
            class="imgsd"
          />
          <div class="div-3h" style="color: black">Bạn đã thêm thành công</div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
import axios from "axios";
import { RouterLink } from "vue-router";
import ProductItem from "./ProductItem.vue";
import Paginate from "vuejs-paginate";
import AddProductDialog from "./AddProductDialog.vue";

export default {
  name: "list",
  components: {
    Paginate,
    ProductItem,
    AddProductDialog,
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
    handleSearch() {
      this.page = 1;
      this.getProducts();
    },
    async getProducts() {
      var token = localStorage.getItem("token");
      this.isLoading = true;
      let url = "http://127.0.0.1:8000/api/products";
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
        return this.$route.path === "/product/actions";
      },
    },

    changeDialog() {
      this.isShowDialog = !this.isShowDialog;
    },

    addProduct() {
      const formData = new FormData();
      formData.append("name", this.product.name);
      formData.append("note", this.product.note);
      formData.append("price", this.product.price);
      formData.append("category_id", this.product.category_id);
      formData.append("brand_id", this.product.brand_id);
      formData.append("image", this.product.image);
      let token = localStorage.getItem("token");

      axios
        .post("http://127.0.0.1:8000/api/saveProduct", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
          },
        })
        .then((response) => {
          console.log(response);
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

    searchProducts(query) {
      axios
        .get("http://127.0.0.1:8000/api/search", {
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
      let url = `http://127.0.1:8000/api/delete_products/${this.productIdDelete}`;
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
          this.page = 1;
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
  margin-left: -12px;
}
</style>
