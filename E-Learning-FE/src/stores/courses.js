import { defineStore } from 'pinia'
import api from '@/api/client'


export const useCourseStore = defineStore('courses', {
    state: () => ({ list: [], current: null, loading: false, error: null }),
    actions: {
        async fetchAll() { this.loading = true; try { const { data } = await api.get('/courses'); this.list = data } finally { this.loading = false } },
        async fetchOne(id) { this.loading = true; try { const { data } = await api.get(`/courses/${id}`); this.current = data; return data } finally { this.loading = false } },
        async create(payload) { const { data } = await api.post('/courses', payload); this.list.unshift(data); return data },
        async update(id, payload) { const { data } = await api.put(`/courses/${id}`, payload); this.current = data; this.list = this.list.map(c => c.id === id ? data : c); return data },
        async remove(id) { await api.delete(`/courses/${id}`); this.list = this.list.filter(c => c.id !== id) },
    }
})