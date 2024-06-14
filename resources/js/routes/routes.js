import { createRouter, createWebHistory } from 'vue-router'
import ProductDetails from "../pages/product/ProductDetails.vue"
import ProductActions from '../pages/ProductManager.vue'
import EditProduct from '../pages/product/EditProduct.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import HomePage from '../pages/HomePage.vue'
const routes = [
    {
        name: 'HomePage',
        path: '/',
        component: HomePage
        // component: Products
    },
    {
        name: 'ProductDetails',
        path: '/product/details/:id',
        component: ProductDetails
    },
    {
        name: 'ProductAction',
        // path: '/Product/actions',
        path: '/product-management/actions',
        component: ProductActions
    },
    {
        name: 'EditProduct',
        path: '/product/edit/:id',
        component: EditProduct
    },
    {                                                                                       
        name: 'Login',
        path: '/Login',
        component: Login
    },
    {
        name: 'register',
        path: '/Register',
        component: Register
    },
   
];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;