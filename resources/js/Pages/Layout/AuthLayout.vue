<script setup>
import { Head } from '@inertiajs/vue3';
import { computed, defineProps, onMounted, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import KTComponent from '../../../metronic/core/index.spa';
import KTLayout from '../../../metronic/app/layouts/demo1.js';

const page = usePage()

const assetPath = computed(() => page.props.assetPath)

const props = defineProps({
    title: {
        type: String,
        required: true,
    }
})

onMounted(() => {
    nextTick(() => {
        KTComponent.init();
        KTLayout.init();
    });
});
</script>

<template>
    <Head :title="props.title" />

    <div class="flex h-full dark:bg-coal-500">
        <!--begin::Page layout-->
        <div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">

            <div class="w-full flex flex-col justify-center">
                <div class="text-center mb-2.5">
                    <div class="flex items-center justify-center p-4">
                        <img class="h-10 w-full light:hidden" alt="Logo" :src="assetPath + '/logos/logo.svg'">
                        <img class="h-10 w-full dark:hidden" alt="Logo" :src="assetPath + '/logos/logo.svg'">
                    </div>

                </div>

                <div class="card max-w-[370px] w-full border-2 mx-auto">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>
