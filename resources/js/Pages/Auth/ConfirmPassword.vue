<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'


const page = usePage()

const assetPath = computed(() => page.props.assetPath)

const form = useForm({
    email: null,
    password: null,
})

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => {
            form.reset()
        }
    })
}
</script>

<template>
    <AuthLayout title="Login">
        <form @submit.prevent="submit" class="card-body flex flex-col gap-5 p-10">
            <div class="flex flex-col gap-1">
                <h3 class="text-xl font-black">
                    {{ trans("pages.auth.confirm_password") }}
                </h3>
                <hr>
                <p class="text-xs font-light text-black py-2">
                    This is a secure area of the application. Please confirm your password before continuing.
                </p>
            </div>
            <div class="flex flex-col gap-1">
                <label class="input" data-toggle-password="true">
                    <input placeholder="" type="password" autocomplete="off" v-model="form.password"/>

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
                {{ trans("pages.auth.submit_confirm_password") }}
            </button>
        </form>
    </AuthLayout>
</template>
