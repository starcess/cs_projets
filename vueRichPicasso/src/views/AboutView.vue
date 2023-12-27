<template>
  <v-main class="main_container">
    <div class="titleAndCardInfo">
      <div class="about_section">
        <v-col>
        <h1 class="text-center">Rich Ricasso</h1>
        <p class="text-center">A visionary artist at the forefront of the ultraluxe vaporwave movement, brings a unique blend of aesthetics and innovation to the world of fashion.</p>
        </v-col>
        <v-col class="richImg_container">
          <v-img
            class="overflow_image"
            src="https://i.postimg.cc/Kckn3cyk/sculpture-face.png"
            :width="800"
            aspect-ratio="16:9"
          ></v-img>
        </v-col>
        <!-- </v-card> -->
      </div>

      <div class="teamCards">
        <h2 class="text-center">Our Team</h2>
        <v-row>
          <v-col cols="12" md="4" v-for="n in 3" :key="n">
            <v-card>
              <v-img :src="`http://w3schools.com/w3images/team${n}.jpg`" height="200px"></v-img>
              <v-card-title>Jane Doe</v-card-title>
              <v-card-subtitle>CEO & Founder</v-card-subtitle>
              <v-card-text>
                Explore a curated collection that showcases Rich Ricasso's signature touch on ultraluxe vaporwave fashion. From statement pieces to everyday wear, each garment reflects his commitment to pushing boundaries and redefining the fashion landscape.
              </v-card-text>
              <v-card-actions>
                <v-btn color="black" text>Contact</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </div>
  </v-main>
</template>

<script>

// export default {
//   name: "AboutUs",
//   // Your script here
// };

import { createVuetify } from 'vuetify'

import colors from 'vuetify/util/colors'

export default createVuetify({
  theme: {
    themes: {
      light: {
        dark: false,
        colors: {
          primary: colors.purple.darken3, // #E53935
          secondary: colors.teal.lighten2, // #FFCDD2
        }
      },
    },
  },
})

</script>

<style scoped>

.richImg_container{
  display: flex;
  justify-content: center;
}

 h2{
  margin-bottom: 1%;
}

.main_container {
  margin-top: 2%;
  height: 100%;
  width: 100%;
  padding: 10%;
}

.titleAndCardInfo {
  display: row;
  grid-template-rows: 1fr 1fr;
}

.about_section {
  /* margin: 5%; */
  padding: 5%;
  margin-bottom: 5%;
  background-color: #4DB6AC;
  color: #6A1B9A;
}

.v-btn {
  width: 100%;
}

.v-card {
  box-shadow: 0 4px 8px 0 rgba(35, 59, 43, 0.747);
  padding: 15px;
  background-color: #4DB6AC;
  color: #6A1B9A;
}

.v-card-title,
.v-card-subtitle,
.v-card-text {
  text-align: center;
}

.overflow-card {
  overflow: hidden;
  position: relative;
}

.overflow_image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover; /* Adjust as needed */
}

@media screen and (max-width: 650px) {
  .v-col {
    width: 100%;
  }
}
</style>
