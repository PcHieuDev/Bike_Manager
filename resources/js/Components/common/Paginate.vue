<template>
  <div>
    <v-pagination
        v-model="currentPage"
        :length="pages"
        :total-visible="5"
    ></v-pagination>
  </div>

</template>

<script>
export default {
  name: "Pagination",
  props: {
    pages: {
      type: Number,
      required: true
    },
    initialPage: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      currentPage: this.initialPage
    };
  },
  watch: {
    currentPage(newValue) {
      this.$emit('update:page', newValue);
    }
  }
};
</script>

<style scoped>
.text-xs-center {
  margin-top: 20px;
}
</style>
