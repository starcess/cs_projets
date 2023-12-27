// main.js

// Import Vue and App component
import { createApp } from 'vue'
import App from './App.vue'

// Import Plugins
import { registerPlugins } from '@/plugins'
import { VueStripe } from '@vue-stripe/vue-stripe';

// Function to load the Stripe.js library
function loadStripe() {
  return new Promise((resolve) => {
    if (document.querySelector('#stripe-js')) {
      resolve();
      return;
    }

    const script = document.createElement('script');
    script.id = 'stripe-js';
    script.src = "https://js.stripe.com/v3/";
    script.onload = () => resolve();
    document.head.appendChild(script);
  });
}

// Create Vue application
const app = createApp(App)

// Register plugins
registerPlugins(app)

// Load Stripe and then initialize Vue app
loadStripe().then(() => {
  // Use VueStripe plugin
  app.use(VueStripe, {
    pk: 'pk_test_51ONc07CVit0Y8pd9fKFtwpHeMcjdKIaGBqDAZq1nwyr5nOZHx2aQFGoTAIePcGO9zMgJYhoYoQ1r41301BpkDYJJ00vqpbB4K4'
  });

  // Mount the app
  app.mount('#app')
});