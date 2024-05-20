<template>
  <div class="header-product d-flex justify-content-between">
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

  <!-- productlisst -->

  <ProductItem :products="products"></ProductItem>

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

  <!--popup add product-->
  <v-dialog v-model="isShowDialog" max-width="674">
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
                <!--                <option selected>-&#45;&#45;Chọn-&#45;&#45;</option>-->
                <template v-for="(item, index) in categories" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label mt-4">Hãng sản xuất</label>
              <select class="form-select" v-model="product.brand_id">
                <!--                <option selected>-&#45;&#45;Chọn-&#45;&#45;</option>-->
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
  </v-dialog>

  <!--  popuplogin-->

  <v-dialog v-model="ShowLogin" max-width="674">
    <v-card>
      <v-card-title class="headline">Login</v-card-title>
      <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg">
        <div class="text-subtitle-1 text-medium-emphasis">Account</div>

        <v-text-field
          density="compact"
          placeholder="Email address"
          prepend-inner-icon="mdi-email-outline"
          variant="outlined"
        ></v-text-field>

        <div
          class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
        >
          Password

          <a
            class="text-caption text-decoration-none text-blue"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
          >
            Forgot login password?</a
          >
        </div>

        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          density="compact"
          placeholder="Enter your password"
          prepend-inner-icon="mdi-lock-outline"
          variant="outlined"
          @click:append-inner="visible = !visible"
        ></v-text-field>
        <v-btn class="mb-8" color="blue" size="large" variant="tonal" block>
          Log In
        </v-btn>

        <v-card-text class="text-center">
          <a
            class="text-blue text-decoration-none"
            href="#"
            rel="noopener noreferrer"
            target="_blank"
          >
            Sign up now
            <v-icon icon="mdi-chevron-right"></v-icon>
          </a>
        </v-card-text>
      </v-card>
    </v-card>
  </v-dialog>

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
import ProductItem from "./ProductItem.vue";
import Paginate from "vuejs-paginate";

export default {
  name: "list",
  components: {
    ProductItem,
    Paginate,
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
    handleSearch() {
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

    searchProducts(query) {
      // Kiểm tra xem query có chứa ký tự đặc biệt hay không
      const regex = /[!@#$%^&*(),.?":{}|<>]/;
      if (regex.test(query)) {
        // Hiển thị popup lỗi
        this.showErrorPopup = true;
        return; // Ngưng hàm searchProducts
      }

      // Nếu không có ký tự đặc biệt, thực hiện tìm kiếm bình thường
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
  },
};
</script>

<style scoped>
.custom-search {
  width: 445px !important;
  margin-left: 650px;
}
</style>
