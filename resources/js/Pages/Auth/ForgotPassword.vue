<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'


const page = usePage()
const assetPath = computed(() => page.props.assetPath)

</script>

<template>
    <AuthLayout title="Two Factor Challenge">
    </AuthLayout>
</template>
