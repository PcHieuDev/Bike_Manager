import { createRouter, createWebHistory } from 'vue-router'
import Products from '../pages/product/index.vue'
import ProductDetails from "../pages/product/ProductDetails.vue"
import ProductActions from '../pages/product/ProductActions.vue'
import EditProduct from '../Components/ProductActions/EditProduct.vue'
import Login from '../pages/Auth/Login.vue'
import Register from '../pages/Auth/Register.vue'
const routes = [
    {
        name: 'Home',
        path: '/',
        component: Products
        // component: Products
    },
    {
        name: 'ProductDetails',
        path: '/product/details/:id',
        component: ProductDetails
    },
    {
        name: 'ProductAction',
        path: '/ProductActions/actions',
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