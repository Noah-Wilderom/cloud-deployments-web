<script setup>
import AuthLayout from "@/Pages/Layout/AuthLayout.vue";
import { useForm } from "@inertiajs/vue3";
import {computed, onMounted, ref} from 'vue'
import { usePage } from '@inertiajs/vue3'

const enableStepperType = "enable"
const confirmStepperType = "confirm"
const recoveryCodesStepperType = "recovery-codes"

const page = usePage()
const assetPath = computed(() => page.props.assetPath)
const user = computed(() => page.props.auth.user)

const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const enableForm = useForm({
    force: true,
})
const confirmForm = useForm({
    code: "",
})

const stepper = ref("")

const enableTwoFactor = () => {
    enableForm.post(route("two-factor.enable"), {
        onFinish: () => {
            setTimeout(() => {
                Promise.all([
                    showQrCode(),
                    showSetupKey(),
                    showRecoveryCodes(),
                ])

                stepper.value = confirmStepperType
            }, 200);
        }
    })


}

const confirmTwoFactor = () => {
    confirmForm.post(route("two-factor.confirm"), {
        errorBag: "confirmTwoFactorAuthentication",
        onSuccess: () => {
            qrCode.value = null;
            setupKey.value = null;
            stepper.value = recoveryCodesStepperType
        },
    })
}


onMounted(() => {
    if (user.value?.two_factor_secret == null) {
        stepper.value = enableStepperType
    } else if (user.value?.two_factor_confirmed_at == null) {
        Promise.all([
            showQrCode(),
            showSetupKey(),
            showRecoveryCodes(),
        ])

        stepper.value = confirmStepperType
    } else {
        Promise.all([
            showRecoveryCodes(),
        ])

        stepper.value = recoveryCodesStepperType
    }
})

const showQrCode = () => {
    return axios.get(route('two-factor.qr-code')).then(response => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route('two-factor.secret-key')).then(response => {
        setupKey.value = response.data.secretKey;
    });
}

const showRecoveryCodes = () => {
    return axios.get(route('two-factor.recovery-codes')).then(response => {
        recoveryCodes.value = response.data;
    });
};

</script>

<template>
    <AuthLayout title="Two Factor">
        <form v-if="stepper === enableStepperType" @submit.prevent="enableTwoFactor" class="card-body flex flex-col gap-5 p-10">
            <div class="flex flex-col gap-1 mb-2 py-2">
                <h3 class="text-xl font-black">Two Factor</h3>
                <hr>
                <p class="text-xs font-light text-black py-2">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur dolores dolorum eveniet! Aut consectetur deleniti deserunt dolorum error explicabo id ipsa, ipsum itaque laboriosam molestiae possimus quia repudiandae voluptas voluptatem.
                </p>
            </div>
            <button type="submit" :disabled="enableForm.processing" class="btn btn-primary flex justify-center grow">
                {{ trans("pages.auth.enable_two_factor") }}
            </button>
        </form>

        <form v-if="stepper === confirmStepperType" @submit.prevent="confirmTwoFactor" class="card-body flex flex-col gap-5 p-10">
            <div class="flex flex-col gap-1 mb-2 py-2">
                <p class="text-sm font-light text-black py-2">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur dolores dolorum eveniet! Aut consectetur deleniti deserunt dolorum error explicabo id ipsa, ipsum itaque laboriosam molestiae possimus quia repudiandae voluptas voluptatem.
                </p>

                <div class="my-5">
                    <div v-html="qrCode" class="flex w-full justify-center items-center p-2 inline-block"></div>
                    <div class="text-sm font-light text-black py-2">
                        <div class="fw-bold text-gray-900 pb-2 text-center">
                            <code>{{ setupKey }}</code>
                        </div>
                        If you having trouble using the QR code, you can use the Setup key above.
                    </div>
                </div>

                <label class="form-label text-gray-900">
                    Code
                </label>
                <input class="input" type="text" name="code" v-model="confirmForm.code" />
                <span class="text-danger text-sm" v-if="confirmForm.errors.code">
                    {{ confirmForm.errors.code }}
                </span>

            </div>
            <button type="submit" :disabled="confirmForm.processing" class="btn btn-primary flex justify-center grow">
                {{ trans("pages.auth.confirm_two_factor") }}
            </button>
        </form>
        <div v-if="stepper === recoveryCodesStepperType" class="card-body flex flex-col gap-5 p-10">
            <div class="flex flex-col gap-1 mb-2 py-2">
                <p class="text-xs font-light text-black py-2">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur dolores dolorum eveniet! Aut consectetur deleniti deserunt dolorum error explicabo id ipsa, ipsum itaque laboriosam molestiae possimus quia repudiandae voluptas voluptatem.
                </p>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-900 dark:text-gray-100 rounded-lg">
                    <div v-for="code in recoveryCodes" :key="code">
                        {{ code }}
                    </div>
                </div>
            </div>

            <a :href="$route('dashboard')" class="btn btn-primary flex justify-center grow">
                {{ trans("pages.auth.confirm_two_factor") }}
            </a>
        </div>
    </AuthLayout>
</template>
