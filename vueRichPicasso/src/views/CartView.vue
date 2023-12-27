<template>
  <v-main>
    <v-container>
      <!-- Header Section -->
      <div class="header-section mb-5">
        <div class="text-h4 mb-10">CART</div>
        <!-- Column Titles and Cart Items -->
        <div class="columnTitlesAndProducts">
          <!-- Column Titles -->
          <div class="columnsTitle">
            <div class="column-title" v-for="title in columnsTitle" :key="title">
              {{ title }}
            </div>
          </div>
          <!-- Cart Items Section -->
          <div class="cart_items_section">
            <div v-for="item in cartItems" :key="item.id" class="cart_item mb-5">
              <div class="item_nameAndImage">
                <!-- Item Image -->
                <div class="item_image">
                  <v-img :src="item.image" class="actual_img"></v-img>
                </div>
                <!-- Item Name -->
                <div class="item_name">{{ item.name }}</div>
              </div>
              <!-- Item Size -->
              <div class="item_size">Size: {{ item.size }}</div>
              <!-- Item Price -->
              <div class="item_price">Price: ${{ item.price }}</div>
              <!-- Item Quantity -->
              <v-text-field v-model.number="item.quantity" type="number" min="1" single-line solo dense
                class="quantity_input" @change="updateQuantity(item)"></v-text-field>
              <!-- Item Total -->
              <div class="item_total">
                Total: ${{ calculateItemTotal(item) }}
              </div>
              <!-- Item Garbage Icon -->
              <div class="trash_icon" @click="removeItem(item.id)">
                <v-icon>mdi-delete</v-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="couponAndCheckout">
        <!-- Coupon Code Section -->
        <div class="coupon_container">
          <div class="coupon_code_section">
            <label for="coupon_code" class="coupon_label">Coupon Code:</label>
            <input type="text" id="coupon_code" class="coupon_input" placeholder="Apply Promo Code" />
            <v-btn class="apply_coupon_button">Apply</v-btn>
          </div>
        </div>
        <!-- Checkout Section -->
        <div class="checkout_container">
          <div class="summary_container">
            <div class="summary_title">Summary:</div>
            <div class="summary_subtotal">
              <span class="summary_text">Subtotal:</span>
              <span class="summary_amount">${{ subtotal }}</span>
            </div>
            <div class="summary_shipping">
              <span class="summary_text">Shipping:</span>
              <span class="summary_amount">FREE</span>
            </div>
            <div class="summary_total">
              <span class="summary_text">Grand total:</span>
              <span class="summary_amount">${{ grandTotal }}</span>
            </div>
          </div>
          <v-btn class="checkout_button" @click="proceedToCheckout()">PROCEED TO CHECKOUT</v-btn>
        </div>
      </div>
    </v-container>
  </v-main>
</template>
<script>
export default {
  data() {
    return {
      cartItemIdsAndQt: [],
      cartItems: [],
      columnsTitle: ["Added Items", "Size", "Price", "Quantity", "Total"],
    };
  },
  computed: {
    subtotal() {
      let sum = 0;
      for (let i = 0; i < this.cartItems.length; i++) {
        sum += this.cartItems[i].quantity * this.cartItems[i].price;
      }
      return sum.toFixed(2);
    },
    grandTotal() {
      // const shippingCost = 4.95;
      // return (parseFloat(this.subtotal) + shippingCost).toFixed(2);
      let sum = 0;
      for (let i = 0; i < this.cartItems.length; i++) {
        sum += this.cartItems[i].quantity * this.cartItems[i].price;
      }
      return sum.toFixed(2);
    },
    itemTotal() {
      return (item) => {
        return (item.quantity * item.price).toFixed(2);
      };
    },
  },
  created() {
    if (localStorage.getItem("cartItemIdsAndQt")) {
      try {
        // this.cartItems = JSON.parse(localStorage.getItem("cartItemIdsAndQt"));
        this.cartItemIdsAndQt = JSON.parse(localStorage.getItem("cartItemIdsAndQt"));
        console.log("cartItemIdsAndQt : ", this.cartItemIdsAndQt);

      } catch (e) {
        console.error("Error parsing cart items from local storage", e);
      }
    }
  },
  mounted() {

    for (let i = 0; i < this.cartItemIdsAndQt.length; i++) {
      let productid = this.cartItemIdsAndQt[i].id
      // console.log("this.cartItemIdsAndQt[i].id : ", this.cartItemIdsAndQt[i].id);
      // getproduct
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
          this.processFetchedData(data, this.cartItemIdsAndQt[i])
        })
        .catch(error => console.log(error));
    }

  },
  methods: {
    proceedToCheckout() {
      this.$router.push({ name: 'ConfirmationView', params: { totalPrice: this.grandTotal } });
    },
    processFetchedData(data, item) {
      if(data.id == item.id){
        this.cartItems.push({
        id: item.id,
        name: data.name,
        size: data.size,
        price: data.price,
        quantity: item.quantity,
        image: data.images.split(',')[0]
      });
      }
     

    },
    updateLocalStorage() {
      localStorage.setItem("cartItemIdsAndQt", JSON.stringify(this.cartItemIdsAndQt));
      console.log("update ids and quantity list : ", this.cartItemIdsAndQt);
    },
    removeItem(itemId) {
      for (let i = 0; i < this.cartItemIdsAndQt.length; i++) {
        if (this.cartItemIdsAndQt[i].id == itemId) {
          console.log("itemId : ", itemId)
          this.cartItems.splice(i, 1);
          this.cartItemIdsAndQt.splice(i, 1);
          break; 
        }
      }
      this.updateLocalStorage();
    },
    updateQuantity(item) {
        let index = this.cartItems.findIndex(i => i.id === item.id);
        if (index !== -1) {
            this.cartItems[index].quantity = item.quantity;
            let qtIndex = this.cartItemIdsAndQt.findIndex(qtItem => qtItem.id === item.id);
            if (qtIndex !== -1) {
                this.cartItemIdsAndQt[qtIndex].quantity = item.quantity;
            }
            this.updateLocalStorage();
        }
    },
    calculateItemTotal(item) {
      return (item.quantity * item.price).toFixed(2);
    },
  },
};
</script>
<style scoped>
.actual_img{
  border-radius: 20px;
}

