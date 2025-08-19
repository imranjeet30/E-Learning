<template>
    <div class="grid">
        <h2>My Subscriptions</h2>
        <div class="grid">
            <div v-for="s in subs.list" :key="s.id" class="card"
                style="display:flex;justify-content:space-between;align-items:center;">
                <div>
                    <strong>{{ s.course?.title || s.course }}</strong>
                    <div>Status: {{ s.status }}</div>
                    <small>Valid: {{ s.start_date }} ➜ {{ s.end_date }}</small>
                </div>
                <router-link class="btn" :to="{ name: 'course-detail', params: { id: s.course_id || s.course?.id } }">Go
                    to course</router-link>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from 'vue'
import { useSubscriptionStore } from '@/stores/subscriptions'

const subs = useSubscriptionStore()

onMounted(() => subs.fetchMine())
</script>