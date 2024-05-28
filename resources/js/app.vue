<template>
  <div>
    <Header />
    <SlideBar />
<!--    <SearchBar />-->
    <router-view></router-view>
  </div>
</template>

<script>
import Header from "./Components/common/header/index.vue";

import SlideBar from "./Components/common/header/Navbar/index.vue";
// import Search from "./Components/common/SearchBar/Search.vue";

export default {
  name: "app",
  components: {
    Header,
    SlideBar,
    // Search,
  },
};
</script>
