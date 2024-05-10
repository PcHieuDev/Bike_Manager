import {createRouter, createWebHistory} from 'vue-router'
import Home from '../pages/index.vue'
import Products from '../pages/product/index.vue'
import AddProduct from "../Components/product/AddProduct.vue"
import ProductDetails from "../Components/product/ProductDetails.vue"

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
        name : 'productDetails',
        path: '/product/details/:id',
        component: ProductDetails
    }


];

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router;