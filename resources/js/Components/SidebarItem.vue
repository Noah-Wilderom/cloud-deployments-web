<script setup>
import {computed, defineProps, reactive} from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const baseUrl = computed(() => page.props.baseUrl)

const props = defineProps({
    route: String,
    icon: String,
})

let currentActive = page.url === props.route.replaceAll(baseUrl.value, "")

if (page.url === "/" && props.route.replaceAll(baseUrl.value, "") === "") {
    currentActive = true
}
</script>

<template>

    <div :class="{ 'menu-item': true, 'here show active': currentActive }">
        <Link :href="props.route" :class="{
            'menu-label menu-link border border-transparent gap-[10px] pl-[10px] pr-[10px] py-[6px] menu-item-active:bg-secondary-active dark:menu-item-active:bg-coal-300 dark:menu-item-active:border-gray-100 menu-item-active:rounded-lg hover:bg-secondary-active dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg': true,
            'active here show': currentActive
        }" tabindex="0" preserve-state>
            <span class="menu-icon items-start text-gray-500 dark:text-gray-400 w-[20px]">
                <i :class="`ki-duotone ${props.icon} text-lg`"></i>
            </span>
            <span class="menu-title text-sm font-semibold text-gray-700">
                <slot/>
            </span>
        </Link>
    </div>

</template>
