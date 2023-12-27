// Importing necessary functions from vue-router  this is router index.js
import { createRouter, createWebHistory } from 'vue-router';

function checkLoginAndRedirect() {
  // localStorage.setItem('loggedIn', false)
  const loggedIn = localStorage.getItem('loggedIn') === 'true';
  const userId = localStorage.getItem('userId');
  if (loggedIn && userId) {
    return import('@/views/UserProfView.vue')
  }
  return import('@/views/LoginSignUpView.vue')
}

// import { SystemBar } from '@/components/SystemBar.vue';

// Define your routes
const routes = [
  {
    path: '/',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'Home',
        component: () => import('@/views/HomeView.vue'),
        meta: { hideFooter: true }
      },
    ],
  },
  {
    path: '/Store',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'Store',
        component: () => import('@/views/StoreView.vue'),
      },
    ],
  },
  {
    path: '/About',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'About',
        component: () => import('@/views/AboutView.vue'),
      },
    ],
  },
  {
    path: '/Account',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'Account',
        component: () => checkLoginAndRedirect(),
      },
    ],
  },
  {
    path: '/Cart',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'Cart',
        component: () => import('@/views/CartView.vue'),
      },
    ],
  },
  {
    path:  '/OneProductView/:itemId',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'OneProductView',
        component: () => import('@/views/OneProductView.vue'),
      },
    ],
  },
  {
    path:  '/ConfirmationView/:totalPrice',
    component: () => import('@/layouts/default/DefaultLayout.vue'),
    children: [
      {
        path: '',
        name: 'ConfirmationView',
        component: () => import('@/views/ConfirmationView.vue'),
      },
    ],
  },
  
];



// Create the router instance
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

// Export the router
export default router;