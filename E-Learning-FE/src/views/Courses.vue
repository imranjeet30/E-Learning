<template>
    <div class="grid">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <h2>Courses</h2>
            <input class="input" placeholder="Search..." v-model="q" style="max-width:300px" />
        </div>
        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));">
            <article v-for="c in filtered" :key="c.id" class="card">
                <h3 style="margin:0 0 6px 0">{{ c.title }}</h3>
                <p style="min-height:48px">{{ c.description }}</p>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px">
                    <strong>₹ {{ Number(c.price).toFixed(2) }}</strong>
                    <router-link class="btn" :to="{ name: 'course-detail', params: { id: c.id } }">View</router-link>
                </div>
            </article>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCourseStore } from '@/stores/courses'


const store = useCourseStore()
const q = ref('')
const filtered = computed(() => store.list.filter(c => `${c.title} ${c.description}`.toLowerCase().includes(q.value.toLowerCase())))


onMounted(() => store.fetchAll())
</script>