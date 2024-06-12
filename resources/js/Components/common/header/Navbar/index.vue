<template>
  <div>
    <div class="sidebar-toggle" @click="toggleSidebar" v-if="!isDesktop">
      <img alt="Menu" src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top.svg" />
    </div>

    <div v-if="showNavbar && showSidebar" class="filter">
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
          <div
            v-for="(brand, index) in brands"
            :key="index"
            class="brand-item bold-text"
            @click="selectBrand(brand.id)"
            :class="{ selected: selectedBrandId === brand.id }"
          >
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
            v-for="category in categories"
            :key="category.id"
            class="category-item bold-text"
            @click="selectCategory(category.id)"
            :class="{ selected: selectedCategoryId === category.id }"
          >
            {{ category.name }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { mapActions } from "vuex";
import { BASE_URL } from "../../../../configUrl";
import { useToast } from "vue3-toastify";

const apiClient = axios.create({
  baseURL: BASE_URL,
});

// Thêm response interceptor
apiClient.interceptors.response.use(
  (response) => {
    // Nếu response không phải lỗi, chỉ cần trả về response
    return response;
  },
  (error) => {
    // Nếu có lỗi, xử lý lỗi
    console.error(error);
    const toast = useToast();

    toast.error("An error occurred while fetching data.");
    throw error;
  }
);
export default {
  name: "Filter",
  data() {
    return {
      categories: [],
      brands: [],
      brandId: null,
      categoryId: null,
      showCategories: true,
      showBrands: false,
      showSidebar: false,
      isDesktop: window.innerWidth >= 1024,
      selectedBrandId: null, // Biến lưu trạng thái của brand được chọn
      selectedCategoryId: null, // Biến lưu trạng thái của category được chọn
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
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },
  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  },
  methods: {
    ...mapActions(["setBrandId", "fetchProducts","fetchProductsAction", "setCategoryId"]),
    async getListCategory() {
      try {
        const response = await apiClient.get("categories");
        this.categories = response.data.contents;
      } catch (error) {}
    },
    async getListBrand() {
      try {
        const response = await apiClient.get("brands");
        this.brands = response.data.contents;
      } catch (error) {}
    },
    selectCategory(categoryId) {
      console.log(categoryId);
      this.$router.push({ path: '/', query: { categoryId } });

      this.selectedCategoryId = categoryId; // Gán giá trị cho biến lưu trạng thái của category được chọn
      this.setCategoryId(categoryId);

      // this.fetchProducts(null, categoryId);
    },
    toggleCategories() {
      this.showCategories = !this.showCategories;
    },
    toggleBrands() {
      this.showBrands = !this.showBrands;
    },
    toggleSidebar() {
      this.showSidebar = !this.showSidebar;
    },
    handleResize() {
      this.isDesktop = window.innerWidth >= 1024;
      if (this.isDesktop) {
        this.showSidebar = true;
      } else {
        this.showSidebar = false;
      }
    },
    selectBrand(brandId) {
      console.log("Selected brandId:", brandId);
      this.selectedBrandId = brandId; // Gán giá trị cho biến lưu trạng thái của brand được chọn
      this.$router.push({ path: '/', query: { brandId } });
      this.setBrandId(brandId);
      
      // this.fetchProducts(brandId);
    },
  },
};
</script>

<style scoped>
.filter {
  background-color: #ffffff;
  height: 100vh;
  /* Full viewport height */
  position: fixed;
  /* Fixed position */
  top: 0;
  /* Align to the top */
  left: 0;
  /* Align to the left */
  width: 269px;
  /* Width of the sidebar */
  overflow-y: auto;
  /* Enable vertical scrolling if the content exceeds the height */
  border-right: 1px solid #e0e0e0;
  /* Right border */
  padding: 20px;
  padding-top: 64px;
  /* Add top padding to account for the top bar */
  z-index: 1000;
  margin-top: 64px;
  /* Ensure the sidebar is on top of other elements */
}

.category,
.category-2 {
  margin-bottom: 20px;
  /* Space between categories */
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
.selected {
  color: var(--blue); /* Thay đổi màu thành màu xanh đậm */
}
.bold-text {
  font-weight: bold; /* Đặt kiểu chữ là đậm */
}

/* Responsive Styles */
@media (max-width: 1023px) {
  .filter {
    width: 80%;
    /* Sidebar takes up 80% width on smaller screens */
    transform: translateX(-100%);
    transition: transform 0.3s;
  }

  .filter.show {
    transform: translateX(0);
  }

  .sidebar-toggle {
    position: fixed;
    top: 10px;
    left: 10px;
    cursor: pointer;
    z-index: 1001;
    /* Above the sidebar */
  }
}
</style>
