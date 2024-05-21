<template>
  <v-dialog
    :value="isShowDialog"
    @input="$emit('update:isShowDialog', $event)"
    max-width="674"
  >
    <v-card>
      <v-card-title class="headline">Thêm sản phẩm</v-card-title>
      <v-card-text>
        <form>
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
                <template v-for="(item, index) in categories" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label mt-4">Hãng sản xuất</label>
              <select class="form-select" v-model="product.brand_id">
                <template v-for="(item, index) in brands" :key="index">
                  <option :value="item.id">{{ item.name }}</option>
                </template>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label mt-4">Note</label>
              <textarea class="form-control" v-model="product.note" rows="3"></textarea>
            </div>
          </fieldset>
        </form>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <button @click="addProduct" type="button" class="btn btn-outline-primary">
          Thêm sản phẩm
        </button>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "AddProductDialog",
  props: ["isShowDialog", "categories", "brands", "addProduct", "onChange"],

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
    onChange(e) {
      this.product.image = e.target.files[0];
    },
  },
  // props: [
  //   "isShowDialog",
  //   "getListCategory",
  //   "getListBrands",
  //   "addProduct",
  //   "onChange",
  //   "product",
  //   "getProducts",
  //   "changeDialog",
  // ],
};
</script>
