<script setup>
import { defineProps } from 'vue'

const props = defineProps({
    title: String,
})
</script>

<template>
    <div class="menu-item pt-2.25 pb-px">
        <span class="menu-heading uppercase text-2sm font-semibold text-gray-500 pl-[10px] pr-[10px]">
            {{ props.title }}
        </span>
    </div>
</template>
