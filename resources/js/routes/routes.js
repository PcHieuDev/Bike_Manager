import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/index.vue'
import Products from '../pages/product/index.vue'
import AddProduct from "../Components/product/AddProduct.vue"
import ProductDetails from "../Components/product/ProductDetails.vue"
import ProductActions from '../Components/product/ProductActions.vue'
import Detail from '../Components/product/Detail.vue'
import EditProduct from '../Components/product/EditProduct.vue'
import Login from '../Components/Auth/Login.vue'

const routes = [
    {
        name: 'Home',
        path: '/',
        component: Products
        // component: Products
    },
    // {
    //     name: 'Product',
    //     path: '/product',
    //     component: Products
    //
    // },

    // {
    //     name : 'AddProduct',
    //     path: '/product/add',
    //     component: AddProduct
    // },
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
        name: 'detail',
        path: '/details',
        component: Detail
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
        component: () => import('../Components/Auth/Register.vue')
    }





];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;