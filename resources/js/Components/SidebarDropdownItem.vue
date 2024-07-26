<script setup>
import {computed, defineProps} from 'vue'
import {usePage} from "@inertiajs/vue3";
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    title: String,
    route: String,
})

const page = usePage()
const baseUrl = computed(() => page.props.baseUrl)

let currentActive = page.url === props.route.replaceAll(baseUrl.value, "")

if (page.url === "/" && props.route.replaceAll(baseUrl.value, "") === "") {
    currentActive = true
}
</script>
<template>
    <div :class="{ 'menu-item': true, 'here show active': currentActive }">
        <Link class="menu-link border border-transparent items-center grow menu-item-active:bg-secondary-active dark:menu-item-active:bg-coal-300 dark:menu-item-active:border-gray-100 menu-item-active:rounded-lg hover:bg-secondary-active dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg gap-[14px] pl-[10px] pr-[10px] py-[8px]"
           :href="props.route">
            <span class="menu-bullet flex w-[6px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-x-1/2 before:-translate-y-1/2 menu-item-active:before:bg-primary menu-item-hover:before:bg-primary"></span>
            <span class="menu-title text-2sm font-medium text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-link-hover:!text-primary">
                {{ props.title }}
            </span>
        </Link>
    </div>
</template>
