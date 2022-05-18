<template>
  <div class="home">
    <img alt="Vue logo" class="m-auto" :src="mix('/images/logo.png')" />
    <HelloWorld msg="Welcome to Your Vue.js App" />
  </div>
</template>

<script>
import HelloWorld from "@/components/HelloWorld.vue";


export default {
  name: "HomeView",
  components: {
    HelloWorld,
  }
};
</script>
