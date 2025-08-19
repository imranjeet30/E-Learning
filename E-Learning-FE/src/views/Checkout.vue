<template>
    <h2>Checkout</h2>
    <div class="card">
        <p>Course #{{ courseId }}</p>
        <label>Gateway</label>
        <select v-model="gateway" class="input">
            <option value="razorpay">Razorpay</option>
            <option value="stripe">Stripe</option>
            <option value="paypal">PayPal</option>
        </select>
        <button class="btn primary" style="margin-top:12px" @click="start">Pay Now</button>
        <div v-if="order">
            <hr />
            <h4>Next step</h4>
            <div v-if="gateway === 'stripe'">
                <p>Use Stripe Elements on client to confirm using client_secret:</p>
                <code>{{ order.client_secret }}</code>
            </div>
            <div v-else-if="gateway === 'razorpay'">
                <p>Open Razorpay Checkout with order_id and key_id.</p>
                <code>{{ order.order?.id }}</code>
            </div>
            <div v-else>
                <p>Redirect user to PayPal approval URL:</p>
                <a :href="order.approve_link" target="_blank">Approve Payment</a>
            </div>
        </div>
    </div>
    </div>
</template>
<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { usePaymentStore } from '@/stores/payments'
import { useCourseStore } from '@/stores/courses'


const route = useRoute()
const courseId = Number(route.params.id)
const gateway = ref(route.query.gateway || 'razorpay')
const payments = usePaymentStore()
const courses = useCourseStore()


const order = ref(null)


const start = async () => {
    const course = courses.list.find(c => c.id === courseId) || courses.current || (await courses.fetchOne(courseId))
    const res = await payments.initiate({ courseId, gateway: gateway.value, amount: course.price })
    order.value = res
}
</script>