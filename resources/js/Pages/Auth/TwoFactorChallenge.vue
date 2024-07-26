<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import { computed, ref } from 'vue'
import {useForm, usePage} from '@inertiajs/vue3'


const page = usePage()
const assetPath = computed(() => page.props.assetPath)

const codes = ref([])

const form = useForm({
    code: null,
})

const submit = () => {
    form.post(route('two-factor.login'))
}
</script>

<template>
    <AuthLayout title="Two Factor Challenge">
        <form @submit.prevent="submit" class="card-body flex flex-col gap-5 p-10">
            <img alt="image" class="dark:hidden h-20 mb-2" :src="assetPath + '/media/illustrations/34.svg'"/>
            <img alt="image" class="light:hidden h-20 mb-2" :src="assetPath + '/media/illustrations/34-dark.svg'"/>
            <div class="text-center mb-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-5">
                    Verify your phone
                </h3>
                <div class="flex flex-col">
                    <span class="text-2sm text-gray-600 font-medium mb-1.5">
                        Enter the verification code we sent to
                    </span>
                    <a class="text-sm font-semibold text-gray-900" href="#">
                        ****** 7859
                    </a>
                </div>
            </div>
            <div class="flex flex-wrap justify-center gap-2">
                <input v-model="form.code" class="input" maxlength="6" name="code" autocomplete="one-time-code" type="text" autofocus=""/>
            </div>
            <button type="submit" class="btn btn-primary flex justify-center grow">
                Continue
            </button>
        </form>
    </AuthLayout>
</template>
