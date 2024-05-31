<template>
  <div class="div">
    <div class="div-2">
      <div class="column">
        <div class="div-3">
          <div class="div-4x" @click="$router.go(-1)">Quay Lại</div>
          <div class="div-4">Thông tin sản phẩm</div>
          <div class="form-group">
            <label>Tên sản phẩm</label>
            <input
              type="text"
              class="form-control"
              v-model="product.name"
              placeholder="Enter name"
            />
          </div>
          <div class="form-group">
            <label>Danh mục sản phẩm</label>
            <select class="form-select" v-model="product.category_id">
              <template v-for="(item, index) in categories" :key="index">
                <option :value="item.id">{{ item.name }}</option>
              </template>
            </select>
          </div>
          <div class="form-group">
            <label>Hãng sản xuất</label>
            <select class="form-select" v-model="product.brand_id">
              <template v-for="(item, index) in brands" :key="index">
                <option :value="item.id">{{ item.name }}</option>
              </template>
            </select>
          </div>
          <div class="form-group">
            <label>Giá</label>
            <input
              type="text"
              class="form-control"
              v-model="product.price"
              placeholder="Enter price"
            />
          </div>
          <div class="form-group">
            <label>Mô tả</label>
            <textarea
              class="form-control"
              id="exampleFormControlTextarea1"
              v-model="product.note"
              rows="3"
            ></textarea>
          </div>
          <div class="div-17">
            <button class="div-18">Hủy</button>
            <button @click="updateProduct" class="div-19">Lưu</button>
          </div>
        </div>
      </div>
      <div class="column-2">
        <div class="div-20">
          <div class="div-21">
            Ảnh minh họa
            <span style="color: rgba(255, 77, 77, 1)">*</span>
            <img
              :src="product.image"
              class="w-100 shadow-1-strong rounded mb-4"
              @change="onImageChange"
            />
          </div>
          <div class="div-22">
            <div class="row p-4">
              <!-- image detail -->
              <div class="col-lg-6 col-md-12 mb-6 mb-lg-0">
                <span>Ảnh 1</span>
                <div class="image-detail">
                  <img
                    :src="imagePreview || placeholderImage"
                    @click="triggerFileInput"
                    class="w-100 shadow-1-strong rounded mb-4"
                    
                  />
                  <input
                    type="file"
                    ref="fileInput"
                    @change="onImageChange"
                    style="display: none"
                  />
                  <div class="button-hover" v-if="imagePreview">
                    <div>
                      <button @click="updateImage">Cập nhật</button>
                    </div>
                    <div>
                      <button @click="removeImage">Xóa</button>
                    </div>
                  </div>
                </div>
              </div>

              <!-- button add image -->
              <!-- <div class="col-lg-6 col-md-12 mb-6 mb-lg-0 custom-btn-add-img">
                <div class="file-input">
                  <input
                    type="file"
                    name="file-input"
                    id="file-input"
                    class="file-input__input"
                  />
                  <label class="file-input__label" for="file-input">
                    <img src="/storage/images/inputfile.png" alt="Boat on Calm Water"
                  /></label>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import UpdateproductSuccess from "../Popup/UpdateProduct/UpdateproductSuccess.vue";
import { BASE_URL } from "../../configUrl.js";

export default {
  name: "EditProduct",
  components: {
    Swiper,
    SwiperSlide,
    UpdateproductSuccess,
  },
  data() {
    return {
      product: {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
        image1: "",
      },
      placeholderImage: "/storage/images/input.jpg", // Ảnh placeholder
      imagePreview: "", // Biến tạm thời để lưu ảnh xem trước
      categories: [],
      brands: [],
      productId: this.$route.params.id,
    };
  },
  mounted() {
    this.getListCategory();
    this.getListBrands();
    this.getProductDetails();
  },
  methods: {
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    onImageChange(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.imagePreview = e.target.result; // Hiển thị ảnh xem trước
        };
        reader.readAsDataURL(file);
      }
    },
    updateImage() {
      this.imagePreview = null;
      this.imageFile = null;
      this.triggerFileInput();
    },
    removeImage() {
      this.imagePreview = ""; // Xóa ảnh xem trước
      this.$refs.fileInput.value = null; // Reset input file
    },

    goHome() {
      this.$router.push("/");
    },
    methods: {
      updateProduct() {
        let url = BASE_URL + `products/${this.productId}`;
        let token = localStorage.getItem("token");
        let formData = new FormData();
        formData.append("name", this.product.name);
        formData.append("price", this.product.price);
        formData.append("note", this.product.note);
        formData.append("category_id", this.product.category_id);
        formData.append("brand_id", this.product.brand_id);
        if (this.product.image) {
          formData.append("image", this.product.image);
        }

        axios
          .put(url, formData, {
            headers: {
              Authorization: `Bearer ${token}`,
              "Content-Type": "multipart/form-data",
            },
          })
          .then((response) => {
            if (response.data.success) {
              console.log("Update success");
              this.afterUpdateProduct = true;
              // You can add any additional actions on successful update
            } else {
              console.log("Update failed");
              // Handle update failure
            }
          })
          .catch((error) => {
            console.log(error);
            // Handle request error
          });
      },
      // other methods...
    },

    getProductDetails() {
      const id = this.$route.params.id; // lấy id từ route
      axios
        .get(BASE_URL + `products/${id}`)
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
  },
};
</script>

