<template>
  <div class="header-product d-flex justify-content-between">
    <button
        v-if="user"
        type="button"
        @click="changeDialog"
        class="btn btn-outline-primary"
    >
      Thêm sản phẩm
    </button>
    <SearchBar @input="handleInput" @clear="clearSearchTerm"></SearchBar>
  </div>
</template>

<script>
import SearchBar from '../common/SearchBar.vue';
export default {
  components: {
    SearchBar
  },
  props: ['user'],
  methods: {
    changeDialog() {
      this.$emit('changeDialog');
    },
    handleInput(event) {
      this.$emit('input', event);
    },
    clearSearchTerm() {
      this.$emit('clear');
    }
  }
}
</script>