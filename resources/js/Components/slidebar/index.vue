<template>
  <div class="filter">
    <div class="category">
      <div class="t-t-c">Tất Cả</div>
      <img
        class="ic-arrow-top"
        alt="Ic arrow top"
        src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top.svg"
      />

      <div class="overlap-group-2">
        <div v-if="showBrands">
          <div v-for="(brand, index) in brands" :key="index" class="mvnvbnv">
            {{ brand.name }}
          </div>
        </div>
        <div class="text-wrapper-5">Hãng Sản Xuất</div>
      </div>
    </div>
    <img class="line" alt="Line" src="https://c.animaapp.com/6fNIys5x/img/line-1.svg" />

    <div class="category-2">
      <div class="overlap-group-2">
        <div class="text-wrapper-5">Danh Mục</div>
      </div>
      <div
        v-for="(category, index) in categories"
        :key="index"
        v-if="showCategories"
        class="category-item"
      >
        <div class="t">
          {{ category.name }}
        </div>
      </div>
      <div class="t-t-c">Tất Cả</div>
      <img
        class="ic-arrow-top"
        alt="Ic arrow top"
        style="cursor: pointer"
        src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top-1.svg"
        @click="toggleCategories"
      />
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { get } from "lodash";
export default {
  name: "Filter",
  data() {
    return {
      categories: [], // Khai báo mảng categories để lưu danh sách danh mục
      // brands: [],
      // showBrands: false,

      showCategories: true,
    };
  },
  mounted() {
    this.getListCategory(); // Gọi hàm getListCategory khi component được mount
    // this.brands = response.data;
    // this.getListBrands();
  },
  methods: {
    getListCategory() {
      axios
        .get("http://127.0.0.1:8000/api/categories")
        .then((response) => {
          this.categories = response.data.contents; // Gán dữ liệu categories từ API
        })
        .catch((error) => {
          console.log(error);
        });
    },
    // getListBrands() {
    //   axios
    //     .get("http://127.0.0.1:8000/api/brands")
    //     .then((response) => {
    //       this.brands = response.data.contents;
    //     })
    //     .catch((error) => {
    //       console.log(error);
    //     });
    // },

    selectCategory(categoryId) {
      this.$emit("selectCategory", categoryId);
    },
    toggleCategories() {
      this.showCategories = !this.showCategories;
    },
    // toggleBrands() {
    //   this.showBrands = !this.showBrands;
    // },
  },
};
</script>

<style>
.filter {
  background-color: #ffffff;
  height: 916px;
  left: 0;
  display: flex;
  position: absolute;
  top: 64px;
  width: 269px;
}

.filter .category {
  height: 84px;
  left: 35px;
  position: absolute;
  top: 318px;
  width: 201px;
}
.filter .category-item {
  position: relative;
}
.mvnvbnv {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 1px;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
}
.filter .t-t-c {
  color: var(--blue);
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 700;
  height: 42px;
  left: 0;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 42px;
  width: 197px;
}

.filter .overlap-group-2 {
  height: 42px;
  left: 0;
  position: absolute;
  top: 0;
  width: 197px;
}

.filter .text-wrapper-5 {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 0;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 0;
  width: 197px;
}

.filter .ic-arrow-top {
  height: 16px;
  left: 167px;
  position: absolute;
  top: 10px;
  width: 16px;
}

.filter .line {
  height: 1px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 275px;
  width: 269px;
}

.filter .category-2 {
  height: 210px;
  left: 43px;
  position: absolute;
  top: 38px;
  width: 208px;
  height: auto;
}

.filter .t {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 1px;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 84px;
  width: 197px;
  cursor: pointer;
}

.filter .category-item .t {
  position: relative;
  left: 0;
  width: 100%; /* Phần tử con chiếm toàn bộ chiều rộng của phần tử cha */
}
</style>
