<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { XCircleIcon } from '@heroicons/vue/24/outline'


const page = usePage()

const assetPath = computed(() => page.props.assetPath)

const form = useForm({
    email: null,
    password: null,
})

</script>

<template>
    <AuthLayout title="Login">
        <form @submit.prevent="form.post($route('login'))" class="card-body flex flex-col gap-5 p-10">
            <div class="flex flex-col gap-1">
                <label class="form-label text-gray-900">
                    {{ trans("pages.auth.email") }}
                </label>
                <input class="input" placeholder="email@email.com" type="text" v-model="form.email" />
                <span class="text-danger text-xs" v-if="form.errors.email">
                    {{ form.errors.email }}
                </span>
            </div>
            <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between gap-1">
                    <label class="form-label text-gray-900">
                        {{ trans("pages.auth.password") }}
                    </label>
                    <a class="text-2sm link shrink-0" href="#">
                        {{ trans("pages.auth.forgot_password") }}
                    </a>
                </div>
                <label class="input" data-toggle-password="true">
                    <input placeholder="xxxxxxxxx" type="password" v-model="form.password"/>

                    <button type="button" class="btn btn-icon" data-toggle-password-trigger="true">
                        <i class="ki-filled ki-eye text-gray-500 toggle-password-active:hidden"></i>
                        <i class="ki-filled ki-eye-slash text-gray-500 hidden toggle-password-active:block"></i>
                    </button>
                </label>
                <span class="text-danger text-xs" v-if="form.errors.password">
                    {{ form.errors.password }}
                </span>
            </div>
            <button type="submit" :disabled="form.processing" class="btn btn-primary flex justify-center grow">
                {{ trans("pages.auth.login") }}
            </button>
        </form>
    </AuthLayout>
</template>
