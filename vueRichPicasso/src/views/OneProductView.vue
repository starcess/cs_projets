<template>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

  <v-main class="main_container">
    <v-container fluid>
      <div class="page_container">
        <div class="product_container">
          <div class="mainImg_container">
            <v-img :src="product.mainImage" class="mainImage"></v-img>
            <div class="mini_image_container">
              <v-img v-for="(image, index) in product.images" :src="image" :key="index" class="mini_image"
                @click="updateMainImage(image)"></v-img>
            </div>
          </div>

          <div class="product_information">
            <v-card flat>
              <v-card-title class="headline">{{ product.title }}</v-card-title>
              <v-card-subtitle>Price: ${{ product.price }}</v-card-subtitle>
              <v-rating v-model="userRating"  color="#DAA520" hover half-increments
                @input="updateUserRating"></v-rating>

              <div class="quantities_and_sizes">
                <v-select v-model="selectedSize" :items="[product.size]" label="Select Size" single-line
                  :disabled="!product.size"></v-select>


                <v-text-field class="quantity_field_container" v-model="quantity" type="number" label="Quantity"
                  min="1"></v-text-field>
              </div>

              <div class="likeBtnAndCartBtn">
                <v-btn class="like_btn" color="white" @click="toggleFavorite">
                  <i :class="isFavorited ? 'fa fa-heart red-heart' : 'fa fa-heart-o'"></i>
                </v-btn>
                <v-btn class="addToCart_btn" color="white" @click="addToCart()">Add to Cart</v-btn>
              </div>
            </v-card>

            <div class="collapsible_container">
              <button type="button" class="collapsible" @click="toggleCollapsible('collection')">
                Collection :
                <span class="collapsible_icon">
                  {{ collapsibleStates.collection ? '-' : '+' }}
                </span>
              </button>
              <div class="content" v-show="collapsibleStates.collection">
                {{ product.collection }}
              </div>

              <button type="button" class="collapsible" @click="toggleCollapsible('description')">
                Description :
                <span class="collapsible_icon">
                  {{ collapsibleStates.description ? '-' : '+' }}
                </span>
              </button>
              <div class="content" v-show="collapsibleStates.description">
                {{ product.description }}
              </div>
            </div>
          </div>
        </div>

        <div class="recommendAndTitle">
          <h3>More Like This</h3>
          <div class="recommend_container">
            <div v-for="recommendedProduct in recommendedProducts" :key="recommendedProduct.id"
              class="recommended_product" @click="viewSelectedProduct(recommendedProduct)">
              <img :src="recommendedProduct.image" alt="" class="recommended_product_image" />
              <div class="recommended_product_info">
                <span>{{ recommendedProduct.name }}</span>
                <span>{{ recommendedProduct.price }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </v-container>
  </v-main>
</template>
  
<script>
export default {
  emits: ['changeAppBarElevation', 'changeAppBarColor'],
  data() {
    return {
      cartItemIdsAndQt: [],
      itemId: null,
      mainImage: "",
      product: {
        title: "",
        price: 0,
        rating: 4.5,
        description: "",
        collection: "Summer Collection",
        stripeId: "",
        images: [
        ],
      },
      quantity: 1,
      isFavorited: false,
      userRating: 0,
      recommendedProducts: [

      ],
      selectedSize: "Select Size",
      collapsibleStates: {
        collection: false,
        description: false,
      },
    };
  },
  methods: {
    processFetchedData(data) {
      this.product.title = data.name;
      this.product.size = data.size;
      this.product.price = data.price;
      this.product.description = data.description
      this.product.mainImage = data.images.split(',')[0]
      this.product.images[0] = data.images.split(',')[0]
      this.product.images[1] = "../test_products_img/" + data.name + "_a.png"
      this.product.images[2] = "../test_products_img/" + data.name + "_b.png"
      this.product.colors = data.color
      this.stripeId = data.stripeID;
      this.product.type = data.type
      this.getRecommendedProducts(this.product.type, data.id)
    },
    getRecommendedProducts(type, alreardyShownProductId) {
      const filterCriteria = {
        type: type,
        size: null,
        color: null,
        minprice: 0,
        maxprice: 0
      };
      let values = JSON.stringify(filterCriteria)
      const url = 'http://localhost:4208/zi_htdocs/RichRicasso/filterproducts';
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: values,
      })
        .then(response => {
          if (response.status !== 200) {
            console.log('Error: Non-200 status code');
            return [];
          }
          return response.json();
        }).then(data => {
          // this.processFetchedData(data)
          this.displayRecommendedProducts(data, alreardyShownProductId)
        })
        .catch(error => console.log(error));

    },
    displayRecommendedProducts(data, alreardyShownProductId) {
      // console.log("alreardyShownProductId", alreardyShownProductId)
      this.recommendedProducts = data.filter(product => product.id !== alreardyShownProductId)
        .map(product => ({
          id: product.id,
          name: product.name,
          price: `$${product.price}`,
          image: product.images.split(',')[0]
        }));

    },
    updateMainImage(image) {
      this.product.mainImage = image;
    },
    viewSelectedProduct(item) {
      // console.log(item.id)
      this.$router.push({ name: 'OneProductView', params: { itemId: item.id } }).then(() => {
        this.$nextTick(() => {
          this.$router.go();
        });
      });
    },
    addToCart() {
      let cart = JSON.parse(localStorage.getItem('cartItemIdsAndQt')) || [];
      let itemAdded = false;

      for (let i = 0; i < cart.length; i++) {
        if (cart[i].id === this.itemId) {
          let newQt = Number(cart[i].quantity) + Number(this.quantity);
          console.log("newQt ", newQt)
          cart[i].quantity = newQt
          itemAdded = true;
          break;
        }
      }
      if (!itemAdded) {
        cart.push({ id: this.itemId, quantity: this.quantity });
      }

      localStorage.setItem('cartItemIdsAndQt', JSON.stringify(cart));
    },
    toggleFavorite() {
      this.isFavorited = !this.isFavorited;
    },
    updateUserRating(newRating) {
      this.userRating = newRating;
    },
    // fetchItemData() {
    //   console.log("Fetched Data : ", this.itemId)
    // },
    toggleCollapsible(section) {
      this.collapsibleStates[section] = !this.collapsibleStates[section];
      console.log("Toggled: ", section, this.collapsibleStates[section]);
    },
  },
  mounted() {
    this.itemId = this.$route.params.itemId;
    let productid = this.itemId
    const url = 'http://localhost:4208/zi_htdocs/RichRicasso/getproduct';
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(productid),
    })
      .then(response => {
        if (response.status !== 200) {
          console.log('Error: Non-200 status code');
          return [];
        }
        // console.log(response.text())
        return response.json();
      }).then(data => {
        this.processFetchedData(data)
      })
      .catch(error => console.log(error));
  },
};
</script>
  
