import { createRouter, createWebHistory } from 'vue-router'
import HomeViews from '../views/HomeView.vue'
import AuthView from '../views/AuthView.vue'

// ADMIN
import DashboardView from '../views/Admin/DashboardView.vue'
import OrderView from '../views/Admin/OrdersView.vue'
import DeliveryView from '../views/Admin/DeliveryView.vue'
import InventoryView from '../views/Admin/InventoryView.vue'
import SalesView from '../views/Admin/SalesView.vue'
import FeedbackView from '../views/Admin/FeedbackView.vue'
import UserManagementView from '../views/Admin/UserManagementView.vue'
import SettingsView from '../views/Admin/SettingsView.vue'

// USER
import HomeView from '../views/User/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeViews
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
      path: '/admin/order',
      name: 'Orders',
      component: OrderView
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
  ]
})

export default router
