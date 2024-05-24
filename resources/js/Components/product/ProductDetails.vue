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
              Danh mục:
              {{ product.category ? product.category.name : "" }}
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
          <img :src="product.image" loading="lazy" class="mg-2" />
        </div>
      </div>
    </div>
    <div class="div-13">gợi ý cho bạn:</div>
    <div class="div-14">
      <div class="div-15">
        <div class="column-3">
          <div class="div-16">
            <img
              style="height: 146px; width: 237px"
              loading="lazy"
              :src="product.image"
              class="mg-3"
            />
            <div class="div-17">Multistrada V4 Pikes Peak</div>
            <div class="div-18">$ 100,000,000</div>
          </div>
        </div>
        <div class="column-4">
          <div class="div-19">
            <img
              loading="lazy"
              style="height: 146px; width: 237px"
              :src="product.image"
              class="mg-4"
            />
            <div class="div-20">Multistrada V4 Pikes Peak</div>
            <div class="div-21">$ 100,000,000</div>
          </div>
        </div>

        <div class="column-4">
          <div class="div-19">
            <img style="height: 146px; width: 237px" :src="product.image" class="mg-4" />
            <div class="div-20">Multistrada V4 Pikes Peak</div>
            <div class="div-21">$ 100,000,000</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      product: [],
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

      productId: this.$route.params.id,
    };
  },
  mounted() {
    this.getProductDetails();
  },

  methods: {
    goHome() {
      this.$router.push("/");
    },

    getProductDetails() {
      const id = this.$route.params.id; // lấy id từ route
      axios
        .get(`http://127.0.0.1:8000/api/products/${id}`)
        .then((response) => {
          if (response.data) {
            this.product = response.data.product;
            console.log(this.product.category.name);
            console.log(this.product);
          } else {
            // Xử lý trường hợp dữ liệu trả về null ở đây
            console.log("No data returned from API");
          }
        })
        .catch((error) => {
          console.error(error);
        });
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
  },
};
</script>
