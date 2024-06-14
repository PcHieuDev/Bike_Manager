<template>
  <div class="div">
    <div class="div-2">
      <div class="div-3">
        <div class="column">
          <div class="div-4">
            <div class="div-5">
              <img
                @click="goHome"
                style="cursor: pointer"
                loading="lazy"
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/13a5a5010bdb4b849c8dcb32751d5eef59a519570bde9058db6c4e2cefa5f4ad?"
                class="img"
              />
              <div class="div-6" @click="goHome">Quay lại</div>
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
        </div>
      </div>
    </div>
    <div class="div-13">gợi ý cho bạn:</div>
    <div class="container">
      <div class="product-list">
        <template v-for="item in randomProducts" :key="item.id">
          <router-link :to="`/product/details/${item.id}`" style="text-decoration: none">
            <div class="column-4">
              <div class="div-19">
                <img
                  :src="item.image"
                  style="height: 140px; width: 227px"
                  class="product-image"
                />
                <div class="product-details">
                  <span class="product-name">{{ item.name }}</span>
                  <span class="product-price">$ {{ item.price }}</span>
                </div>
              </div>
            </div>
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
import axios from "axios";
import "vue-slick-carousel/dist/vue-slick-carousel.css";
import "vue-slick-carousel/dist/vue-slick-carousel-theme.css";
import VueSlickCarousel from "vue-slick-carousel";
import VueCarousel from "vue-carousel";
import apiClient from "../../axios-interceptor";

export default {
  components: {
    VueSlickCarousel,
    VueCarousel,
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
    goHome() {
      this.$router.push("/");
    },
    fetchProductDetails() {
      this.imageDetails = [];
      const id = this.$route.params.id;
      this.isLoading = true;
      apiClient
        .get(`products/${id}`)
        .then((response) => {
          if (response.data) {
            this.product = response.data.product;
          } else {
            console.log("No data returned from API");
          }
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
    async getProducts() {
      const id = this.$route.params.id;
      this.isLoading = true;
      apiClient
        .get(`products/${id}`)
        .then((response) => {
          if (response.data) {
            this.products = response.data.products;
            this.imageDetails = response.data.imageDetails;
            console.log("dfdjsfjdsf", this.imageDetails);
          } else {
            console.log("No data returned from API");
          }
        })
        .catch((error) => {
          console.error(error);
        })
        .finally(() => {
          this.isLoading = false;
        });
    },
  },
  beforeRouteUpdate(to, from, next) {
    this.productId = to.params.id;
    this.fetchProductDetails();
    next();
  },
};
</script>

<style scoped>
.carousel-tong {
  width: 510px;
  height: 316px !important;
}
.carousel-image img {
  width: 510px !important;
  height: 316px !important;
  object-fit: cover; /* Đảm bảo ảnh được phủ kín mà không làm mất tỉ lệ */
}

.product-list {
  padding-left: 0px;
}
.container {
  display: flex;
  flex-direction: row;
  justify-content: space-between; /* Optional: space items evenly */
  padding-top: 5px;
}
.div-14 {
  flex: 1; /* Adjust width of each item as needed */
  margin: 0 2px; /* Optional: add space between items */
}
.div-15,
.column-3,
.div-16 {
  display: flex;
  flex-direction: column;
  align-items: center;
}

</style>
