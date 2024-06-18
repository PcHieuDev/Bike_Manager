<template>
  <div id="layout">
    <Header />
    <main>
      <Sidebar />
      <div class="main-content">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script>
import Header from "./Components/common/Header.vue";
import Sidebar from "./Components/common/Sidebar.vue";

export default {
  name: "App",
  components: {
    Header,
    Sidebar,
  },
};
</script>

<style>
.v-carousel__controls{
  background: none !important;
  color: rgb(125 125 125) !important;
}

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
  background: #eeeeee;
;
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
.v-pagination .v-btn {
  border-radius: 4px;
  background: #f1f1f1;
}
.v-pagination__list{
  width: 93% !important;
}
</style>
