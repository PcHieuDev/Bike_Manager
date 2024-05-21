<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="addProduct">
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

            <button type="submit" class="btn btn-primary mt-4">Submit</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  <!-- popup afterAddProduct -->
  <v-dialog v-model="afterAddProduct" max-width="610">
    <v-card>
      <v-btn icon class="close-btn" @click="afterAddProduct = false">
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

export default {
  name: "AddProduct",
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
    };
  },

  mounted() {
    this.getListCategory();
    this.getListBrands();
  },

  methods: {
    addProduct() {
      const formData = new FormData();
      formData.append("name", this.product.name);
      formData.append("note", this.product.note);
      formData.append("price", this.product.price);
      formData.append("category_id", this.product.category_id);
      formData.append("brand_id", this.product.brand_id);
      formData.append("image", this.product.image);
      let token = localStorage.getItem("token");

      axios
        .post("http://127.0.0.1:8000/api/saveProduct", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
            Authorization: `Bearer ${token}`,
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
    onChange(e) {
      this.product.image = e.target.files[0];
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

<style>
.col-md-6 {
  flex: 0 0 auto;
  width: 50%;
  padding-left: 223px;
  padding-top: 70px;
}
</style>
