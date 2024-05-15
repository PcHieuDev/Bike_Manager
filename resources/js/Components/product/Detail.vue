<template>
  <h1>Demoooo</h1>
  <img :src="data.Image" alt="" />
</template>
<script>
export default {
  name: "detail",
  data() {
    return {
      data: {
        Image: "/storage/images/1715611799.png",
      },
    };
  },
  mounted() {
    console.log("FFF");
  },
};
</script>
