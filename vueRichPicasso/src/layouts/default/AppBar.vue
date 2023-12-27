<template>

  <!-- appBar -->
  <v-app-bar
    :color="color"
    :elevation="elevation"
    rounded
    id="appbar_container"
  >
    <!-- Left-aligned buttons -->
    <template v-for="item in leftMenuItems" :key="item.title">
      <router-link :to="item.path" exact v-slot="{ navigate, isActive }">
        <div class="ma-10">
          <v-btn
            text
            v-if="!showNavIcon"
            @click="navigate"
            :class="{ 'router-link-active': isActive }"
          >
          {{ item.title }}
          </v-btn>
        </div>
      </router-link>
    </template>

    <v-spacer></v-spacer>
    <!-- Spacer to push content to the center and sides -->

    <!-- Centered title -->
    <v-toolbar-title>RICH RICASSO</v-toolbar-title>

    <v-spacer></v-spacer>
    <!-- Spacer for symmetry -->

    <!-- Right-aligned buttons -->
    <template v-for="item in rightMenuItems" :key="item.title">
      <router-link
        v-if="item.title !== 'Search'"
        :to="item.path"
        exact
        v-slot="{ navigate, isActive }"
        >
        <div class="ma-10">
        <v-btn
          text
          v-if="item.title !== 'Search' && !showNavIcon"
          @click="navigate"
          :class="{ 'router-link-active': isActive }"
        >
          {{ item.title }}
          <!-- <v-icon v-if="item.title === 'Search'">mdi-magnify</v-icon> -->
        </v-btn>
      </div>
      </router-link>


      <!-- color="grey-lighten-3" -->

      <v-card
        class="search_container"    
        text
        v-if="item.title == 'Search' && !showNavIcon"
      >
        <v-card-text>
          <v-text-field
            density="compact"
            variant="solo"
            :placeholder= "item.title"
            append-inner-icon="mdi-magnify"
            single-line
            hide-details
           
          ></v-text-field>
          <!-- @click:append-inner="onClick" -->
        </v-card-text>
      </v-card>
      <!-- <v-btn text v-if="item.title == 'Search' && !showNavIcon">
          {{ item.title }}
          <v-icon v-if="item.title === 'Search'">mdi-magnify</v-icon>
        </v-btn> -->

      <!-- Search input and icon -->
      <!-- <div v-if="item.title === 'Search'" class="search_container">
      <v-text-field
        dense
        hide-details
        placeholder="Search"
        class="search-input"
        @keyup.enter="onSearch" 
      ></v-text-field>
      <v-btn icon class="search-icon" @click="onSearch">
        <v-icon>mdi-magnify</v-icon>
      </v-btn>
    </div> -->
    </template>

    <!-- Icon for small screens -->
    <v-app-bar-nav-icon
      v-if="showNavIcon"
      @click="drawer = !drawer"
    ></v-app-bar-nav-icon>
  </v-app-bar>

  <!-- Navigation Drawer for small screens -->
  <v-navigation-drawer
    v-if="showNavIcon"
    v-model="drawer"
    :color="color"
    temporary
  >
   <!-- <v-list dense> -->
      <div>
        <v-btn class="navDrawer_buttons"
          v-for="item in [...leftMenuItems, ...rightMenuItems]"
          :key="item.title"
          @click="onMenuItemClick(item)"
        >
            {{ item.title }}

        </v-btn>
      </div>
    <!-- </v-list>  -->
  </v-navigation-drawer>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: "white",
    },
    elevation: {
      type: Number,
      default: 10,
    },
  },
  data() {
    return {
      drawer: false,
      leftMenuItems: [
        { title: "Home", path: "/" },
        { title: "Store", path: "/store" },
        { title: "About", path: "/about" },
      ],
      rightMenuItems: [
        { title: "Account", path: "/account" },
        { title: "Cart", path: "/cart" },
        { title: "Search", path: "/search" },
      ],
      windowWidth: window.innerWidth,
    };
  },
  computed: {
    showNavIcon() {
      return this.windowWidth <= 1000;
    },
  },
  mounted() {
   window.addEventListener("resize", this.onResize);
  },
  beforeUnmount() {
   window.removeEventListener("resize", this.onResize);
  },
  methods: {
    onMenuItemClick(item) {
      this.$router.push(item.path);
      this.drawer = false;
    },
    onResize() {
      this.windowWidth = window.innerWidth;
    },
  },
};
</script>

<style scoped>



#appbar_container {
  font-family: "Open Sans", sans-serif;
}

.v-toolbar-title {
  font-weight: 600;
  font-size: 1.5rem;
  position: absolute;
  left: 50%;
  margin: auto;
  transform: translateX(-60%);
  color: rgb(0, 0, 0);
}

.router-link-active {
  color: black !important;
  font-weight: bold;
}

.v-btn {
  color: #585454;
  text-transform: none; 
}

.v-navigation-drawer {
  width: 20%; 
  padding: 5%;
  /* margin: 5%; */
}

.navDrawer_buttons{
  display: flex;
  flex-direction: column;
  margin: 10%;
  width: 100%;
  margin-right: 5%;
}

@media (max-width: 959px) {
  .v-toolbar-title {
    font-weight: 600;
    font-size: 1.5rem;
    position: absolute;
    /* display: none;  */
  }
}

.search_container {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 200px; /* Adjust the width as needed */
  max-width: 100%; 
}

.search_card_text {
  width: 100%; 
  display: flex;
  justify-content: center;
}

.search_input_field {
  width: 100%; 
}

</style>
