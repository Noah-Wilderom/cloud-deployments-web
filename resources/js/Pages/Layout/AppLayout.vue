<script setup>
import { Head } from '@inertiajs/vue3';
import { computed, defineProps, onMounted, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Sidebar from "@/Components/Sidebar.vue";
import Header from "@/Components/Header.vue";
import Notifications from "../../Components/Notifications.vue";
import KTComponent from '../../../metronic/core/index.spa';
import KTLayout from '../../../metronic/app/layouts/demo1.js';

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const appName = computed(() => page.props.appName)

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

<style>
.channel-stats-bg {
    background-image: url('/assets/media/images/2600x1600/bg-3.png');
}

.dark .channel-stats-bg {
    background-image: url('/assets/media/images/2600x1600/bg-3-dark.png');
}

.entry-callout-bg {
    background-image: url('/assets/media/images/2600x1600/2.png');
}

.dark .entry-callout-bg {
    background-image: url('/assets/media/images/2600x1600/2-dark.png');
}
</style>

<template>
    <Head :title="props.title" />

    <div class="flex h-full bg-[#fefefe] dark:bg-coal-500">

        <div class="flex h-full grow">
            <Sidebar/>
            <div class="wrapper flex grow flex-col">
                <Header />
                <main class="grow content pt-5" id="content" role="content">
                    <slot/>
                </main>
                <footer class="footer">
                    <!-- begin: container -->
                    <div class="container-fixed">
                        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5">
                            <div class="flex order-2 md:order-1 gap-2 font-normal text-2sm">
                                <span class="text-gray-500">
                                    {{ new Date().getFullYear() }}©
                                </span>

                                <a class="text-gray-600 hover:text-primary" href="#">
                                    {{ appName }}
                                </a>
                            </div>
                            <nav class="flex order-1 md:order-2 gap-4 font-normal text-2sm text-gray-600">
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs">
                                    Docs
                                </a>
                                <a class="hover:text-primary" href="https://1.envato.market/EA4JP">
                                    Purchase
                                </a>
                                <a class="hover:text-primary" href="https://devs.keenthemes.com/question/understanding-metronic-themeforest-license-a-comprehensive-guide">
                                    FAQ
                                </a>
                                <a class="hover:text-primary" href="https://devs.keenthemes.com">
                                    Support
                                </a>
                                <a class="hover:text-primary" href="https://devs.keenthemes.com/question/understanding-metronic-themeforest-license-a-comprehensive-guide">
                                    License
                                </a>
                            </nav>
                        </div>
                    </div>
                    <!-- end: container -->
                </footer>
            </div>
        </div>
    </div>

    <Notifications />
</template>


