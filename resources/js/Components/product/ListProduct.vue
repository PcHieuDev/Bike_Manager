<template>
  <div class="header-product d-flex justify-content-between">
    <button type="button" @click="changeDialog" class="btn btn-outline-primary">
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
  <div class="product-list">
    <!-- <template v-for="product in products" :key="product.id">
      <router-link :to="`/product/details/${product.id}`" style="text-decoration: none">
        <div class="product-card">
          <img :src="product.image" class="product-image" :alt="product.name"/>
          <div class="product-info">
            <h2 class="product-name">{{ product.name }}</h2>
            <p class="product-description">{{ product.description }}</p>
            <p class="product-price">$ {{ product.price }}</p>
          </div>
        </div>
      </router-link>
    </template> -->
    <template v-for="product in products" :key="product.id">
      <router-link :to="`/product/details/${product.id}`" style="text-decoration: none">
        <div class="column-4">
          <div class="div-19">
            <img
              :src="product.image"
              style="height: 146px; width: 230px"
              class="product-image"
            />
            <div class="product-details">
              <span class="product-name">{{ product.name }}</span>
              <span class="product-price">$ {{ product.price }}</span>
            </div>
          </div>
        </div>
      </router-link>
    </template>
  </div>

  <div class="text-xs-center">
    <v-pagination v-model="page" :length="countRercord" :total-visible="5"></v-pagination>
  </div>

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

        <!--        <v-card-->
        <!--            class="mb-12"-->
        <!--            color="surface-variant"-->
        <!--            variant="tonal"-->
        <!--        >-->
        <!--          <v-card-text class="text-medium-emphasis text-caption">-->
        <!--            Warning: After 3 consecutive failed login attempts, you account will be temporarily locked for three hours.-->
        <!--            If you must login now, you can also click "Forgot login password?" below to reset the login password.-->
        <!--          </v-card-text>-->
        <!--        </v-card>-->

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

  <!--  popupAddCucces-->
  <!-- <v-dialog v-model="addCucces">
   <v-card>
    <div class="divab">
    <img style="  aspect-ratio: 1;
    object-fit: auto;  object-position: center;
    width: 24px;
";
      loading="lazy"
      src="https://cdn.builder.io/api/v1/image/assets/TEMP/30a3d4b5ef0147f880c3aef85a902b777a0d1145376670060bcd0eda4376081f?"
      class="img"
    /><img
      loading="lazy"
      src="https://cdn.builder.io/api/v1/image/assets/TEMP/6998beae4086ea2ecf93be1cb4bac64c753c10c053a00ac87dcfeb4109d1c78b?"
      class="img-2"
    />
    <div class="div-2ab">Thêm thành công!</div>
  </div>

   </v-card>

    </v-dialog> -->

  <!-- popup tim kiem -->

  <!-- <v-dialog v-model="showErrorPopup">
    <v-card>
      <div class="text-center">
        <div class="error-message">Không được nhập ký tự đặc biệt vào ô tìm kiếm!</div>
        <v-btn color="primary" @click="showErrorPopup = false">Đóng</v-btn>
      </div>
    </v-card>
  </v-dialog> -->

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
import Paginate from "vuejs-paginate";

export default {
  name: "list",
  components: {
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

  created() {
    this.getProducts();
  },
  mounted() {
    this.getListCategory();
    this.getListBrands();
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
      let url = "http://127.0.0.1:8000/api/products";
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

    addProduct() {
      const formData = new FormData();
      formData.append("name", this.product.name);
      formData.append("note", this.product.note);
      formData.append("price", this.product.price);
      formData.append("category_id", this.product.category_id);
      formData.append("brand_id", this.product.brand_id);
      formData.append("image", this.product.image);
      axios
        .post("http://127.0.0.1:8000/api/saveProduct", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
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
          // // this.addCucces = true; after 1s ?
          //   setTimeout(() => {
          //   this.addCucces = false;
          // }, 1000);
          this.page = 1;
          this.getProducts();
        });
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
