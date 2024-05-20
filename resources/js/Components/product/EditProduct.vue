<template>
  <div class="div">
    <div class="div-2">
      <div class="column">
        <div class="div-3">
          <div class="div-4x" @click="$router.go(-1)">Quay Lại</div>
          <div class="div-4">Thông tin sản phẩm</div>
          <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" class="form-control" placeholder="Nhập tên sản phẩm" />
          </div>

          <div class="form-group">
            <label>Danh mục sản phẩm</label>
            <select class="form-select" v-model="product.category_id">
              <!--                <option selected>-&#45;&#45;Chọn-&#45;&#45;</option>-->
              <template v-for="(item, index) in categories" :key="index">
                <option :value="item.id">{{ item.name }}</option>
              </template>
            </select>
          </div>

          <div class="form-group">
            <label>Hãng sản xuất</label>
            <select class="form-select" v-model="product.brand_id">
              <!--                <option selected>-&#45;&#45;Chọn-&#45;&#45;</option>-->
              <template v-for="(item, index) in brands" :key="index">
                <option :value="item.id">{{ item.name }}</option>
              </template>
            </select>
          </div>

          <div class="form-group">
            <label>Giá</label>
            <input type="number" class="form-control" placeholder="Nhập giá" />
          </div>

          <div class="form-group">
            <label>Mô tả</label>
            <textarea
              class="form-control"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>

          <div class="div-17">
            <button class="div-18">Lưu</button>
            <button class="div-18">Huy</button>
          </div>
        </div>
      </div>
      <div class="column-2">
        <div class="div-20">
          <div class="div-21">
            Ảnh minh họa
            <span style="color: rgba(255, 77, 77, 1)">*</span>
            <img
              src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp"
              class="w-100 shadow-1-strong rounded mb-4"
              alt="Boat on Calm Water"
            />
          </div>
          <div class="div-22">
            <div class="row p-4">
              <!-- image detail -->

              <div class="col-lg-6 col-md-12 mb-6 mb-lg-0">
                <span>Ảnh 1</span>
                <div class="image-detail">
                  <img
                    src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/Nature/4-col/img%20(73).webp"
                    class="w-100 shadow-1-strong rounded mb-4"
                    alt="Boat on Calm Water"
                  />
                  <div class="button-hover">
                    <div><button>Cập nhật</button></div>
                    <div><button>Xóa</button></div>
                  </div>
                </div>
              </div>

              <!-- button add image -->
              <div class="col-lg-6 col-md-12 mb-6 mb-lg-0 custom-btn-add-img">
                <div class="file-input">
                  <input
                    type="file"
                    name="file-input"
                    id="file-input"
                    class="file-input__input"
                  />
                  <label class="file-input__label" for="file-input">
                    <img
                      src="https://cdn.builder.io/api/v1/image/assets/TEMP/d9db3dbd583a98e15fc1248e1af2e10cfbac954d35db499e1c898150c3f0fa90?apiKey=c828819b5944477ab9c72fd951f3ee71&"
                      alt="Boat on Calm Water"
                  /></label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "EditProduct",
  data() {
    return {
      product: {
        name: "",
        price: "",
        note: "",
        image: null,
        afterAddProduct: false,
        category_id: "",
        brand_id: "",
      },
      categories: [],
      brands: [],
      productId: this.$route.params.id,
    };
  },
  mounted() {
    this.getListCategory();
    this.getListBrands();
  },

  methods: {
    goHome() {
      this.$router.push("/");
    },

    getProductDetails() {
      const id = this.$route.params.id; // lấy id từ route
      axios
        .get(`http://127.0.0.1:8000/api/products/${this.$route.params.id}`)
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
  padding: 59px 30px;
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
    gap: 0px;
  }
}

