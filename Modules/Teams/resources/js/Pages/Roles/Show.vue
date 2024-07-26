<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted} from 'vue'
import {useForm, usePage} from '@inertiajs/vue3'
import { ExclamationTriangleIcon } from '@heroicons/vue/20/solid'

const page = usePage()

const assetPath = computed(() => page.props.assetPath)

const props = defineProps(["role", "users", "permissions"])

const form = useForm({
    permissions: [],
})

onMounted(() => {
    for (let i = 0; i < Object.keys(props.role.permissions).length; i++) {
        form.permissions[props.role.permissions[i].id] = true
    }
})
const update = () => {
    form.post(route("management::roles.update", props.role))
    console.log(form.permissions)
}

</script>

<template>
    <AppLayout :title="`${role.name} Permissions`">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        {{ role.name }} Permissions
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Overview of all team members and roles.
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button type="button" @click="update" :disabled="role.system" class="btn btn-sm btn-primary" href="#">
                        Save
                    </button>
                </div>
            </div>
        </div>
        <!-- end: container -->
        <!-- begin: container -->
        <div class="container-fixed">
            <!-- begin: grid -->

            <div v-if="role.system" class="rounded-md bg-yellow-50 mb-6 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <ExclamationTriangleIcon class="h-5 w-5 text-yellow-400" aria-hidden="true" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">This role is readonly</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>
                                This is a role that is used in the application as a permission overriding role so there is no need to adjust the permissions.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5">
                <div v-for="(permission, group) in permissions" class="col-span-2">
                    <div class="flex flex-col gap-5 lg:gap-7.5">
                        <div class="card">
                            <div class="card-header" :data-collapse="`#permissions-${group}`">
                                <h3 class="card-title">
                                    {{ group }} - Permissies
                                </h3>
                            </div>

                            <div :id="`permissions-${group}`" class="card-body grid grid-cols-1 lg:grid-cols-2 gap-5 py-5 lg:py-7.5 transition-all duration-300 hidden">
                                <div v-for="p in permission" class="rounded-xl border p-4 flex items-center justify-between gap-2.5">
                                    <div class="flex items-center gap-3.5">
                                        <div class="flex flex-col gap-1">
                                            <span class="flex items-center gap-1.5 leading-none font-semibold text-sm text-gray-900">
                                                {{ trans(`permissions.${p.name}`) }}
                                            </span>
                                            <span class="text-2sm text-gray-600">
                                                {{ p.description }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="switch switch-sm">
                                        <input
                                            v-model="form.permissions[p.id]"
                                            :disabled="role.system"
                                            :checked="role.permissions.includes(p.id) || role.system"
                                            type="checkbox"
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: grid -->
        </div>
        <!-- end: container -->
    </AppLayout>
</template>
