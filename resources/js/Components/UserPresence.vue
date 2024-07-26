<script setup>
import {onBeforeUnmount, nextTick, onMounted, ref, computed, watch} from "vue";
import {router, usePage, useRemember} from '@inertiajs/vue3'

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const authUser = computed(() => page.props.auth.user)

const presentUsers = ref([])

const joinChannel = () => {
    Echo.join("user-presence")
        .here((users) => {
            presentUsers.value = users.filter((user) => {
                return user.id !== authUser.value.id
            })

            console.log(presentUsers.value)
        })
        .joining((user) => {
            if (presentUsers.value.indexOf(user, 0) === -1) {
                presentUsers.value.push(user)
            }
        })
        .leaving((user) => {
            presentUsers.value = presentUsers.value.filter((presentUser) => {
                return presentUser.id !== user.id
            })
        })
        .error((err) => {
            console.error(err)
        })
}

const leaveChannel = () => {
    Echo.leave("user-presence")
}

onBeforeUnmount(() => {
    leaveChannel()
})

onMounted(() => {
    console.log("onMounted")
    nextTick(() => {
        joinChannel()
    });
});


</script>

<template>
    <div class="dropdown" data-dropdown="true" data-dropdown-offset="10px, 10px" data-dropdown-placement="bottom-end" data-dropdown-trigger="click|lg:click">
        <button class="dropdown-toggle btn btn-icon btn-icon-lg size-9 rounded-full hover:bg-primary-light hover:text-primary dropdown-open:bg-primary-light dropdown-open:text-primary text-gray-500">
            <i class="ki-filled ki-element-11">
            </i>
        </button>
        <div class="dropdown-content light:border-gray-300 w-full max-w-[320px]">
            <div class="flex items-center justify-between gap-2.5 text-2xs text-gray-600 font-medium px-5 py-3 border-b border-b-gray-200">
                <span>
                    Werknemers
                </span>
                <span>
                    Status
                </span>
            </div>
            <div class="flex flex-col scrollable-y-auto max-h-[400px] divide-y divide-gray-200">
                <div v-for="user in presentUsers" class="flex items-center justify-between flex-wrap gap-2 px-5 py-3.5">
                    <div class="flex items-center flex-wrap gap-2">
                        <div class="flex items-center justify-center shrink-0 rounded-full size-10">
                            <span class="badge badge-dot size-4 badge-success"></span>
                        </div>
                        <div class="flex flex-col">
                            <a class="text-2sm font-semibold text-gray-900 hover:text-primary-active" href="#">
                                {{ user.name }}
                            </a>
                            <span class="text-2xs font-medium text-gray-600">
                                {{ user.role }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 lg:gap-5">
                        <button type="button" class="btn btn-xs btn-light">Chat</button>
                    </div>
                </div>
            </div>
            <div class="grid p-5 border-t border-t-gray-200">
                <a class="btn btn-sm btn-light justify-center" href="html/demo1/account/api-keys.html">
                    Go to Apps
                </a>
            </div>
        </div>
    </div>
</template>
