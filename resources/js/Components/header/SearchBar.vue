<template>
  <div class="input-group custom-search">
    <input
      type="text"
      class="form-control"
      placeholder="Tìm kiếm"
      aria-label="Username"
      @keyup.enter="handleSearch"
      aria-describedby="basic-addon1"
      v-model="keyword"
    />
  </div>
</template>
<script>
export default {
  name: "SearchBar",
  props: ["keyword", "handleSearch", "getProducts"],
};
</script>
