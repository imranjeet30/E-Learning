<template>
    <header class="card" style="border-radius:0">
        <div class="container" style="display:flex; gap:12px; align-items:center; justify-content:space-between;">
            <router-link to="/courses" style="font-weight:700">Ananas Academy</router-link>
            <nav style="display:flex; gap:12px; align-items:center;">
                <router-link to="/courses">Courses</router-link>
                <router-link to="/subscriptions">My Subscriptions</router-link>
                <router-link v-if="user?.role === 'admin'" to="/admin/courses/new">New Course</router-link>
                <template v-if="isAuth">
                    <span>Hi, {{ user?.name }}</span>
                    <button class="btn" @click="logout">Logout</button>
                </template>
                <template v-else>
                    <router-link to="/login" class="btn">Login</router-link>
                    <router-link to="/register" class="btn primary">Register</router-link>
                </template>
            </nav>
        </div>
    </header>
</template>
<script setup>
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'


const auth = useAuthStore()
const { user, isAuthenticated: isAuth } = storeToRefs(auth)
const logout = () => auth.logout()
</script>