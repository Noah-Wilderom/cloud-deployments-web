<script setup>
import {nextTick, onMounted, ref, watch} from 'vue'
import { CheckCircleIcon } from '@heroicons/vue/24/outline'
import { InformationCircleIcon } from '@heroicons/vue/24/outline'
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
const page = usePage()

const flashNotification = computed(() => page.props.flash.notification)

const show = ref(true)
const notifications = ref([])

watch(flashNotification, (newNotification) => {
    if(newNotification?.type !== undefined)  {
        SetNotification(newNotification)
    }
})

onMounted(() => {
    if(flashNotification.value?.type !== undefined)  {
        SetNotification(flashNotification)
    }

    nextTick(() => {
        Echo.private("notifications")
            .listen('.notification', (e) => {
                SetNotification(e.notification)
            })
    });
});

const SetNotification = (notification) => {
    notifications.value.push(notification)
    setTimeout(() => {
        let index = notifications.value.indexOf(notification);
        if (index !== -1) {
            notifications.value.splice(index, 1);
        }
    }, 5 * 1000); // 5 seconds
}

</script>

<template>
    <!-- Global notification live region, render this permanently at the end of the document -->
    <div aria-live="assertive" class="z-10 pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <transition v-for="notification in notifications" enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <CheckCircleIcon v-if="notification.type === 'success'" class="h-6 w-6 text-green-400" aria-hidden="true" />
                                <InformationCircleIcon v-if="notification.type === 'info'" class="h-6 w-6 text-blue-400" aria-hidden="true" />
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium light:text-gray-900 dark:text-black">{{ notification.title }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ notification.description }}</p>
                            </div>

                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
