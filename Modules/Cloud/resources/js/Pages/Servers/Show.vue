<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref} from 'vue'
import {usePage } from '@inertiajs/vue3'

const tab = ref(null)
const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)

onMounted(() => {
    //
})

defineProps(["server"])
</script>

<template>
    <AppLayout :title="server.name + '- Server'">
        <div class="dark:text-white">
            {{ server }}
        </div>
    </AppLayout>
</template>
