<template>
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <form @submit.prevent="addProduct">
          <fieldset>
            <div class="form-group">
              <label class="form-label mt-4">Name</label>
              <input type="text" class="form-control" v-model="product.name" placeholder="Enter name"/>
            </div>
            <div class="form-group">
              <label class="form-label mt-4">Price</label>
              <input type="text" class="form-control" v-model="product.price"  placeholder="Enter price"/>
            </div>

            <div class="form-group">
              <label class="form-label mt-4">image</label>
              <input type="text" class="form-control" v-model="product.image"  placeholder="Enter image"/>
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








</template>

<script>
import axios from 'axios';

export default {
  name: "AddProduct",
  data() {
    return {
      product: {
        name: '',
        price: '',
        note: '',
        image: '',
        category_id: '',
        brand_id: '',
      },
      categories: [],
      brands: [],
    }
  },

  mounted() {
    this.getListCategory();
    this.getListBrands();
  },

  methods:{
    addProduct() {
      console.log(this.product)
      axios.post('http://127.0.0.1:8000/api/saveProduct', this.product)
          .then(response => {
            console.log(response);
          })
          .catch(error => {
            console.log(error);
          })
    },
    getListCategory() {
      axios.get('http://127.0.0.1:8000/api/categories')
          .then(response => {
            this.categories = response.data.contents;
          })
          .catch(error => {
            console.log(error);
          })
    },

    getListBrands() {
      axios.get('http://127.0.0.1:8000/api/brands')
          .then(response => {
            this.brands = response.data.contents;
          })
          .catch(error => {
            console.log(error);
          })
    }
  },
}
</script>

<style>
.col-md-6{
  flex: 0 0 auto;
  width: 50%;
  padding-left: 223px;
  padding-top: 70px;
}


</style>