<template>
  <div class="text-xs-center">
    <v-pagination v-model="page" :length="countRercord" :total-visible="5"></v-pagination>
  </div>
</template>

<script>
export default {
  name: 'CustomPagination',
  props: ['page', 'countRercord'],
};
</script>