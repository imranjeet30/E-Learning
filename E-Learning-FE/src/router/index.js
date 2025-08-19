import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'


const Login = () => import('@/views/Login.vue')
const Register = () => import('@/views/Register.vue')
const Courses = () => import('@/views/Courses.vue')
const CourseDetail = () => import('@/views/CourseDetail.vue')
const Checkout = () => import('@/views/Checkout.vue')
const MySubscriptions = () => import('@/views/MySubscriptions.vue')
const AdminCourseForm = () => import('@/views/AdminCourseForm.vue')


const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', redirect: '/courses' },
        { path: '/login', name: 'login', component: Login, meta: { guest: true } },
        { path: '/register', name: 'register', component: Register, meta: { guest: true } },
        { path: '/courses', name: 'courses', component: Courses },
        { path: '/courses/:id', name: 'course-detail', component: CourseDetail, props: true },
        { path: '/checkout/:id', name: 'checkout', component: Checkout, meta: { requiresAuth: true }, props: true },
        { path: '/subscriptions', name: 'subscriptions', component: MySubscriptions, meta: { requiresAuth: true } },
        { path: '/admin/courses/new', name: 'admin-course-new', component: AdminCourseForm, meta: { requiresAuth: true, role: 'admin' } },
        { path: '/admin/courses/:id', name: 'admin-course-edit', component: AdminCourseForm, meta: { requiresAuth: true, role: 'admin' }, props: true },
    ],
})


router.beforeEach((to, from, next) => {
    const auth = useAuthStore()
    if (to.meta.requiresAuth && !auth.isAuthenticated) return next({ name: 'login' })
    if (to.meta.guest && auth.isAuthenticated) return next({ name: 'courses' })
    if (to.meta.role && auth.user?.role !== to.meta.role) return next({ name: 'courses' })
    return next()
})


export default router