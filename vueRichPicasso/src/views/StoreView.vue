<template>
  <v-main>
    <div class="store_container">
      <div class="filter_sidebar">
        <h3>Filters</h3>
        <!-- <div id="filter_container"> -->
        <div class="type">
          <p class="filter_titles">TYPES:</p>
          <select id="type" v-model="selectedType">
            <option value="">All types</option>
            <option v-for="type in typesList" :value="type" :key="type"> {{ type }} </option>
          </select>
        </div>

        <div class="taille">
          <p class="filter_titles">SIZES:</p>
          <select id="taille" v-model="selectedSize">
            <option value="">All sizes</option>
            <option v-for="size in sizesList" :value="size" :key="size">{{ size }}</option>
          </select>
        </div>

        <div class="couleur">
          <p class="filter_titles">COLORS:</p>
          <select id="couleur" v-model="selectedColor">
            <option value="">All colors</option>
            <option v-for="color in colorsList" :value="color" :key="color">{{ color }}</option>
          </select>
        </div>

        <div class="slidecontainer">
          <p class="filter_titles">PRODUCT PRICES:</p>
          <v-slider v-model="selectedPrice" :min="this.priceRange[0]" :max="this.priceRange[1]" :step="500" thumb-label>
          </v-slider>

        </div>
        <v-btn id="filter_button" @click="handleFilterClick">Filter</v-btn>
      </div>

      <!-- Main Content -->
      <div class="main_content">
        <div class="product_title">Products</div>
        <div class="store_products">
          <div class="card" v-for="item in paginatedItems" :key="item.id" @click="viewSelectedProduct(item)">
            <v-img class="product_img" :src="item.image"></v-img>
            <!-- <div class="card_title">id : {{ item.id }}</div> -->
            <div class="card_text"> name : {{ item.name }}</div>
            <div class="card_text"> Size : {{ item.size }}</div>
            <div class="card_text">price : {{ item.price }}$</div>
          </div>
        </div>

        <div class="pagination_controls">
          <v-btn @click="getPreviousPage" :disabled="currentPage <= 1">
            Previous
          </v-btn>
          <span>Page {{ currentPage }} of {{ totalPages }}</span>
          <v-btn @click="getNextPage" :disabled="currentPage >= totalPages">
            Next
          </v-btn>
        </div>
      </div>
    </div>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      data: null,
      sizesList: [],
      colorsList: [],
      typesList: [],
      pricesList: [],
      currentPage: 1,
      itemsPerPage: 12,
      selectedType: "",
      selectedSize: "",
      selectedColor: "",
      selectedPrice: 0,
      priceRange: [0, 10000],
      cartItems: [],
    };
  },
  mounted() {
    const url = 'http://localhost:4208/zi_htdocs/RichRicasso/allproducts';

    fetch(url)
      .then(response => {
        if (response.status !== 200) {
          console.log('Error: Non-200 status code');
          return [];
        }
        // console.log( response.text())
        return response.json();
      }).then(data => {
        this.processFetchedData(data)
      })
      .catch(error => console.log(error));
  },
  computed: {
    paginatedItems() {
      let start = (this.currentPage - 1) * this.itemsPerPage;
      let end = start + this.itemsPerPage;
      return this.cartItems.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.cartItems.length / this.itemsPerPage);
    },
  },
  methods: {
    handleFilterClick() {
      const filterCriteria = {
        type: this.selectedType,
        size: this.selectedSize,
        color: this.selectedColor,
        minprice: this.selectedPrice,
        maxprice : this.priceRange[1]
      };
      let values = JSON.stringify(filterCriteria)
      console.log("Filter Criteria:", values);
      this.fetchFilteredProducts(values)

    },
    fetchFilteredProducts(filterCriterias) {
      const url = 'http://localhost:4208/zi_htdocs/RichRicasso/filterproducts';
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: filterCriterias,
      })
        .then(response => {
          if (response.status !== 200) {
            console.log('Error: Non-200 status code');
            return [];
          }
          return response.json();
        }).then(data => {
          this.processFetchedData(data)
        })
        .catch(error => console.log(error));
    },
    processFetchedData(data) {
      this.cartItems = []
      for (let i = 0; i < data.length; i++) {

        this.cartItems.push({
          id: data[i].id,
          stripeId: data[i].stripeID,
          name: data[i].name,
          type: data[i].type,
          size: data[i].size,
          color: data[i].color,
          price: Number(data[i].price),
          quantity: 10,
          image: data[i].images.split(',')[0],
        });
        if (!this.sizesList.includes(data[i].size)) {
          this.sizesList.push(data[i].size);
        }
        if (!this.colorsList.includes(data[i].color)) {
          this.colorsList.push(data[i].color);
        }
        if (!this.typesList.includes(data[i].type)) {
          this.typesList.push(data[i].type);
        }
        if (!this.pricesList.includes(data[i].price)) {
          this.pricesList.push(data[i].price);
        }

      }
      let minPrice = Math.min(...this.pricesList);
      let maxPrice = Math.max(...this.pricesList);

      if (minPrice !== Infinity && maxPrice !== -Infinity) {
        this.priceRange = [minPrice, maxPrice];
      }
      this.currentPage = 1
      // });
    },
    getNextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    getPreviousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    viewSelectedProduct(item) {
      this.$router.push({ name: 'OneProductView', params: { itemId: item.id } });
    },
  },
  watch: {
    selectedPrice(newVal, oldVal) {
      console.log(`Price changed from ${oldVal} to ${newVal}`);
    },
  },
};
</script>

