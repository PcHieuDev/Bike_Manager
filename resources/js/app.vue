<template>
  <div id="layout">
    <Header />
    <main>
      <SlideBar />
      <div class="main-content">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script>
import Header from "./Components/common/Header.vue";
import SlideBar from "./Components/common/Sidebar.vue";

export default {
  name: "App",
  components: {
    Header,
    SlideBar,
  },
};
</script>

<style>
#layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

main {
  display: flex;
  flex: 1;
}

.main-content {
  flex: 1;
  padding: 1rem;
  background: #f4f4f4;
}

/* Responsive Styles */
@media (max-width: 768px) {
  main {
    flex-direction: column;
  }

  .main-content {
    padding: 0.5rem;
  }
}
</style>