.column {
  display: flex;
  flex-direction: column;
  line-height: normal;
  width: 50%;
  margin-left: 0px;
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

@media (max-width: 991px) {
  .div-4x {
  }
}

.div-5 {
  color: var(--Red, #ff4d4d);
  margin-top: 38px;
  font: 16px Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-5 {
    max-width: 100%;
  }
}

.div-6 {
  font-family: Inter, sans-serif;
  border-radius: 6px;
  border-color: rgba(188, 190, 198, 1);
  border-style: solid;
  border-width: 1px;
  margin-top: 11px;
  align-items: start;
  color: var(--Neutral-Darker-Grey, #343747);
  line-height: 170%;
  justify-content: center;
  padding: 14px 16px;
}

@media (max-width: 991px) {
  .div-6 {
    max-width: 100%;
    padding-right: 20px;
  }
}

.div-7 {
  color: var(--Red, #ff4d4d);
  margin-top: 28px;
  font: 16px Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-7 {
    max-width: 100%;
  }
}

.div-8 {
  margin-top: 11px;
  gap: 20px;
}

@media (max-width: 991px) {
  .div-8 {
    max-width: 100%;
    flex-wrap: wrap;
  }
}

.div-9 {
  font-family: Inter, sans-serif;
  margin: auto 0;
}

.img {
  aspect-ratio: 1;
  object-fit: auto;
  object-position: center;
  width: 16px;
}

.div-10 {
  color: var(--Red, #ff4d4d);
  margin-top: 30px;
  font: 16px Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-10 {
    max-width: 100%;
  }
}

.div-11 {
  border-radius: 6px;
  border-color: rgba(188, 190, 198, 1);
  border-style: solid;
  border-width: 1px;
  display: flex;
  margin-top: 11px;
  gap: 20px;
  color: var(--Neutral-Darker-Grey, #343747);
  white-space: nowrap;
  line-height: 170%;
  justify-content: space-between;
  padding: 12px 14px;
}

@media (max-width: 991px) {
  .div-11 {
    max-width: 100%;
    flex-wrap: wrap;
    white-space: initial;
  }
}

.div-12 {
  font-family: Inter, sans-serif;
  margin: auto 0;
}

.div-13 {
  color: var(--Red, #ff4d4d);
  margin-top: 28px;
  font: 16px Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-13 {
    max-width: 100%;
  }
}

.div-14 {
  font-family: Inter, sans-serif;
  border-radius: 6px;
  border-color: rgba(188, 190, 198, 1);
  border-style: solid;
  border-width: 1px;
  margin-top: 14px;
  align-items: start;
  color: var(--Neutral-Darker-Grey, #343747);
  line-height: 170%;
  justify-content: center;
  padding: 14px 16px;
}

@media (max-width: 991px) {
  .div-14 {
    max-width: 100%;
    padding-right: 20px;
  }
}

.div-15 {
  color: var(--Neutral-Darker-Grey, #343747);
  margin-top: 29px;
  font: 16px Inter, sans-serif;
}

@media (max-width: 991px) {
  .div-15 {
    max-width: 100%;
  }
}

.div-16 {
  font-family: Inter, sans-serif;
  border-radius: 6px;
  border-color: rgba(188, 190, 198, 1);
  border-style: solid;
  border-width: 1px;
  margin-top: 14px;
  align-items: start;
  color: var(--Neutral-Grey, #bcbec6);
  line-height: 170%;
  padding: 15px 20px 65px;
}

@media (max-width: 991px) {
  .div-16 {
    max-width: 100%;
  }
}

.div-17 {
  display: flex;
  gap: 20px;
  font-weight: 500;
  white-space: nowrap;
  text-align: center;
  line-height: 170%;
  justify-content: space-between;
  margin: 61px 34px 0;
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
  background-color: var(--Primary, #0cf);
  align-items: center;
  color: #fff;
  justify-content: center;
  padding: 19px 60px;
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

.div-23 {
  gap: 20px;
  display: flex;
}

@media (max-width: 991px) {
  .div-23 {
    flex-direction: column;
    align-items: stretch;
    gap: 0px;
  }
}

.div-24 {
  display: flex;
  flex-grow: 1;
  flex-direction: column;
  font-size: 16px;
  color: var(--Neutral-Darker-Grey, #343747);
  font-weight: 400;
  letter-spacing: 0.35px;
  line-height: 150%;
}

@media (max-width: 991px) {
  .div-24 {
    margin-top: 40px;
  }
}

.div-25 {
  font-family: Inter, sans-serif;
}

.div-26 {
  font-family: Inter, sans-serif;
  margin-top: 50px;
}

@media (max-width: 991px) {
  .div-26 {
    margin-top: 40px;
  }
}

.div-27 {
  font-family: Inter, sans-serif;
  margin-top: 48px;
}

@media (max-width: 991px) {
  .div-27 {
    margin-top: 40px;
  }
}

.column-3 {
  display: flex;
  flex-direction: column;
  line-height: normal;
  width: 50%;
  margin-left: 20px;
}

@media (max-width: 991px) {
  .column-3 {
    width: 100%;
  }
}

.div-28 {
  display: flex;
  margin-top: 66px;
  flex-direction: column;
  font-size: 16px;
  color: var(--Neutral-Darker-Grey, #343747);
  font-weight: 400;
  letter-spacing: 0.35px;
  line-height: 150%;
}

@media (max-width: 991px) {
  .div-28 {
    margin-top: 40px;
  }
}

.div-29 {
  font-family: Inter, sans-serif;
}

.div-30 {
  font-family: Inter, sans-serif;
  margin-top: 48px;
}

@media (max-width: 991px) {
  .div-30 {
    margin-top: 40px;
  }
}

.img-2 {
  aspect-ratio: 1;
  object-fit: auto;
  object-position: center;
  width: 74px;
  align-self: center;
  margin-top: 40px;
}
</style>
