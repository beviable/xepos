import { createWebHistory, createRouter } from 'vue-router'
import store from '../store'

/* Guest Component */
const Login = () => import('../components/Login.vue')
const Register = () => import('../components/Register.vue')
/* Guest Component */

/* Layouts */
const DefaultLayout = () => import('../components/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('../components/Dashboard.vue')
/* Authenticated Component */

const CompanyList = () => import('../components/company/List.vue' /* webpackChunkName: "resource/js/components/company/list" */)
const CompanyCreate = () => import('../components/company/Add.vue' /* webpackChunkName: "resource/js/components/company/add" */)
const CompanyEdit = () => import('../components/company/Edit.vue' /* webpackChunkName: "resource/js/components/company/edit" */)


const EmployeeList = () => import('../components/employee/List.vue' /* webpackChunkName: "resource/js/components/employee/list" */)
const EmployeeCreate = () => import('../components/employee/Add.vue' /* webpackChunkName: "resource/js/components/employee/add" */)
const EmployeeEdit = () => import('../components/employee/Edit.vue' /* webpackChunkName: "resource/js/components/employee/edit" */)


const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DefaultLayout,
        meta: {
            middleware: "auth"
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    },
    {
        path: '/employee',
        meta: {
            middleware: "auth"
        },
        component: DefaultLayout,
        children: [
            {
                name: "list-employee",
                path: '/employee',
                component: EmployeeList,
                meta: {
                    title: `Employees`
                }
            },
            {
                name: 'add-employee',
                path: '/employee/add',
                component: EmployeeCreate,
                meta: {
                    title: `Create Employee`
                }
            },
            {
                name: 'edit-employee',
                path: '/employee/edit/:id',
                meta: {
                    title: `Edit Employee`
                },
                component: EmployeeEdit
            }
        ]

    }
    ,
    {
        path: '/company',
        meta: {
            middleware: "auth"
        },
        component: DefaultLayout,
        children: [
            {
                name: "list-company",
                path: '/company',
                component: CompanyList,
                meta: {
                    title: `Companies`
                }
            },
            {
                name: 'add-company',
                path: '/company/add',
                component: CompanyCreate,
                meta: {
                    title: `Create Company`
                }
            },
            {
                name: 'edit-company',
                path: '/company/edit/:id',
                meta: {
                    title: `Edit Company`
                },
                component: CompanyEdit
            }
        ]

    }

]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.auth.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router
