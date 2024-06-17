<template>
  <v-dialog
    :value="isShowDialog"
    @input="$emit('update:isShowDialog', $event)"
    max-width="674"
  >
    <v-card>
      <v-spacer></v-spacer>

      <v-card-title class="headline">Thêm sản phẩm</v-card-title>
      <v-card-text>
        <form ref="productForm">
          <fieldset>
            <div class="form-group">
              <label class="form-label mt-4 required">Tên sản phẩm</label>
              <input
                type="text"
                class="form-control"
                v-model="product.name"
                placeholder="Enter name"
              />
            </div>
            <div class="form-group">
              <label class="form-label mt-4 required">Giá</label>
              <input
                type="text"
                class="form-control"
                v-model="product.price"
                placeholder="Enter price"
              />
            </div>

            <div class="form-group">
              <label class="form-label mt-4 required">Danh Mục</label>
              <select class="form-select" v-model="product.brand_id">
                <template v-for="(item, index) in brands" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label mt-4 required">Hãng sản xuất</label>
              <select class="form-select" v-model="product.category_id">
                <template v-for="(item, index) in categories" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label mt-4 required">Mô tả</label>
              <textarea class="form-control" v-model="product.note" rows="1"></textarea>
            </div>

            <div class="form-group">
              <label class="form-label mt-4 required" style="color: green"
                >Thêm hình ảnh minh họa</label
              >
              <input
                type="file"
                class="form-control"
                ref="imageInput"
                @change="onImageChange"
                placeholder="Enter image"
              />
            </div>
          </fieldset>
        </form>
      </v-card-text>

      <v-card-actions class="justify-center">
        <button
          @click="closePopup"
          type="button"
          class="btn btn-outline-secondary btn-wide btn-black-text"
        >
          Hủy
        </button>
        <button
          @click="addProduct"
          type="button"
          class="btn btn-outline-primary btn-wide"
        >
          Thêm sản phẩm
        </button>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "AddProductDialog",
  props: ["isShowDialog", "categories", "brands"],

  data() {
    return {
      product: {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
      },
    };
  },

  methods: {
    addProduct() {
      this.$emit("submit", this.product);
    },

    onImageChange(e) {
      this.product.image = e.target.files[0];
    },

    resetForm() {
      this.product = {
        name: "",
        price: "",
        note: "",
        image: "",
        category_id: "",
        brand_id: "",
      };
      // Reset the file input
      this.$refs.imageInput.value = "";
    },

    closePopup() {
      this.resetForm();
      this.$emit("close");
    },
  },
};
</script>

<style scoped>
.btn-outline-primary,
.btn-outline-secondary {
  border-radius: 20px;
  padding: 5px 10px;
  display: inline-block;
}

.btn-outline-primary {
  background-color: #00b5ff; /* Sky blue color */
  color: white;
  border: 1px solid #00b5ff;
}

.btn-outline-secondary {
  background-color: #ffffff;
  color: white;
  border: 1px solid #00b5ff;
}

.btn-black-text {
  color: black; /* Black text color for the Hủy button */
}

.btn-wide {
  width: 150px; /* Set a fixed width for both buttons */
}

.v-card-actions.justify-center {
  justify-content: center;
  display: flex;
  gap: 10px; /* Add some space between the buttons */
}

.required::after {
  content: "*";
  color: red;
}
</style>
