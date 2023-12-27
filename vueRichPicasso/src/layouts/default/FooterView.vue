<template>
  <v-footer elevation="15" class="footer">
    <div class="footer_container" v-cloak>
      <div>Lorem ipsum dolor sit amet consectetur adipisicing elit.</div>
      <div class="footer_icons">
        <button v-for="icon in icons" :key="icon" class="icon_btn">
          <v-icon>{{ icon }}</v-icon>
        </button>
      </div>
      <div>&copy; 2023 Rich Ricasso</div>
    </div>
  </v-footer>
</template>

<script>
export default {
  data: () => {
    return {
      icons: ["mdi-facebook", "mdi-twitter", "mdi-instagram", "mdi-youtube"],
    };
  },
  mounted() {
   window.addEventListener("resize", this.onResize);
  },
  beforeUnmount() {
   window.removeEventListener("resize", this.onResize);
  },
};
</script>

<style scoped>
.footer{
   border-top: 1px solid black;
   box-shadow: 0px 8px 16px rgb(0, 0, 0);
}

.footer, .footer_container {
  height: 100%;
  width: 100%;
  color: rgb(0, 0, 0);
  display: grid;
  align-items: center;
  text-align: center;
  padding: 2%;
  gap: 20%;
  background: rgb(255, 255, 255);
  /* box-shadow: 3px solid black; */
}

.footer_icons {
  display: flex;
  justify-content: center;
  gap: 1%;
 color: aliceblue;
}

.icon_btn {
  background: rgb(106, 106, 103);
  box-shadow: 3px solid rgb(255, 255, 255);
  color: inherit;
  cursor: pointer;
  min-height: 15px; 
  min-width: 15px; 
  padding: 1%;
  border-radius: 50%; 
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon_btn:hover {
  background: rgb(0, 0, 0);
  box-shadow: 3px solid rgb(255, 255, 255);
}

.v-icon {
  align-self: center;
  font-size: 1.5rem; 
}

[v-cloak]{
display: block;
}

@media (max-width: 1000px) {
  .footer, .footer_container {
  height: 100%;
  width: 100%;
  color: rgb(0, 0, 0);
  display: grid;
  align-items: center;
  text-align: center;
  padding: 5%;
  gap: 20%;
  background: rgb(255, 255, 255);
}
}
</style>