.trash_icon {
  display: flex;
  align-items: center;
  justify-content: center;
}

.trash_icon:hover {
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
  border-radius: 50%;
  /* Rounded corners */
  padding: 5%;
  /* Padding around the icon */
}

.header-section {
  text-align: left;
  padding: 5%;
  font-size: 1em;
}

.item_nameAndImage {
  display: flex;
  flex-direction: column;
  /* justify-content: center; */
  align-items: center;
  text-align: center;
}

.columnTitlesAndProducts {
  display: grid;
  gap: 2%;
}

.columnsTitle,
.cart_item {
  /* add shadows to each elements box */
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr;
  align-items: center;
  justify-items: center;

  /* add shadows to each elements box */
  /* justify-items: center;
  padding: 2%;
  margin-bottom: 5%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border-radius: 1%; */
}

.columnsTitle {
  border-bottom: 2px solid #000;
  margin-bottom: 2%;
  font-weight: bold;
}

.cart_item {
  padding: 2%;
  margin-bottom: 5%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border-radius: 1%;
}

.item_image {
  width: 150px;
  height: auto;
 
}

.item_name,
.item_size,
.item_price,
.item_quantity,
.item_total {
  padding: 0 16px;
}

.quantity_input {
  width: 100%;
}

.cart_items_section {
  margin-bottom: 5%;
}

.column-title {
  text-align: center;
}

.couponAndCheckout {
  border-top: 2px solid #000;
  display: grid;
  grid-template-columns: 0.5fr 1fr;
  padding: 3%;
  gap: 5%;
}

/*  */
.coupon_container {
  display: flex;
  flex-direction: column;
  /* padding: 5%; */
  /* border: 1px solid #ddd; */
  /* margin-bottom: 10%; */
}

.coupon_code_section {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.coupon_label {
  /* margin-right: 8px; */
}

.coupon_input {
  flex-grow: 1;
  margin-right: 5%;
  border: 2px solid #000;
}

.apply_coupon_button {
  padding: 2% 5%;
  background-color: #000;
  color: #fff;
  border: none;
  cursor: pointer;
}

.checkout_container {
  /* border: 1px solid #ddd; */
  /* padding: 5%; */
}

.summary_container {
  margin-bottom: 16px;
}

.summary_title {
  font-weight: bold;
  margin-bottom: 8px;
}

.summary_subtotal,
.summary_shipping,
.summary_total {
  display: flex;
  justify-content: space-between;
  margin-bottom: 4px;
}

.summary_text {
  font-weight: bold;
}

.summary_amount {
  text-align: right;
}

.checkout_button {
  width: 100%;
  padding: 0 5%;
  background-color: #000;
  color: #fff;
  text-align: center;
  align-items: center;
  font-weight: bold;
  border: none;
  cursor: pointer;
}

.payment_options {
  display: flex;
  justify-content: space-between;
  padding-top: 16px;
}

@media (max-width: 1000px) {
  .coupon_container {
    display: flex;
    flex-direction: column;
    /* padding: 5%; */
    /* border: 1px solid #ddd; */
    /* margin-bottom: 10%; */
  }

  .coupon_code_section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 99%;
  }

  .coupon_label {
    margin-right: 3%;
  }

  .coupon_input {
    flex-grow: 1;
    margin-right: 5%;
    border: 2px solid #000;
  }

  .apply_coupon_button {
    padding: 2% 5%;
    background-color: #000;
    color: #fff;
    border: none;
    cursor: pointer;
  }

  .couponAndCheckout {
    border-top: 2px solid #000;
    display: grid;
    grid-template-columns: 1fr;
    padding: 3%;
    gap: 5%;
  }
}
</style>