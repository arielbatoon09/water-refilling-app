import { createRouter, createWebHistory } from 'vue-router'
import HomeViews from '../views/HomeView.vue'
import AuthView from '../views/AuthView.vue'

// ADMIN
import DashboardView from '../views/Admin/DashboardView.vue'
import ManageRefillView from '../views/Admin/ManageRefillView.vue'
import DeliveryView from '../views/Admin/DeliveryView.vue'
import InventoryView from '../views/Admin/InventoryView.vue'
import SalesView from '../views/Admin/SalesView.vue'
import FeedbackView from '../views/Admin/FeedbackView.vue'
import UserManagementView from '../views/Admin/UserManagementView.vue'
import SettingsView from '../views/Admin/SettingsView.vue'

// USER
import HomeView from '../views/User/HomeView.vue'
import RefillView from '../views/User/RefillView.vue'
import Success from '../views/User/SuccessView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: AuthView
  },
  {
    path: '/auth',
    name: 'auth',
    component: AuthView
  },
  // ADMIN
  {
    path: '/admin/dashboard',
    name: 'Dashboard',
    component: DashboardView
  },
  {
    path: '/admin/refill',
    name: 'Manage Refill',
    component: ManageRefillView
  },
  {
    path: '/admin/delivery',
    name: 'Delivery',
    component: DeliveryView
  },
  {
    path: '/admin/inventory',
    name: 'Inventory',
    component: InventoryView
  },
  {
    path: '/admin/sales',
    name: 'Sales',
    component: SalesView
  },
  {
    path: '/admin/feedback',
    name: 'Feedback',
    component: FeedbackView
  },
  {
    path: '/admin/management',
    name: 'User Management',
    component: UserManagementView
  },
  {
    path: '/admin/settings',
    name: 'Account Settings',
    component: SettingsView
  },

  // USER
  {
    path: '/home',
    name: 'Home',
    component: HomeView,
  },
  {
    path: '/refill',
    name: 'Refill Water',
    component: RefillView,
  },
  {
    path: '/success',
    name: 'success',
    component: Success,
  } 
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation guard
router.beforeEach((to, from, next) => {
  const authData = localStorage.getItem('auth');
  const authUser = authData ? JSON.parse(authData).authUser : null;

  // Check if the user is authenticated
  if (authUser) {
    // Redirect authenticated users away from the login/auth routes
    if (to.path === '/' || to.path === '/auth') {
      if (authUser.user_role === 'admin') {
        return next('/admin/dashboard');
      } else if (authUser.user_role === 'user') {
        return next('/home');
      }
    }

    // Restrict access based on user role
    if (authUser.user_role === 'admin' && to.path.startsWith('/admin')) {
      return next(); // Allow admin to access admin routes
    } else if (authUser.user_role === 'user' && !to.path.startsWith('/admin')) {
      return next(); // Allow user to access non-admin routes
    } else {
      // Redirect to appropriate route if role doesn't match the route
      return next(authUser.user_role === 'admin' ? '/admin/dashboard' : '/home');
    }
  } else {
    // If not authenticated, redirect to /auth for any restricted routes
    if (to.path !== '/auth' && to.path !== '/') {
      return next('/auth');
    }
  }

  next(); // Proceed to route if none of the above conditions are met
})

export default router
