<template>
  <v-main>
    <v-container class="fill_height">
      <div class="purchasedItemsAndPrice">
        <div class="purchasedItems_container">
          <div v-for="item in this.cartItems" :key="item.id" class="item">
            <img :src="item.image" alt="Product Image" class="product_image" />
            <div class="product_details">
              <div class="product_name">{{ item.name }}</div>
              <div class="product_quantity">Quantity: {{ item.quantity }}</div>
              <div class="product_price">Total Price : ${{ item.price }}</div>
            </div>
          </div>
        </div>

        <div class="payment_box">
          <div class="confirm_payment_container">
            <div class="text-h5 mb-3">Confirm Payment</div>
            <div class="amount-display mb-3">${{ grandTotal }}</div>
            <v-btn class="btn-primary pay_btn" @click="confirmPayment">
              Proceed with Payment
            </v-btn>
          </div>
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
      grandTotal: 0,
      cartItems: [],
    };
  },
  created() {
    if (localStorage.getItem("cartItemIdsAndQt")) {
      try {
        // this.cartItems = JSON.parse(localStorage.getItem("cartItemIdsAndQt"));
        this.cartItemIdsAndQt = JSON.parse(
          localStorage.getItem("cartItemIdsAndQt")
        );
        console.log("cartItemIdsAndQt : ", this.cartItemIdsAndQt);
      } catch (e) {
        console.error("Error parsing cart items from local storage", e);
      }
    }

    if (this.$route && this.$route.params.totalPrice) {
      this.grandTotal = parseFloat(this.$route.params.totalPrice);
    }
  },
  mounted() {
    for (let i = 0; i < this.cartItemIdsAndQt.length; i++) {
      let productid = this.cartItemIdsAndQt[i].id;
      // console.log("this.cartItemIdsAndQt[i].id : ", this.cartItemIdsAndQt[i].id);
      // getproduct
      const url = "http://localhost:4208/zi_htdocs/RichRicasso/getproduct";
      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(productid),
      })
        .then((response) => {
          if (response.status !== 200) {
            console.log("Error: Non-200 status code");
            return [];
          }
          // console.log(response.text())
          return response.json();
        })
        .then((data) => {
          this.processFetchedData(data, this.cartItemIdsAndQt[i].quantity);
        })
        .catch((error) => console.log(error));
    }
  },
  methods: {
    async confirmPayment() {
      console.log("Payment confirmed for: $" + this.grandTotal);
      const url = "http://localhost:4208/zi_htdocs/RichRicasso/checkout2";

      try {
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ amount: this.grandTotal * 100 }),
        });
        const data = await response.json();
        // console.log(data)
        window.location = data.checkoutUrl;
      } catch (error) {
        console.error("Error:", error);
      }
    },
    processFetchedData(data, quantity) {
      this.cartItems.push({
        id: data.id,
        name: data.name,
        size: data.size,
        price: Number(data.price) * Number(quantity),
        quantity: quantity,
        image: data.images.split(",")[0],
      });
    },
  },
};
</script>

<style scoped>
.fill_height {
  padding: 5%;
  margin-bottom: 5%;
}

.pay_btn {
  background-color: rgb(18, 105, 32);
  color: antiquewhite;
  padding: 2%;
}

.product_price {
  margin: 2% 0%;
}

.product_quantity {
  margin: 2% 0%;
}

.product_image {
  width: 20%;
  margin-right: 2%;
  border-radius: 20px;
}

.product_details {
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-between;
  font-weight: bold;
}

.item {
  /* text-align: center; */
  height: 100%;
  width: 100%;
  padding: 5%;
  display: flex;
  flex-direction: row;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border: 2px solid rgb(0, 0, 0);
  border-radius: 10px;

}

.purchasedItems_container {
  height: 100%;
  width: 100%;
  display: grid;
  flex-direction: column;
  gap: 5%;
  /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
   border: 2px solid rgb(0, 0, 0);
  border-radius: 10px; */
  /* text-align: center; */
  /* border: 2px solid rgb(0, 0, 0); */
}


.purchasedItemsAndPrice {
  height: 100%;
  width: 100%;
}

.confirm_payment_container {
  text-align: center;
  padding: 20%;
  width: 100%;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  border: 2px solid rgb(0, 0, 0);
  border-radius: 10px;
}

.amount-display {
  font-size: 1.5em;
  font-weight: bold;
}


.purchasedItemsAndPrice {
  display: grid;
  grid-template-columns: 1fr 0.5fr;
  gap: 2%;
}

@media (max-width: 1000px) {
  .pay_btn {
    background-color: rgb(225, 68, 68);
    color: antiquewhite;
    padding: 2%;
    font-size: 0.6rem;
  }
}
</style>
