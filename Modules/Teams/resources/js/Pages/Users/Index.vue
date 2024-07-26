<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed} from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const assetPath = computed(() => page.props.assetPath)

const props = defineProps(["users"])
</script>

<template>
    <AppLayout :title="trans('pages.management.users.header')">
        <!-- begin: container -->
        <div class="container-fixed" id="content_container">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Users
                    </h1>
                    <div class="flex items-center flex-wrap gap-1.5 font-medium">
                        <span class="text-md text-gray-600">
                            All Members:
                        </span>
                        <span class="text-md text-gray-800 font-semibold me-2">
                            {{ users.length }}
                        </span>
                        <span class="text-md text-gray-600">
                            Pro Licenses
                        </span>
                        <span class="text-md text-gray-800 font-semibold">
                            724
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: container -->
        <div class="container-fixed">
            <div class="max-w-7xl mx-auto">
                <div class="">

                </div>
            </div>
        </div>

    </AppLayout>
</template>
