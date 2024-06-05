<template>
  <div v-if="showNavbar" class="filter">
    <div class="category">
      <div class="category-header" @click="toggleBrands">
        <div class="text">Hãng Sản Xuất</div>
        <img
          class="ic-arrow-top"
          :class="{ rotated: showBrands }"
          alt="Ic arrow top"
          src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top.svg"
        />
      </div>
      <div v-if="showBrands" class="brands-list">
        <div v-for="(brand, index) in brands" :key="index" class="brand-item">
          {{ brand.name }}
        </div>
      </div>
    </div>
    <img class="line" alt="Line" src="https://c.animaapp.com/6fNIys5x/img/line-1.svg" />

    <div class="category-2">
      <div class="category-header" @click="toggleCategories">
        <div class="text">Danh Mục</div>
        <img
          class="ic-arrow-top"
          :class="{ rotated: showCategories }"
          alt="Ic arrow top"
          src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top-1.svg"
        />
      </div>
      <div v-if="showCategories" class="categories-list">
        <div
          v-for="(category, index) in categories"
          :key="index"
          class="category-item"
          @click="selectCategory(category.id)"
        >
          {{ category.name }}
        </div>
      </div>
      <!-- <div class="all-categories" @click="selectCategory(null)">Tất Cả</div> -->
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Filter",
  data() {
    return {
      categories: [],
      brands: [],
      showCategories: true,
      showBrands: false,
    };
  },
  computed: {
    showNavbar() {
      return this.$route.path !== "/Login" && this.$route.path !== "/Register";
    },
  },
  mounted() {
    this.getListCategory();
    this.getListBrand();
    this.showBrands = true;
  },
  methods: {
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
    getListBrand() {
      axios
        .get("http://127.0.0.1:8000/api/brands")
        .then((response) => {
          this.brands = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    selectCategory(categoryId) {
      this.$emit("selectCategory", categoryId);
    },
    toggleCategories() {
      this.showCategories = !this.showCategories;
    },
    toggleBrands() {
      this.showBrands = !this.showBrands;
    },
  },
};
</script>

<style scoped>
.filter {
  background-color: #ffffff;
  height: 100vh; /* Full viewport height */
  position: fixed; /* Fixed position */
  top: 0; /* Align to the top */
  left: 0; /* Align to the left */
  width: 269px; /* Width of the sidebar */
  overflow-y: auto; /* Enable vertical scrolling if the content exceeds the height */
  border-right: 1px solid #e0e0e0; /* Right border */
  padding: 20px;
  top: 25px;
  padding-top: 64px; /* Add top padding to account for the top bar */
}

.category,
.category-2 {
  margin-bottom: 20px; /* Space between categories */
}

.category-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  cursor: pointer;
}

.category-header .text {
  font-size: 16px;
  font-weight: 700;
  color: #000;
}

.ic-arrow-top {
  transition: transform 0.3s;
}

.ic-arrow-top.rotated {
  transform: rotate(180deg);
}

.brands-list,
.categories-list {
  margin-top: 10px;
}

.brand-item,
.category-item,
.all-categories {
  padding: 10px 0;
  font-size: 16px;
  font-weight: 400;
  color: #000;
  cursor: pointer;
}

.brand-item:hover,
.category-item:hover,
.all-categories:hover {
  color: var(--blue);
}

.line {
  width: 100%;
  margin: 20px 0;
  object-fit: cover;
}
</style>