<style scoped>

.red-heart {
    color: rgb(185, 35, 35);
}

.quantities_and_sizes {
  display: grid;
  grid-template-columns: 0.5fr 1fr;
  gap: 5%;
  width: 100%;
  height: 100%;
}

.colors_container {
  display: flex;
  margin: 5% 0;
}

.color_square {
  height: 20px;
  width: 20px;
  border: 1px solid black;
  margin-right: 5px;
  cursor: pointer;
}

.recommendAndTitle {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  margin-bottom: 10%;
  border-top: 2px solid black;
  border-bottom: 2px solid black;
}

.recommend_container {
  display: flex;
  overflow-x: auto;
  justify-content: center;
  padding: 2%;
  width: 100%;
}

h3 {
  margin-top: 2%;
}

.recommended_product {
  margin-right: 20px;
  text-align: center;
  cursor: pointer;
}

.recommended_product_image {
  width: 200px;
  /* height: 100px; */
  object-fit: contain;
}

.recommended_product_info {
  margin-top: 5px;
  display: flex;
  flex-direction: column;
}

.likeBtnAndCartBtn {
  display: grid;
  grid-template-columns: 0.15fr 1fr;
}

.like_btn {
  border: 2px solid #000000;
  margin-right: 10%;
}

.addToCart_btn {
  color: black;
  background-color: white;
  border: 2px solid black;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  width: 100%;

  &:hover {
    background-color: black;
    color: white;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
  }

  &:active {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    transform: translateY(2px);
  }
}

.quantity_field_container {
  width: 40%;
  max-width: 100%;
  border-radius: 0;
}

.main_container {
  /* margin-top: 5%; */
}

.mainImage {
  height: 100%;
  width: 100%;
  object-fit: contain;
}

.page_container {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product_container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: start;
  padding: 5%;
  gap: 5%;
  margin-bottom: 5%;
}

.mainImg_container {
  display: flex;
  flex-direction: column;
  align-items: center;
}

.mini_image_container {
  display: flex;
  flex-direction: row;
  overflow-x: auto;
  margin-bottom: 1em;
  margin-top: 1em;
  height: 50%;
  width: 30%;
}

.mini_image {
  cursor: pointer;
  width: 20%;
  object-fit: contain;
  margin-right: 2%;

  &:hover {
    border: 2px solid #f590be;
  }
}

.product_information {
  display: flex;
  /* margin-top: 1em; */
  flex-direction: column;
  padding: 5%;
}

.headline {
  font-size: 2em;
  margin-bottom: 0.35em;
}

.collapsible {
  background-color: #ffffff;
  color: rgb(0, 0, 0);
  cursor: pointer;
  padding: 3%;
  width: 90%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 1em;
  margin: 1% 0;
}

.active,
.collapsible:hover {
  border: 2px solid #000000;
}

.collapsible {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

.content {
  padding: 0 1%;
  max-height: 100px;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #ffffff;
}
</style>
  