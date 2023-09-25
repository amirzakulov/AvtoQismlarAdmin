import { createRouter, createWebHistory } from 'vue-router'

import home from './components/dashboard/home.vue'
import login from './components/auth/login.vue'

//products
import products from './components/products/index.vue'
import categories from './components/products/categories.vue'
import brands from './components/products/brand.vue'
import countries from './components/products/country.vue'

//Stock of raw materials
import stock from './components/stock/index.vue'
import income from './components/stock/income.vue'
import incomeAdd from './components/stock/income_add.vue'
import incomeDetails from './components/stock/income_details.vue'

//Orders
import orders from './components/orders/index.vue'
import orderAdd from './components/orders/order_add.vue'

//Order Details
import orderDetails from './components/orders/order_details.vue'

//Clients
import clients from './components/clients/index.vue'
import notifications from './components/clients/notifications.vue'

//Users
import users from './components/users/index.vue'

//permissions
import roles from './components/permissions/roles.vue'
import permissions from './components/permissions/assignRole.vue'
import stores from './components/stores/index.vue';

//partners
import partners from './components/partners/index.vue'

//Cards
import cards from './components/cards/index.vue'

//Settings
import settings from './components/settings/index.vue'

//---------------------------
import rawMaterials from './components/raw_materials_stock/index.vue'
import report from './components/raw_materials_stock/report.vue'
import rmIncome from './components/raw_materials_stock/income.vue'
import rmIncomeAdd from './components/raw_materials_stock/income_add.vue'
import rmIncomeDetails from './components/raw_materials_stock/income_details.vue'
import rmOutcome from './components/raw_materials_stock/outcome.vue'
import rmoutcomeDetails from './components/raw_materials_stock/outcome_details.vue'

//Reports
import reports from './components/reports/index.vue'
import reportClients from './components/reports/clients.vue'
import reportClientOrders from './components/reports/client_orders.vue'

const routes = [
    //project routes
    {
        path: '/',
        component: home,
        name: 'home'
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },

    //products
    {
        path: "/products",
        component: products,
        name: "products",
    }, {
        path: "/products/category",
        component: categories,
        name: "categories",
    }, {
        path: "/products/brands",
        component: brands,
        name: "brands",
    }, {
        path: "/products/countries",
        component: countries,
        name: "countries",
    },

    //Stock
    {
        path: "/stock",
        component: stock,
        name: "stock",
    }, {
        path: "/stock/income",
        component: income,
        name: "income",
    }, {
        path: "/stock/income/add",
        component: incomeAdd,
        name: "incomeAdd",
    }, {
        path: "/stock/income/details/:contract_id",
        component: incomeDetails,
        name: "incomeDetails",
    },

    //Orders
    {
        path: "/orders/:status?",
        component: orders,
        name: "orders"
    }, {
        path: "/orders/order/add",
        component: orderAdd,
        name: "orderAdd"
    },{
        path: "/orders/order/details/:order_id",
        component: orderDetails,
        name: "orderDetails"
    },

    //Clients
    {
        path: "/clients",
        component: clients,
        name: "clients"
    }, {
        path: "/clients/notifications",
        component: notifications,
        name: "notifications"
    },

   //Partners
    {
        path: "/partners",
        component: partners,
        name: "partners"
    },

    //Users
    {
        path: "/users",
        component: users,
        name: "users"
    },

    //permissions
    {
        path: "/roles",
        component: roles,
        name: "roles"
    },
    {
        path: "/roles/permissions",
        component: permissions,
        name: "permissions"
    },
    {
        path: "/stores",
        component: stores,
        name: "stores"
    },

    //Cards
    {
        path: "/cards",
        component: cards,
        name: "cards"
    },
    //Settings
    {
        path: "/settings",
        component: settings,
        name: "settings"
    },

    //Raw Materials
    {
        path: "/raw-materials",
        component: rawMaterials,
        name: "rawMaterials",
    },
    {
        path: "/raw-materials/income",
        component: rmIncome,
        name: "rmIncome",
    },
    {
        path: "/raw-materials/income/add",
        component: rmIncomeAdd,
        name: "rmIncomeAdd",
    },
    {
        path: "/raw-materials/income/details/:id",
        component: rmIncomeDetails,
        name: "rmincomeDetails",
    },
    {
        path: "/raw-materials/outcome",
        component: rmOutcome,
        name: "rmOutcome",
    },
    {
        path: "/raw-materials/outcome/:id",
        component: rmoutcomeDetails,
        name: "rmoutcomeDetails",
    },
    {
        path: '/raw-materials/report',
        component: report,
        name: 'report'
    },

    //Reports
    {
        path: '/reports',
        component: reports,
        name: 'reports'
    },
    {
        path: '/reports/clients',
        component: reportClients,
        name: 'reportClients'
    },
    {
        path: '/reports/clients/:client_id',
        component: reportClientOrders,
        name: 'reportClientOrders'
    },

]

const router = createRouter({
    history: createWebHistory(process.env.BASE_URL),
    routes,
});

export default router
