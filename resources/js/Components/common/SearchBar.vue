<template>
  <div class="input-group custom-search">
    <input
      type="text"
      class="form-control"
      placeholder="Tìm kiếm"
      aria-label="Username"
      @keyup.enter="handleSearch"
      @input="onInput"
      aria-describedby="basic-addon1"
      v-model="keyword"
    />
  </div>
</template>
<script>
export default {
  name: "SearchBar",
  data() {
    return {
      keyword: "",
    };
  },

  methods: {
    onInput() {
      this.$emit("handleSearch", this.keyword);
    },
    handleSearch() {
      this.$emit("handleSearch", this.keyword);
      localStorage.setItem("searchKeyword", this.keyword); // Lưu từ khóa vào localStorage
    },
    clearSearchTerm() {
      this.keyword = "";
      this.$emit("clear");
      localStorage.removeItem("searchKeyword"); // Xóa từ khóa khỏi localStorage khi xóa tìm kiếm
    },
  },
};
</script>
