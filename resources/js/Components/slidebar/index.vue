<template>
  <div class="filter" >
    <div class="category">
      <div class="t-t-c">Tất Cả</div>
      <div class="overlap-group-2">
        <div class="text-wrapper-5">Hãng Sản Xuất</div>
        <img class="ic-arrow-top" alt="Ic arrow top" src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top.svg" />
      </div>
    </div>
    <img class="line" alt="Line" src="https://c.animaapp.com/6fNIys5x/img/line-1.svg" />
    <div class="category-2">
      <div class="overlap-group-2">
        <img
            class="ic-arrow-top"
            alt="Ic arrow top"
            src="https://c.animaapp.com/6fNIys5x/img/ic-arrow-top-1.svg"
        />
        <div class="text-wrapper-5">Danh Mục</div>
      </div>
      <div class="t">Ô Tô</div>
      <div class="xe-m-y">Xe Máy</div>
      <div class="xe-p">Xe Đạp</div>
      <div class="t-t-c">Tất Cả</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Filter",
};
</script>

<style>
.filter {
  background-color: #ffffff;
  height: 916px;
  left: 0;
  display: flex;
  position: absolute;
  top: 64px;
  width: 269px;
}

.filter .category {
  height: 84px;
  left: 35px;
  position: absolute;
  top: 318px;
  width: 201px;
}

.filter .t-t-c {
  color: var(--blue);
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 700;
  height: 42px;
  left: 0;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 42px;
  width: 197px;
}

.filter .overlap-group-2 {
  height: 42px;
  left: 0;
  position: absolute;
  top: 0;
  width: 197px;
}

.filter .text-wrapper-5 {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 0;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 0;
  width: 197px;
}

.filter .ic-arrow-top {
  height: 16px;
  left: 167px;
  position: absolute;
  top: 10px;
  width: 16px;
}

.filter .line {
  height: 1px;
  left: 0;
  object-fit: cover;
  position: absolute;
  top: 275px;
  width: 269px;
}

.filter .category-2 {
  height: 210px;
  left: 43px;
  position: absolute;
  top: 38px;
  width: 208px;
}

.filter .t {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 1px;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 84px;
  width: 197px;
}

.filter .xe-m-y {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 0;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 126px;
  width: 197px;
}

.filter .xe-p {
  color: #000000;
  font-family: "Roboto", Helvetica;
  font-size: 16px;
  font-weight: 400;
  height: 42px;
  left: 1px;
  letter-spacing: 0;
  line-height: 14.2px;
  position: absolute;
  top: 168px;
  width: 197px;
}

</style>
