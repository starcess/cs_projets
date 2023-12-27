<template>
  <v-main class="main_container">
    <v-container>
      <v-row justify="center">
        <v-col cols="12" sm="8" md="6" lg="4">
          <v-card class="elevation-12">
            <v-toolbar color="purple" dark flat>
              <v-toolbar-title class="text-center w-100">{{ formTitle }}</v-toolbar-title>
            </v-toolbar>
            <v-card-text>
              <v-card-text>
                <v-text-field v-if="isSignUp" v-model="firstName" label="First Name" prepend-icon="mdi-account"
                  type="text"></v-text-field>
                <v-text-field v-if="isSignUp" v-model="lastName" label="Last Name" prepend-icon="mdi-account"
                  type="text"></v-text-field>
                <v-text-field v-model="email" label="email" prepend-icon="mdi-email" type="email"></v-text-field>
                <v-text-field v-model="password" label="password" prepend-icon="mdi-lock" type="password"></v-text-field>
              </v-card-text>
            </v-card-text>
            <v-card-actions class="justify-center">
              <v-btn color="blue" dark width="100%" @click="handleSubmit">{{ formButtonTitle }}</v-btn>
            </v-card-actions>
            <v-card-actions v-if="!isSignUp" class="justify-center">
              <v-btn text color="purple">Forgot Password?</v-btn>
            </v-card-actions>
            <v-divider></v-divider>
            <v-card-actions class="justify-center">
              <v-btn text color="purple" width="100%" @click="toggleForm">
                {{ isSignUp ? 'Already have an account? Sign In' : "Don't have an account? Sign Up" }}
              </v-btn>
            </v-card-actions>
            <v-card-actions v-if="!isSignUp" class="justify-center">
              <!-- Insert social media icons here -->
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>
  
<script>
export default {
  name: "SignInUp",
  data() {
    return {
      firstName: "",
      lastName: "",
      email: "",
      password: "",
      isSignUp: false, // Toggle between sign up and sign in
    };
  },
  computed: {
    formTitle() {
      return this.isSignUp ? "SIGN UP" : "SIGN IN";
    },
    formButtonTitle() {
      return this.isSignUp ? "Sign Up" : "Login";
    },
  },
  methods: {
    toggleForm() {
      this.isSignUp = !this.isSignUp;
    },
    handleSubmit() {
      if (this.isSignUp) {
        this.createAccount();
      } else {
        this.SignIntoAccount();
      }
    },
    createAccount() {
      console.log("Creating Account with:", this.firstName, this.lastName, this.email, this.password);

      const account = {
        email: this.email,
        name: this.firstName,
        lastname: this.lastName,
        password: this.password
      };
      const url = 'http://localhost:4208/zi_htdocs/RichRicasso/register';
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(account),
      })
        .then(response => {
          if (response.status !== 200) {
            console.log('Error: Non-200 status code');
            return [];
          }
          // console.log(response.text())
          return response.json();
        }).then(data => {
          if (data) {
            // localStorage.setItem('loggedIn', true);
            // localStorage.setItem('userId', data.id);
            console.log("Created Account")
            // this.$router.push({ name: 'Account' });
          }

          // console.log("Logged in ? : ", data)
        })
        .catch(error => console.log(error));
    },
    SignIntoAccount() {
      console.log("Logging in with:", this.email, this.password);
      const account = {
        email: this.email,
        password: this.password
      };
      const url = 'http://localhost:4208/zi_htdocs/RichRicasso/login';
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(account),
      })
        .then(response => {
          if (response.status !== 200) {
            console.log('Error: Non-200 status code');
            return [];
          }
          // console.log(response.text())
          return response.json();
        }).then(data => {
          // console.log("Logged in ? : ", data)
          if (data.id) {
            
            console.log(data.id)
            this.$router.push({ name: 'Account' })
              .then(() => {
                localStorage.setItem('loggedIn', true);
            localStorage.setItem('userId', data.id);
                this.$router.go()
              })
              .catch((error) => {
                
                if (error.name !== 'NavigationDuplicated') {
                  // Handle error or log it
                  console.error(error);
                }
              });
          }
        })
        .catch(error => console.log(error));

    },

  },
};
</script>
  
<style scoped>
.main_container {
  /* margin: 5%; */
  height: 80%;
  width: 100%;
  margin-top: 5%;
}

.v-toolbar-title {
  width: 100%;
}


.text-center {
  text-align: justify;
}
</style>