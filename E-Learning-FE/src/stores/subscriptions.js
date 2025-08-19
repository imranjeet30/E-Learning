import { defineStore } from 'pinia'
import api from '@/api/client'


export const useSubscriptionStore = defineStore('subscriptions', {
    state: () => ({ list: [], loading: false }),
    actions: {
        async fetchMine() { this.loading = true; try { const { data } = await api.get('/subscriptions'); this.list = data } finally { this.loading = false } },
    }
})