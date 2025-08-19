<template>
    <div class="grid" style="max-width:640px;margin:0 auto;">
        <h2>{{ id ? 'Edit Course' : 'New Course' }}</h2>
        <form class="grid" @submit.prevent="save">
            <input class="input" v-model="form.title" placeholder="Title" required />
            <textarea class="input" v-model="form.description" placeholder="Description" rows="5"></textarea>
            <input class="input" v-model.number="form.price" placeholder="Price" type="number" step="0.01" min="0"
                required />
            <button class="btn primary">Save</button>
        </form>
    </div>
</template>
<script setup>
import { onMounted, reactive, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCourseStore } from '@/stores/courses'


const route = useRoute(); const router = useRouter();
const id = computed(() => route.params.id ? Number(route.params.id) : null)
const store = useCourseStore()
const form = reactive({ title: '', description: '', price: 0 })


onMounted(async () => {
    if (id.value) {
        const c = await store.fetchOne(id.value); Object.assign(form, { title: c.title, description: c.description, price: c.price })
    }
})


const save = async () => {
    if (id.value) await store.update(id.value, form); else await store.create(form)
    router.push({ name: 'courses' })
}
</script>