import { defineStore } from 'pinia'
import api from '@/api/client'


export const useAuthStore = defineStore('auth', {
    state: () => ({ token: null, user: null, loading: false, error: null }),
    getters: { isAuthenticated: (s) => !!s.token },
    persist: true,
    actions: {
        async register(payload) {
            this.loading = true; this.error = null
            try {
                const { data } = await api.post('/register', payload)
                this.token = data.token; this.user = data.user ?? (await this.profile())
            } catch (e) { this.error = e.response?.data || e.message; throw e } finally { this.loading = false }
        },
        async login(payload) {
            this.loading = true; this.error = null
            try {
                const { data } = await api.post('/login', payload)
                this.token = data.token; this.user = data.user ?? (await this.profile())
            } catch (e) { this.error = e.response?.data || e.message; throw e } finally { this.loading = false }
        },
        async profile() {
            try { const { data } = await api.get('/profile'); this.user = data; return data } catch { return null }
        },
        async logout() {
            try { await api.post('/logout') } catch { }
            this.$reset()
        }
    }
})