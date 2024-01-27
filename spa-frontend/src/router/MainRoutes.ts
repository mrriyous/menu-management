const MainRoutes = {
    path: '/main',
    meta: {
        requiresAuth: true
    },
    redirect: '/main',
    component: () => import('@/layouts/full/FullLayout.vue'),
    children: [
        {
            name: 'Menu Overview',
            path: '/',
            component: () => import('@/views/pages/MenuOverview.vue')
        },

        {
            name: 'Categories',
            path: '/categories',
            component: () => import('@/views/pages/Categories/Categories.vue')
        },

        {
            name: 'Add Categories',
            path: '/categories/add',
            component: () => import('@/views/pages/Categories/AddCategories.vue')
        },

        {
            name: 'Edit Categories',
            path: '/categories/:id',
            component: () => import('@/views/pages/Categories/EditCategories.vue')
        },

        {
            name: 'Products',
            path: '/products',
            component: () => import('@/views/pages/Products/Products.vue')
        },

        {
            name: 'Add Products',
            path: '/products/add',
            component: () => import('@/views/pages/Products/AddProducts.vue')
        },

        {
            name: 'Edit Products',
            path: '/products/:id',
            component: () => import('@/views/pages/Products/EditProducts.vue')
        },

        {
            name: 'Discounts',
            path: '/discounts',
            component: () => import('@/views/pages/Discounts/Discounts.vue')
        },

        {
            name: 'Add Discounts',
            path: '/discounts/add',
            component: () => import('@/views/pages/Discounts/AddDiscounts.vue')
        },

        {
            name: 'Edit Discounts',
            path: '/discounts/:id',
            component: () => import('@/views/pages/Discounts/EditDiscounts.vue')
        }
    ]
};

export default MainRoutes;
