
<template>

<!-- eslint-disable-next-line vue/multi-word-component-names -->

  <v-app>
    <AppBar :color="appBarColor" :elevation="appBarElevation"/>
    <!-- <router-view @change-app-bar-elevation="appBarElevation = $event" /> -->
    <router-view @change-app-bar-elevation="appBarElevation = $event" @change-app-bar-color="appBarColor = $event" />
   
  </v-app>
  <FooterView/>
</template>

<script>
import AppBar from './AppBar.vue';
import FooterView from './FooterView.vue';

export default {
  components: {
    AppBar,
    FooterView,
  },
  data() {
    return {
      appBarElevation: 10, // Default elevation
      appBarColor: 'white', // This is the default color of the AppBar
    };
  },
};
</script>

<style>

AppBar{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100; 
}


FooterView{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100; 
}

</style>