import { createRouter, createWebHistory } from 'vue-router'
import HomeViews from '@/views/HomeView.vue'
import AuthView from '@/views/AuthView.vue'

// ADMIN
import DashboardView from '@/views/Admin/DashboardView.vue'
import GallonView from '@/views/Admin/GallonView.vue'
import ManageRefillView from '@/views/Admin/ManageRefillView.vue'
import OrdersView from '@/views/Admin/OrdersView.vue'
import ProductsView from '@/views/Admin/ProductsView.vue'
import SalesView from '@/views/Admin/SalesView.vue'
import FeedbackView from '@/views/Admin/FeedbackView.vue'
import UserManagementView from '@/views/Admin/UserManagementView.vue'
import SettingsView from '@/views/Admin/SettingsView.vue'

// USER
import HomeView from '@/views/User/HomeView.vue'
import RefillView from '@/views/User/RefillView.vue'
import ShopView from '@/views/User/ShopView.vue'
import CartView from '@/views/User/CartView.vue'
import PurchaseView from '@/views/User/PurchaseView.vue'
import AccountView from '@/views/User/AccountView.vue'
import Success from '@/views/User/SuccessView.vue'
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
    path: '/admin/gallon',
    name: 'Gallon Type',
    component: GallonView
  },
  {
    path: '/admin/refill',
    name: 'Manage Refill',
    component: ManageRefillView
  },
  {
    path: '/admin/orders',
    name: 'Orders',
    component: OrdersView
  },
  {
    path: '/admin/products',
    name: 'Products',
    component: ProductsView
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
    name: 'Manage Settings',
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
    path: '/shop',
    name: 'Shop',
    component: ShopView,
  },
  {
    path: '/cart',
    name: 'Cart',
    component: CartView,
  },
  {
    path: '/purchase',
    name: 'My Purchase',
    component: PurchaseView,
  },
  {
    path: '/account',
    name: 'Account Settings',
    component: AccountView,
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

router.beforeEach((to, from, next) => {
  const authData = localStorage.getItem('auth');
  const authUser = authData ? JSON.parse(authData).authUser : null;

  // Check if the user is authenticated
  if (authUser) {
    const isAdminOrStaff = authUser.user_role === 'admin' || authUser.user_role === 'staff';

    // Redirect authenticated users away from the login/auth routes
    if (to.path === '/' || to.path === '/auth') {
      if (isAdminOrStaff) {
        return next('/admin/dashboard');
      } else if (authUser.user_role === 'user') {
        return next('/home');
      }
    }

    // Restrict access based on user role
    if (isAdminOrStaff && to.path.startsWith('/admin')) {
      return next();
    } else if (authUser.user_role === 'user' && !to.path.startsWith('/admin')) {
      return next();
    } else {
      return next(isAdminOrStaff ? '/admin/dashboard' : '/home');
    }
  } else {
    if (to.path !== '/auth' && to.path !== '/') {
      return next('/auth');
    }
  }

  next();
});

export default router
