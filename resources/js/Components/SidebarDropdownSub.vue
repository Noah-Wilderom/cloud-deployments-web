<script setup>
import {computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    route: String,
    title: String,
})

const page = usePage()
const baseUrl = computed(() => page.props.baseUrl)

let currentActive = page.url === props.route.replaceAll(baseUrl.value, "")

if (page.url === "/" && props.route.replaceAll(baseUrl.value, "") === "") {
    currentActive = true
}
</script>

<template>

    <div :class="{ 'menu-item': true, 'here show active': currentActive}" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div
            class="menu-link border border-transparent grow cursor-pointer gap-[14px] pl-[10px] pr-[10px] py-[8px]"
            tabindex="0">
            <span class="menu-bullet flex w-[6px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-x-1/2 before:-translate-y-1/2 menu-item-active:before:bg-primary menu-item-hover:before:bg-primary"></span>
            <span class="menu-title text-2sm font-medium mr-1 text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-link-hover:!text-primary">
                {{ props.title }}
            </span>
            <span class="menu-arrow text-gray-400 w-[20px] shrink-0 justify-end ml-1 mr-[-10px]">
                <i class="ki-filled ki-plus text-2xs menu-item-show:hidden"></i>
                <i class="ki-filled ki-minus text-2xs hidden menu-item-show:inline-flex"></i>
            </span>
        </div>
        <div class="menu-accordion gap-0.5 relative before:absolute before:left-[32px] pl-[22px] before:top-0 before:bottom-0 before:border-l before:border-gray-200">
            <slot/>
        </div>
    </div>
</template>
