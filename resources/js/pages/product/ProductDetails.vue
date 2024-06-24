<template>
  <div class="div">
    <div class="div-2">
      <div class="div-3" v-if="product">
        <div class="column">
          <div class="div-4">
            <div class="div-5">
              <img
                @click="goBack"
                style="cursor: pointer"
                loading="lazy"
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/13a5a5010bdb4b849c8dcb32751d5eef59a519570bde9058db6c4e2cefa5f4ad?"
                class="img"
              />
              <div class="div-6" @click="goBack">Quay lại</div>
            </div>
            <div class="div-7">{{ product.name }}</div>
            <div class="div-8">
              Danh mục: {{ product.category ? product.category.name : "" }}
            </div>
            <div class="div-9">
              Hãng sản xuất: {{ product.brand ? product.brand.name : "" }}
            </div>
            <div class="div-10">giá sản phẩm: {{ product.price }} $</div>
            <div class="div-11">Mô tả sản phẩm:</div>
            <div class="div-12">
              {{ product.note }}
            </div>
          </div>
        </div>
        <div class="column-2">
          <div>
            <template v-if="imageDetails.length">
              <v-carousel :show-arrows="false" class="carousel-tong" cycle>
                <v-carousel-item
                  v-for="(item, i) in imageDetails"
                  :key="i"
                  :src="item.image_path"
                  cover
                  class="carousel-image"
                ></v-carousel-item>
              </v-carousel>
            </template>
            <template v-else>
              <img :src="product.image" alt="Product Image" class="main-image" />
            </template>
          </div>
        </div>
      </div>
      <div v-else class="no-product">không có sản phẩm</div>
    </div>
    <div class="div-13" v-if="products.length">gợi ý cho bạn:</div>
    <div class="container" v-if="products.length">
      <div class="product-list">
        <template v-for="item in randomProducts" :key="item.id">
          <router-link :to="`/product/details/${item.id}`" style="text-decoration: none">
            <ProductItem
              :product="item"
              :showPrice="true"
              :showButton="false"
            ></ProductItem>
          </router-link>
        </template>
      </div>
    </div>
  </div>
  <div v-if="isLoading" class="loading-page">
    <v-progress-circular indeterminate color="primary"></v-progress-circular>
  </div>
</template>

<script>
import "vue-slick-carousel/dist/vue-slick-carousel.css";
import "vue-slick-carousel/dist/vue-slick-carousel-theme.css";
import VueSlickCarousel from "vue-slick-carousel";
import VueCarousel from "vue-carousel";
// import apiClient from "../../axios-interceptor";
import ProductItem from "../../Components/Product/ProductItem.vue";
import { BASE_URL } from "./../../baseUrl.js";

import axios from "axios";
export default {
  components: {
    VueSlickCarousel,
    VueCarousel,
    ProductItem,
  },
  data() {
    return {
      brandId: null,
      item: null,
      isLoading: false,
      itemsPerPage: 12,
      countRercord: 0,
      productId: this.$route.params.id,
      products: [],
      product: null,
      categories: [],
      brands: [],
      imageDetails: [],
    };
  },
  mounted() {
    this.fetchProductDetails();
  },
  watch: {
    "$route.params.id": function (newId, oldId) {
      if (newId !== oldId) {
        this.fetchProductDetails();
      }
    },
  },
  computed: {
    randomProducts() {
      let productsCopy = [...this.products];
      let result = [];
      for (let i = 0; i < 3; i++) {
        if (productsCopy.length > 0) {
          let randomIndex = Math.floor(Math.random() * productsCopy.length);
          result.push(productsCopy[randomIndex]);
          productsCopy.splice(randomIndex, 1);
        }
      }
      return result;
    },
  },
  created() {
    this.getProducts();
  },
  methods: {
    goBack() {
      this.$router.go(-1);
      this.imageDetails = []; // Cập nhật trực tiếp
    },
    fetchProductDetails() {
      const id = this.$route.params.id;
      this.isLoading = true;
      axios
        .get(BASE_URL + `products/${id}`)
        .then((response) => {
          if (response.data) {
            this.product = response.data.product || null;
            this.imageDetails = response.data.imageDetails || [];
          } else {
            console.log("No data returned from API");
            this.product = null;
            this.imageDetails = [];
          }
        })
        .catch((error) => {
          console.error(error);
          this.product = null;
          this.imageDetails = [];
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    async getProducts() {
      const id = this.$route.params.id;
      this.isLoading = true;
      axios
        .get(BASE_URL + `products/${id}`)
        .then((response) => {
          if (response.data) {
            this.products = response.data.products || [];
            this.imageDetails = response.data.imageDetails || [];
            console.log("image Slide", this.imageDetails);
          } else {
            console.log("No data returned from API");
            this.products = [];
            this.imageDetails = [];
          }
        })
        .catch((error) => {
          console.error(error);
          this.products = [];
          this.imageDetails = [];
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
  beforeRouteUpdate(to, from, next) {
    this.productId = to.params.id;
    this.fetchProductDetails(); // Gọi fetchProductDetails khi chuyển đổi giữa các sản phẩm
    next();
  },
};
</script>

<style scoped>
.no-product {
  font-size: 24px;
  text-align: center;
  margin-top: 20px;
}
.div {
  max-width: 1080px;
}
.carousel-tong {
  width: 510px !important;
  height: 316px !important;
}

.carousel-image img {
  width: 510px !important;
  height: 316px !important;
  object-fit: cover;
  /* Đảm bảo ảnh được phủ kín mà không làm mất tỉ lệ */
}

.product-list {
  padding-left: 0px;
  gap: 35px;
}

.container {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  /* Optional: space items evenly */
  padding-top: 5px;
}

.div-14 {
  flex: 1;
  /* Adjust width of each item as needed */
  margin: 0 2px;
  /* Optional: add space between items */
}

.div-15,
.column-3,
.div-16 {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.main-image {
  width: 510px;
  height: auto;
}

/* Điều chỉnh cho màn hình dưới 768px */
@media (max-width: 768px) {
  .carousel-tong,
  .carousel-image img,
  .main-image {
    width: 100% !important; /* Cho phép carousel và hình ảnh chính chiếm toàn bộ chiều rộng */
    height: auto !important; /* Điều chỉnh chiều cao tự động */
  }

  .container {
    flex-direction: column; /* Chuyển container sang hiển thị theo cột */
  }

  .product-list,
  .div-13,
  .div {
    padding: 0 10px; /* Thêm padding vào hai bên để không bị sát mép */
  }
}

/* Điều chỉnh cho màn hình dưới 480px */
@media (max-width: 480px) {
  .div-7,
  .div-8,
  .div-9,
  .div-10,
  .div-11,
  .div-12 {
    font-size: 14px; /* Giảm kích thước font cho các phần tử văn bản */
  }

  .div-6,
  .div-13 {
    font-size: 16px; /* Giảm kích thước font cho tiêu đề */
  }
}
</style>
