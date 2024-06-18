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
            <select class="form-select" v-model="product.brand_id">
              <template v-for="(item, index) in brands" :key="index">
                <option :value="item.id">{{ item.name }}</option>
              </template>
            </select>
          </div>
          <div class="form-group">
            <label>Hãng sản xuất</label>
            <select class="form-select" v-model="product.category_id">
              <template v-for="(item, index) in categories" :key="index">
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
            <button class="div-18" @click="resetForm">Hủy</button>
            <button @click="updateProduct" class="div-19">Lưu</button>
          </div>
        </div>
      </div>
      <div class="column-2">
        <div class="div-20">
          <div class="div-21">
            Ảnh minh họa
            <span style="color: rgba(255, 77, 77, 1)">*</span>
            <div class="image-detail">
              <img
                :src="imagePreview || initialImage"
                class="fixed-size-image shadow-1-strong rounded mb-4"
                @click="triggerFileInputMainImage"
              />
              <input
                type="file"
                ref="mainFileInput"
                @change="onMainImageChange"
                style="display: none"
              />
              <div class="button-hover">
                <div>
                  <button v-if="!product.image" @click="triggerFileInputMainImage">
                    Cập nhật
                  </button>
                  <button v-if="product.image" @click="triggerFileInputMainImage">
                    Cập nhật
                  </button>
                  <button v-if="product.image" @click="removeMainImage">Xóa</button>
                </div>
              </div>
            </div>
          </div>
          <div class="div-22">
            <div class="row p-4">
              <!-- image detail -->
              <div
                v-for="(item, index) in imageDetail"
                class="col-lg-6 col-md-12 mb-6 mb-lg-0"
              >
                <span>Ảnh {{ index + 1 }}</span>
                <div class="image-detail">
                  <img
                    :src="item.image ? showImageDetail(index) : placeholderImage"
                    @click="triggerFileInput(index)"
                    :class="[
                      'small-image',
                      item.image ? 'loaded-image' : 'placeholder-image',
                    ]"
                    class="shadow-1-strong rounded mb-4"
                  />
                  <input
                    type="file"
                    :ref="'fileInput' + index"
                    @change="onImageChange(index, $event)"
                    style="display: none"
                  />
                  <div class="button-hover" v-if="item.image">
                    <div>
                      <button @click="updateImage(index)">Cập nhật</button>
                    </div>
                    <div>
                      <button @click="removeImage(index)">Xóa</button>
                      <!-- <button @click="showModalDelete">Xóa</button> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <UpdatePopupSuccess v-model="UpdateSuccess" @close="UpdateSuccess = false" />
  <UpdatePopupFail v-model="UpdateFail" @close="UpdateFail = false" />
  <DeleteImage
    v-model="beforeDelete"
    @close="beforeDelete = false"
    @remove="removeImage(index)"
  />
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";
import UpdatePopupSuccess from "../../Components/Popup/UpdateProduct/UpdateproductSuccess.vue";
import apiClient from "../../axios-interceptor.js";
import UpdatePopupFail from "../../Components/Popup/UpdateProduct/UpdateProductFail.vue";
import DeleteImage from "../../Components/Popup/DeleteProduct/PopupDeleteImage.vue";
export default {
  name: "EditProduct",
  components: {
    Swiper,
    SwiperSlide,
    UpdatePopupSuccess,
    UpdatePopupFail,
    DeleteImage,
  },
  data() {
    return {
      UpdateSuccess: false,
      UpdateFail: false,
      beforeDelete: false,

      product: {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
        image1: "",
      },
      imageDetail: [{ image: "" }, { image: "" }, { image: "" }, { image: "" }],
      initialImage: "", // Lưu trữ hình ảnh ban đầu
      placeholderImage: "/storage/images/inputfile.png", // Ảnh placeholder
      imagePreview: "", // Biến tạm thời để lưu ảnh xem trước
      categories: [],
      brands: [],
      productId: this.$route.params.id,
      removedImageIndexes: [], // Mảng lưu các chỉ mục của các ảnh chi tiết bị xóa
    };
  },
  mounted() {
    this.getListCategory();
    this.getListBrands();
    this.getProductDetails();
  },
  methods: {
    showModalDelete(index) {
      this.selectedImageIndex = index; // Lưu chỉ mục của hình ảnh được chọn để xóa
      this.beforeDelete = true;
    },
    triggerFileInput(index) {
      this.$refs["fileInput" + index][0].click();
    },
    onImageChange(index, event) {
      this.imageDetail[index].image = event.target.files[0];
    },
    showImageDetail(index) {
      const file = this.imageDetail[index].image;
      if (file) {
        return URL.createObjectURL(file);
      }
    },
    updateImage(index) {
      this.imagePreview = null;
      this.imageFile = null;
      this.triggerFileInput(index);
    },
    removeImage(index) {
      // Logic xóa hình ảnh dựa trên chỉ mục
      this.imageDetail[index].image = "";
      this.$refs["fileInput" + index][0].value = null; // Reset input file
      this.removedImageIndexes.push(index); // Thêm chỉ mục của ảnh bị xóa vào mảng
      this.beforeDelete = false; // Đóng popup sau khi xóa
    },
    goHome() {
      this.$router.push("/");
    },

    updateProduct() {
      let url = `products/${this.productId}`;
      let token = localStorage.getItem("token");
      let formData = new FormData();
      formData.append("_method", "PUT");
      formData.append("name", this.product.name);
      formData.append("price", this.product.price);
      formData.append("note", this.product.note);
      formData.append("category_id", this.product.category_id);
      formData.append("brand_id", this.product.brand_id);

      // Thêm ảnh minh họa vào formData
      if (this.product.image instanceof File) {
        formData.append("image", this.product.image);
      }

      // Thêm ảnh chi tiết nếu có
      for (let i = 0; i < 4; i++) {
        if (this.imageDetail[i].image instanceof File) {
          formData.append(`image_detail[${i}]`, this.imageDetail[i].image);
        }
      }

      // Thêm các chỉ mục của ảnh cần xóa vào formData
      formData.append("removed_images", JSON.stringify(this.removedImageIndexes));

      apiClient
        .post(url, formData, {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.UpdateSuccess = true;
            console.log("Update success");
            // Thực hiện hành động sau khi cập nhật thành công
          } else {
            this.UpdateFail = true;
            console.log("Update failed");
            // Xử lý trường hợp cập nhật thất bại
          }
        })
        .catch((error) => {
          console.log(error);
          // Xử lý lỗi
        });
    },
    // other methods...
    async urlToFile(url) {
      const response = await fetch(url);
      const blob = await response.blob();
      const fileName = url.split("/").pop();
      return new File([blob], fileName, { type: blob.type });
    },
    // main image
    triggerFileInputMainImage() {
      this.$refs.mainFileInput.click();
    },
    onMainImageChange(event) {
      const file = event.target.files[0];
      if (file) {
        this.product.image = file; // Lưu trữ file ảnh để gửi lên server
        this.imagePreview = URL.createObjectURL(file); // Tạo URL để hiển thị
      }
    },
    removeMainImage() {
      this.product.image = ""; // Xóa hình ảnh sản phẩm      this.imagePreview = null; // Xóa hình ảnh xem trước
    },
    getListCategory() {
      apiClient
        .get("categories")
        .then((response) => {
          this.categories = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getListBrands() {
      apiClient
        .get("brands")
        .then((response) => {
          this.brands = response.data.contents;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getProductDetails() {
      const id = this.$route.params.id; // lấy id từ route
      apiClient
        .get(`products/${id}`)
        .then(async (response) => {
          if (response.data) {
            this.product = response.data.product;
            this.initialImage = this.product.image; // Lưu hình ảnh ban đầu

            if (response.data.imageDetails.length) {
              const imagePromises = response.data.imageDetails.map(async (detail) => {
                const file = await this.urlToFile(
                  "http://127.0.0.1:8000" + detail.image_path
                );
                return { image: file };
              });

              this.imageDetail = await Promise.all(imagePromises);
            } else {
              this.imageDetail = [];
            }

            if (this.imageDetail.length < 4) {
              for (let i = this.imageDetail.length; i < 4; i++) {
                this.imageDetail.push({ image: "" });
              }
            }

            console.log("anh slide", this.imageDetail); // This should now log the files
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

    resetForm() {
      this.$router.go(-1);
    },
  },
};
</script>

<style>
/* Add your styles here */
</style>

<style scoped>
.image-detail {
  position: relative;
  transition: 0.3s all;
}

.image-detail .small-image {
  /* Giữ nguyên tỷ lệ khung hình */
  height: auto;

  object-fit: cover;
  /* Đảm bảo ảnh không bị méo */
  display: block;
  margin: 0;
}

.placeholder-image {
  width: 50px;
  height: auto;
}

.loaded-image {
  width: 192px;
  height: 118px;
}

.image-detail .button-hover {
  display: none;
  text-align: center;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 10px;
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
  width: 100px;
  height: 40px;
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
    height: auto;
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

/* main image */
.image-detail {
  position: relative;
  transition: 0.3s all;
}

.fixed-size-image {
  width: 421px;
  height: auto;
  object-fit: cover;
  /* Ensure the image covers the area without distorting */
}

.image-detail .button-hover {
  display: none;
  text-align: center;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 10px;
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
  width: 100px;
  height: 40px;
}
</style>
