import { defineStore } from 'pinia'
import api from '@/api/client'


export const usePaymentStore = defineStore('payments', {
    state: () => ({ lastOrder: null, capturing: false, error: null }),
    actions: {
        async initiate({ courseId, gateway, amount }) {
            const { data } = await api.post('/payments/initiate', { course_id: courseId, gateway, amount })
            this.lastOrder = data
            return data
        },
        async capture({ gateway, externalId, courseId }) {
            this.capturing = true; this.error = null
            try {
                const { data } = await api.post('/payments/capture', { gateway, external_id: externalId, course_id: courseId })
                return data
            } catch (e) { this.error = e.response?.data || e.message; throw e } finally { this.capturing = false }
        }
    }
})