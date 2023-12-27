<template>
  <!-- this is HomeView.vue -->
  <v-main class="main_container" v-cloak>
    <div id="app">
      <v-carousel v-model="activeSlideIndex" cycle height="100%" hide-delimiter-background show-arrows="hover" v-cloak>
        <v-carousel-item v-for="(slide, i) in slides" :key="i">
          <v-sheet :style="{
            backgroundImage: 'url(' + this.backgroundUrl + ')', height: '100%', backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
          }">
            <div class="d-flex fill-height justify-center align-center imgAndTitle">
              <div class="title">SUMMER</div>
              <img :src="slide" class="slide_img" />
              <div class="title">COLLECTION</div>
            </div>
          </v-sheet>
        </v-carousel-item>
      </v-carousel>
    </div>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      backgroundUrl: "../test_products_img/vaporwave.gif",
      colors: [
        "indigo",
        "warning",
        "pink darken-2",
        "red lighten-1",
        "deep-purple accent-4",
      ],
      slides: ["../test_products_img/shirt1.png", "../test_products_img/cravate1.png",
        "../test_products_img/shirt3.png", "../test_products_img/cravate3.png", "../test_products_img/shirt5.png",],
      activeSlideIndex: 0, // Initialize with the index of the first slide
    };
  },
  computed: {
    // appBarColor() {
    //   // Return the color of the currently active v-sheet
    //   // console.log(this.colors[this.activeSlideIndex])
    //   return this.colors[this.activeSlideIndex];
    // },
  },
  // mounted() {
  //   this.$nextTick(() => {
  //     // var colors = this.colors[this.activeSlideIndex];
  //     // console.log("cccolors", color);
  //     this.$emit("change-app-bar-color", 'white');
  //     this.$emit('change-app-bar-elevation', 0);
  //   });
  // },
  // beforeUnmount() {
  //   this.$emit("change-app-bar-color", "white"); // Emit an event to revert the color
  //   this.$emit('change-app-bar-elevation', 10); // Reset AppBar elevation before component is destroyed
  // },
  // watch: {
  //   activeSlideIndex() {  //newVal, oldVal
  //     // console.log(`Slide index changed from ${oldVal} to ${newVal}`);
  //     // console.log(this.activeSlideIndex);
  //     // var colors = this.colors[this.activeSlideIndex];
  //     // console.log("cccolors_22", color);



  //     // this.$emit("change-app-bar-color", 'white');
  //     // this.$emit('change-app-bar-elevation', 0);
  //   },
  // },
  methods: {
    // changeAppBarColor(colors) {
    //   this.$root.$emit('app-bar-color-change', colors);
    // },
    // changeAppBarElevation(number) {
    //   this.$emit('change-app-bar-elevation', number);
    // }
  },
};
</script>

<style>
#app {
  height: 100%;
  width: 100%;
}

.main_container {
  height: 100%;
  width: 100%;
  /* object-fit: contain; */
  /* background-size: contain; */
}

.carousel_container {
  height: 100%;
  width: 100%;
  object-fit: contain;
}

[v-cloak] {
  display: none;
}

.slide_img {
  width: 30%;
  /* height: 70%; */
  border-radius: 50%;
  object-fit: contain;
}

.title {
  margin: 0% 3%;
  font-size: 3rem;
  font-weight: bolder;
  color: rgb(251, 255, 0);
  text-shadow:
    -5px -1px 0 #000,
    3px -1px 0 #000,
    -1px 1px 0 #000,
    1px 1px 0 #000;
}

.imgAndTitle {
  /* justify-content: space-between; */
  transform: translateX(3%);
}

@media (max-width: 1000px) {
  .slide_img {
    width: 30%;
    /* height: 70%; */
    border-radius: 50%;
    object-fit: contain;
  }

  .title {
    margin: 0% 3%;
    font-size: 2.5rem;
    font-weight: bolder;
    color: rgb(251, 255, 0);
    text-shadow:
      -5px -1px 0 #000,
      3px -1px 0 #000,
      -1px 1px 0 #000,
      1px 1px 0 #000;
  }
}
</style>
