<script setup>
import {computed, defineProps} from 'vue'
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    route: String,
    title: String,
    icon: String,
})

const page = usePage()
const baseUrl = computed(() => page.props.baseUrl)

let currentActive = route().current(props.route)
</script>

<template>
    <div :class="{ 'menu-item': true, 'here show ': currentActive }" data-menu-item-toggle="accordion" data-menu-item-trigger="click">
        <div
            class="menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] pl-[10px] pr-[10px] py-[6px]"
            tabindex="0">
            <span class="menu-icon items-start text-gray-500 dark:text-gray-400 w-[20px]">
                <i :class="`ki-filled ${props.icon} text-lg`"></i>
            </span>
            <span
                class="menu-title text-sm font-semibold text-gray-700 menu-item-active:text-primary menu-link-hover:!text-primary">
                {{ props.title }}
            </span>
            <span class="menu-arrow text-gray-400 w-[20px] shrink-0 justify-end ml-1 mr-[-10px]">
                <i class="ki-filled ki-plus text-2xs menu-item-show:hidden"></i>
                <i class="ki-filled ki-minus text-2xs hidden menu-item-show:inline-flex"></i>
            </span>
        </div>
        <div
            class="menu-accordion gap-0.5 pl-[10px] relative before:absolute before:left-[20px] before:top-0 before:bottom-0 before:border-l before:border-gray-200">
            <slot/>
        </div>
    </div>
</template>
