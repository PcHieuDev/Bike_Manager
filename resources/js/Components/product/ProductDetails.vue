<template>
<div class="div">
    <div class="div-2">
      <div class="div-3">
        <div class="column">
          <div class="div-4">
            <div class="div-5">
              <img @click="goHome"
                loading="lazy"
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/13a5a5010bdb4b849c8dcb32751d5eef59a519570bde9058db6c4e2cefa5f4ad?"
                class="img"
              />
              <div class="div-6" @click="goHome">Quay lại</div>
            </div>
            <div class="div-7">New Panigale V4 SP</div>
            <div class="div-8">Danh mục: Xe máy</div>
            <div class="div-9">Hãng sản xuất: Ducati</div>
            <div class="div-10">giá sản phẩm: $ 100,000,000</div>
            <div class="div-11">Mô tả sản phẩm:</div>
            <div class="div-12">
              Lorem Ipsum is simply dummy text of the printing and typesetting
              industry. Lorem Ipsum has been the industry's standard dummy text
              ever since the 1500s, when an unknown printer took a galley of type
              and scrambled it to make a type specimen book...
            </div>
          </div>
        </div>
        <div class="column-2">
            <img
                loading="lazy"
                src="https://png.pngtree.com/png-vector/20240315/ourmid/pngtree-silver-super-car-png-image_11974437.png"
                class="mg-2"
              />
        </div>
      </div>
    </div>
    <div class="div-13">gợi ý cho bạn:</div>
    <div class="div-14">
      <div class="div-15">
        <div class="column-3">
          <div class="div-16">
            <img style="height: 146px; width: 200px;"
                loading="lazy"
                src="https://png.pngtree.com/png-vector/20240315/ourmid/pngtree-silver-super-car-png-image_11974437.png"
                class="mg-3"
              />
            <div class="div-17">Ducati DesertX</div>
            <div class="div-18">$ 100,000,000</div>
          </div>
        </div>
        <div class="column-4">
          <div class="div-19">
            <img 
            loading="lazy" style="height: 146px; width: 200px;"
                src="https://png.pngtree.com/png-vector/20240315/ourmid/pngtree-silver-super-car-png-image_11974437.png"
                class="mg-4"
              />
            <div class="div-20">Multistrada V4 Pikes Peak</div>
            <div class="div-21">$ 100,000,000</div>
          </div>
        </div>

        <div class="column-4">
          <div class="div-19">
            <img 
                loading="lazy" style="height: 146px; width: 200px; "
                src="https://png.pngtree.com/png-vector/20240315/ourmid/pngtree-silver-super-car-png-image_11974437.png"
                class="mg-4"
              />
            <div class="div-20">Multistrada V4 Pikes Peak</div>
            <div class="div-21">$ 100,000,000</div>
          </div>
        </div>
        
        <!-- <div class="column-5">
          <div class="div-22">
            <img
                loading="lazy" style="height: 146px; width: 200px; padding-left:45px"
                src="https://cdn.builder.io/api/v1/image/assets/TEMP/13a5a5010bdb4b849c8dcb32751d5eef59a519570bde9058db6c4e2cefa5f4ad?"
                class="mg-5"
              />
            <div class="div-23">
              <div class="div-24">Diavel 1260 S</div>
              <div class="div-25">$ 100,000,000</div>
            </div>
          </div>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';


export default {
  data() {
    return {
      product: {},
      
    };
  },
  methods: {
    goHome() {
      this.$router.push("/");
    },

    mounted() {
      this.getProduct();
    
    },

    getProduct(){
      axios.get('http://127.0.0.1:8000/api/products/:id')
      .then(response => {
        this.product = response.data;
      })
      .then(error => {
        console.log(error);
      })
    },
    
  },

  created() {
    this.getProduct();
  }
};


</script>

  
  


