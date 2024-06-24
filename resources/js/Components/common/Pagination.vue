<template>
  <div class="text-xs-center">
    <v-pagination
      v-model="pageClient"
      :length="totalPages"
      :totalVisible="totalVisible"
      @input="$emit('updatePage', $event)"
    ></v-pagination>
  </div>
</template>

<script>
export default {
  props: {
    page: {
      type: Number,
      required: true,
    },
    totalPages: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      pageClient: this.page,
      totalVisible: 7,
    };
  },
  watch: {
    pageClient(newPage) {
      this.$emit("updatePage", newPage);
    },
  },
  created() {
    this.pageClient = this.page;
  },
};
</script>
