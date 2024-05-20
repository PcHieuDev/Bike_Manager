<template>
  <div class="text-xs-center">
    <v-pagination
      v-model="page"
      :length="countRercord"
      :total-visible="totalVisible"
    ></v-pagination>
  </div>
</template>

<script>
export default {
  name: "PaginationComponent",
  props: {
    page: {
      type: Number,
      required: true,
    },
    countRercord: {
      type: Number,
      required: true,
    },
    totalVisible: {
      type: Number,
      default: 5,
    },
  },
};
</script>