<style scoped>
.image-detail {
  position: relative;
  transition: 0.3s all;
}

.image-detail .button-hover {
  display: none;
  text-align: center;
  position: absolute;
  left: 45px;
  top: 23px;
  transition: 0.3s all;
}

.image-detail:hover .button-hover {
  display: block;
}

.image-detail .button-hover button:first-child {
  margin-bottom: 5px;
}

.image-detail .button-hover button {
  border: 1px solid #333;
  padding: 5px 10px;
  border-radius: 5px;
  background-color: #ffffff80;
}

.file-input__input {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}

.file-input__label {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
}

.custom-btn-add-img {
  display: flex;
  justify-content: center;
  align-items: center;
}

.custom-btn-add-img img {
  width: 50px;
  cursor: pointer;
}

.div {
  border-radius: 20px;
  background-color: #fff;
  max-width: 981px;
  padding: 20px 20px;
}

@media (max-width: 991px) {
  .div {
    padding: 0 20px;
  }
}

.div-2 {
  gap: 20px;
  display: flex;
}

@media (max-width: 991px) {
  .div-2 {
    flex-direction: column;
    align-items: stretch;
  }
}

.column {
  display: flex;
  flex-direction: column;
  line-height: normal;
  width: 50%;
}

@media (max-width: 991px) {
  .column {
    width: 100%;
  }
}

.div-3 {
  display: flex;
  flex-direction: column;
  font-size: 14px;
  font-weight: 400;
  letter-spacing: 0.35px;
  line-height: 150%;
}

@media (max-width: 991px) {
  .div-3 {
    max-width: 100%;
    margin-top: 40px;
  }
}

.div-4 {
  color: var(--Neutral-Darkest-Grey, #171b2f);
  font: 600 18px Open Sans, sans-serif;
}

@media (max-width: 991px) {
  .div-4 {
    max-width: 100%;
  }
}

.div-4x {
  color: var(--Neutral-Darkest-Grey, #22dae0);
  font: 600 18px Open Sans, sans-serif;
  cursor: pointer;
}

.div-17 {
  display: flex;
  gap: 20px;
  font-weight: 500;
  white-space: nowrap;
  text-align: center;
  line-height: 170%;
  justify-content: space-between;
  margin: 1px 54px 0;
}

@media (max-width: 991px) {
  .div-17 {
    white-space: initial;
    margin: 40px 10px 0;
  }
}

.div-18 {
  font-family: Inter, sans-serif;
  border-radius: 4px;
  border-color: rgba(0, 204, 255, 1);
  border-style: solid;
  border-width: 1px;
  align-items: center;
  color: var(--Primary, #0cf);
  justify-content: center;
  padding: 17px 60px;
  width: 160px;
  height: 50px;
}

@media (max-width: 991px) {
  .div-18 {
    white-space: initial;
    padding: 0 20px;
  }
}
.div-19 {
  font-family: Inter, sans-serif;
  border-radius: 4px;
  border-color: rgba(0, 204, 255, 1);
  border-style: solid;
  border-width: 1px;
  align-items: center;
  color: rgb(253, 253, 253);
  justify-content: center;
  padding: 17px 60px;
  width: 160px;
  height: 50px;
  margin-top: 10px;
  background-color: rgb(0, 204, 250);
}

@media (max-width: 991px) {
  .div-19 {
    white-space: initial;
    padding: 0 20px;
  }
}

.column-2 {
  display: flex;
  flex-direction: column;
  line-height: normal;
  width: 50%;
  margin-left: 20px;
}

@media (max-width: 991px) {
  .column-2 {
    width: 100%;
  }
}

.div-20 {
  display: flex;
  flex-grow: 1;
  flex-direction: column;
}

@media (max-width: 991px) {
  .div-20 {
    max-width: 100%;
    margin-top: 40px;
  }
}

.div-21 {
  letter-spacing: 0.35px;
  font: 400 16px/150% Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-21 {
    max-width: 100%;
  }
}

@media (max-width: 991px) {
  .div-22 {
    max-width: 100%;
  }
}

:root {
  --Neutral-Darkest-Grey: #171b2f;
  --Neutral-Darker-Grey: #343747;
  --Neutral-Grey: #bcbec6;
  --Primary: #ffffff;
  --Red: #ff4d4d;
}
.image-detail {
  position: relative;
  transition: 0.3s all;
}

.image-detail .button-hover {
  display: none;
  text-align: center;
  position: absolute;
  left: 45px;
  top: 23px;
  transition: 0.3s all;
}

.image-detail:hover .button-hover {
  display: block;
}

.image-detail .button-hover button:first-child {
  margin-bottom: 5px;
}

.image-detail .button-hover button {
  border: 1px solid #333;
  padding: 5px 10px;
  border-radius: 5px;
  background-color: #ffffff80;
}

.file-input__input {
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;
  position: absolute;
  z-index: -1;
}

.file-input__label {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
}

.custom-btn-add-img {
  display: flex;
  justify-content: center;
  align-items: center;
}

.custom-btn-add-img img {
  width: 50px;
  cursor: pointer;
}
</style>
