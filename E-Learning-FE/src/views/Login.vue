<template>
    <div class="grid" style="max-width:420px;margin:0 auto;">
        <h2>Login</h2>
        <form class="grid" @submit.prevent="onSubmit">
            <input class="input" v-model="form.email" placeholder="Email" type="email" required />
            <input class="input" v-model="form.password" placeholder="Password" type="password" required />
            <button class="btn primary" :disabled="loading">Login</button>
            <p v-if="error" style="color:#dc2626">{{ error.message || error }}</p>
        </form>
    </div>
</template>
<script setup>
import { reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'


const auth = useAuthStore();
const { loading, error } = storeToRefs(auth)
const form = reactive({ email: '', password: '' })
const router = useRouter()


const onSubmit = async () => {
    try { await auth.login(form); router.push({ name: 'courses' }) } catch { }
}
</script>