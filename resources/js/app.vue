<template>
  <div>
    <Header />
    <SlideBar />
    <SearchBar />
    <router-view></router-view>
  </div>
</template>

<script>
import Header from "./Components/header/index.vue";

import SlideBar from "./Components/slidebar/index.vue";
import Search from "./Components/SearchBar/Search.vue";

export default {
  name: "app",
  components: {
    Header,
    SlideBar,
    Search,
  },
};
</script>
