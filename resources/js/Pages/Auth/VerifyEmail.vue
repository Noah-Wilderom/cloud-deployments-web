<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import {computed, reactive, ref} from 'vue'
import {useForm, usePage} from '@inertiajs/vue3'
import moment from "moment";


const page = usePage()
const assetPath = computed(() => page.props.assetPath)
const user = computed(() => page.props.auth.user)

let props = defineProps({
    timeLeft: Number,
})

let dateText = ref("")
let date = moment((props.timeLeft > 0 ? props.timeLeft : 0) * 1000);
let canResend = false

if(date.seconds() > 0) {
    dateText = ref(date.format("mm:ss"));
    setInterval(() => {
        if (date.format("mm:ss") === "00:00") {
            canResend = true
            dateText.value = ""
            return;
        }

        date = date.clone().subtract(1, 'seconds');
        dateText.value = date.format('mm:ss');
    }, 1000);
} else {
    canResend = true
}

const resend = () => {
    if(canResend) {
        console.log("RESEND")
        useForm({}).post(route("verification.send"))
        date = date.clone().add(5, 'minutes')
        canResend = false
    }
}

</script>

<template>
    <AuthLayout :title="trans('pages.auth.verify_email')">
        <div class="card-body p-10" id="check_email_form">
            <div class="flex justify-center py-10">
                <img alt="image" class="dark:hidden max-h-[130px]" :src="assetPath + '/media/illustrations/30.svg'"/>
                <img alt="image" class="light:hidden max-h-[130px]" :src="assetPath + '/media/illustrations/30-dark.svg'"/>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 text-center mb-3">
                Check your email
            </h3>
            <div class="text-2sm font-medium text-center text-gray-600 mb-7.5">
                Please click the link sent to your email
                <a class="text-2sm text-gray-800 font-medium hover:text-primary-active" href="#">
                    {{ user.email }}
                </a>
                <br/>
                to verify your account. Thank you
            </div>
            <div class="flex items-center justify-center gap-1">
                <span class="text-xs font-medium text-gray-600">
                    Didn’t receive an email?
                </span>
                <span v-on:click="resend" class="text-xs font-medium link" :class="{ 'cursor-pointer': canResend, 'cursor-not-allowed': !canResend }">
                    Resend
                </span>
            </div>
            <div class="flex items-center justify-center gap-1">
                <span v-text="dateText" class="text-xs text-muted">
                </span>
            </div>
        </div>
    </AuthLayout>
</template>
