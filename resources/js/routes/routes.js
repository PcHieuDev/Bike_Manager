import { createRouter, createWebHistory } from 'vue-router'
import Products from '../pages/product/index.vue'
import ProductDetails from "../Components/product/ProductDetails.vue"
import ProductActions from '../pages/product/ProductActions.vue'
import EditProduct from '../Components/product/EditProduct.vue'
import Login from '../pages/Auth/Login.vue'

const routes = [
    {
        name: 'Home',
        path: '/',
        component: Products
        // component: Products
    },
    {
        name: 'productDetails',
        path: '/product/details/:id',
        component: ProductDetails
    },

    {
        name: 'ProductAction',
        path: '/product/actions',
        component: ProductActions
    },
   
    {
        name: 'EditProduct',
        path: '/product/edit/:id',
        component: EditProduct
    },
    {                                                                                       
        name: 'Login',
        path: '/login',
        component: Login
    },

    {
        name: 'register',
        path: '/register',
        component: () => import('../pages/Auth/Register.vue')
    }

    





];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;