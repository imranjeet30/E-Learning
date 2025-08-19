<template>
    <div v-if="course" class="grid" style="grid-template-columns: 2fr 1fr; align-items:start;">
        <div class="card">
            <h2>{{ course.title }}</h2>
            <p>{{ course.description }}</p>
        </div>
        <div class="card">
            <h3>Purchase</h3>
            <p style="margin:6px 0">Price: <strong>₹ {{ Number(course.price).toFixed(2) }}</strong></p>
            <label>Gateway</label>
            <select v-model="gateway" class="input">
                <option value="razorpay">Razorpay</option>
                <option value="stripe">Stripe</option>
                <option value="paypal">PayPal</option>
            </select>
            <button class="btn primary" style="margin-top:12px" @click="goCheckout">Continue</button>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCourseStore } from '@/stores/courses'


const route = useRoute(); const router = useRouter();
const store = useCourseStore();
const gateway = ref('razorpay')


onMounted(async () => { await store.fetchOne(route.params.id) })
const course = store.current


const goCheckout = () => router.push({ name: 'checkout', params: { id: course.id }, query: { gateway: gateway.value } })
</script>