<style>

.product_title {
  text-align: left;
  font-weight: bold;
  font-size: 1.25rem;
  margin-left: 5%;
}

.main_content {
  display: grid;
  grid-template-rows: 0.125fr 2fr 0.5fr;
}

.pagination_controls {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 2%;
}

.pagination_controls button {
  margin: 0 5%;
  text-align: center;
}

.store_container {
  display: grid;
  grid-template-columns: 0.25fr 1fr;
  gap: 2%;
  padding: 5%;
  margin-bottom: 10%;
  height: 100%;
  width: 100%;
}

.filter_sidebar {
  padding-right: 10%;
  display: flex;
  flex-direction: column;
  gap: 2%;
  border-right: 2px solid #000000;
}

.store_products {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  padding: 0% 5%;
}

.card {
  border: 1px solid #5f5858;
  padding: 20%;
  text-align: center;
  height: 100%;
  width: 100%;
  cursor: pointer;
}


.product_img {
  object-fit: contain;
  margin-bottom: 10%;
  border-radius: 20px;
}

select {
  border: 1px solid #000000;
  width: 100%;
}

@media (max-width: 1000px) {

  .product_img {
    object-fit: contain;
  }


  .main_content {
    display: grid;
    grid-template-rows: 0.25fr 2fr 1fr;
    justify-content: center;
    align-items: center;
 width: 100%;
  }

  .store_container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2%;
    padding: 5%;
    margin-bottom: 10%;
    height: 100%;
    width: 100%;
  }

  .filter_sidebar {
    padding: 5% 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5%;
    border-bottom: 2px solid #000000;
    border-right: none;
    width: 100%;
    position: relative;
    z-index: 100%;
  }

  .type,
  .taille,
  .couleur,
  .slidecontainer {
    width: 100%;
  }

  h3 {
    text-align: center;
    margin-bottom: 3%;
  }

  #filter_button {
    width: 100%;
  }

  .store_products {
    display: grid;
    grid-template-columns: repeat(3,
        1fr);
    gap: 5%;
    padding: 5%;
    justify-content: center;
  }


  .product_title {
    text-align: center;
    font-weight: bold;
    font-size: 1.25rem;
  }
}
</style>
