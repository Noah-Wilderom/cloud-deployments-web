<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref, watch} from 'vue'
import {Link, router, useForm, usePage} from '@inertiajs/vue3'
import { KTModal } from "@metronic/core/index.ts";

const tab = ref(null)
const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)
const queryParams = computed(() => page.props.queryParams)

const basicSettingsForm = useForm({
    name: auth.value.name,
})

onMounted(() => {
    KTModal.init()
    tab.value = queryParams.value?.tab ?? "general"
    console.log(tab.value)
})

watch(tab, () => {
    //
})

const saveBasicSettings = () => {
    basicSettingsForm.post(route("userportal::profile.update"))
}

const newPasswordForm = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
})

const saveNewPassword = () => {
    newPasswordForm.post(route("userportal::profile.change-password"))
    newPasswordForm.reset()
}

const props = defineProps({
    integrations: Object,
    sshKeys: Object,
})

const createSshKeyForm = useForm({
    name: null,
    public_key: null,
})

const openCreateSshKey = () => {
    const modalEl = document.getElementById("create_ssh_key");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const createSshKey = () => {
    createSshKeyForm.post(route("userportal::profile.ssh-key"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_ssh_key");
            const modal = KTModal.getInstance(modalEl);
            modal.hide();

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["sshKeys"]
            })
        }
    })
}

const selectedProvider = ref(null);
const openConnectProvider = (provider) => {
    selectedProvider.value = provider
    const modalEl = document.getElementById("connect_modal");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const connectProviderForm = useForm({
    token: null,
})

const connectProvider = () => {
    if(selectedProvider === null) {
        console.error("selected provider is null")
        return
    }

    console.log(selectedProvider.value.connect_url)

    connectProviderForm.post(selectedProvider.value.connect_url, {
        onSuccess: (resp) => {
            selectedProvider.value = null
            const modalEl = document.getElementById("connect_modal");
            const modal = KTModal.getInstance(modalEl);
            modal.hide();

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["integrations"]
            })
        }
    })
}
</script>

<style>
.singl-sign-on-bg {
    background-image: url('/assets/media/images/2600x1600/bg-2.png');
}
.dark .singl-sign-on-bg {
    background-image: url('/assets/media/images/2600x1600/bg-2-dark.png');
}
.sso-active:has(:checked) {
    background-image: url('/assets/media/images/2600x1600/bg-1.png');
}
.dark .sso-active:has(:checked) {
    background-image: url('/assets/media/images/2600x1600/bg-1-dark.png');
}

.user-access-bg {
    background-image: url('/assets/media/images/2600x1200/bg-5.png');
}
.dark .user-access-bg {
    background-image: url('/assets/media/images/2600x1200/bg-5-dark.png');
}
</style>

<template>
    <AppLayout title="Profiel">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Profiel instellingen
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Intuitive Access to In-Depth Customization
                    </div>
                </div>
            </div>
        </div>
        <!-- end: container -->

        <!-- end: container -->
        <div class="container-fixed">
            <div class="flex flex-nowrap items-center lg:items-end justify-between border-b border-b-gray-200 dark:border-b-coal-100 gap-6 mb-5 lg:mb-10">
                <div class="grid">
                    <div class="scrollable-x-auto">
                        <div class="menu gap-3" data-menu="true">
                            <div :class="{ 'active': tab === 'general'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('userportal::profile.index', {'tab': 'general'})" method="GET" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2" tabindex="0">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        General
                                    </span>
                                </Link>
                            </div>
                            <div :class="{ 'active': tab === 'ssh-keys'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('userportal::profile.index', {'tab': 'ssh-keys'})" method="GET" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2" tabindex="0">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        SSH Keys
                                    </span>
                                </Link>
                            </div>
                            <div :class="{ 'active': tab === 'integrations'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('userportal::profile.index', {'tab': 'integrations'})" method="GET" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2" tabindex="0">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Integrations
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center lg:pb-4 gap-2.5">
                    <button class="btn btn-sm btn-icon btn-light">
                        <i class="ki-filled ki-messages">
                        </i>
                    </button>
                    <div class="dropdown" data-dropdown="true" data-dropdown-placement="bottom-end" data-dropdown-trigger="click">
                        <button class="dropdown-toggle btn btn-sm btn-icon btn-light">
                            <i class="ki-filled ki-dots-vertical">
                            </i>
                        </button>
                        <div class="dropdown-content menu-default w-full max-w-[220px]">
                            <div class="menu-item" data-dropdown-dismiss="true">
                                <button class="menu-link" data-modal-toggle="#share_profile_modal">
                                    <span class="menu-icon">
                                        <i class="ki-filled ki-coffee"></i>
                                    </span>
                                    <span class="menu-title">
                                        Share Profile
                                    </span>
                                </button>
                            </div>
                            <div class="menu-item" data-dropdown-dismiss="true">
                                <a class="menu-link" data-modal-toggle="#give_award_modal" href="#">
                                    <span class="menu-icon">
                                        <i class="ki-filled ki-award"></i>
                                    </span>
                                    <span class="menu-title">
                                        Give Award
                                    </span>
                                </a>
                            </div>
                            <div class="menu-item" data-dropdown-dismiss="true">
                                <button class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-filled ki-chart-line"></i>
                                    </span>
                                    <span class="menu-title">
                                        Stay Updated
                                    </span>
                                    <label class="switch switch-sm">
                                        <input name="check" type="checkbox" value="1">

                                    </label>
                                </button>
                            </div>
                            <div class="menu-item" data-dropdown-dismiss="true">
                                <button class="menu-link" data-modal-toggle="#report_user_modal">
                                    <span class="menu-icon">
                                        <i class="ki-filled ki-information-2"></i>
                                    </span>
                                    <span class="menu-title">
                                        Report User
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- begin: container -->
        <div v-if="tab === 'general'" class="container-fixed">
            <div class="flex grow gap-5 lg:gap-7.5">
                <div class="hidden lg:block w-[230px] shrink-0">
                    <div class="w-[230px]" data-sticky="true" data-sticky-animation="true" data-sticky-class="fixed top-[calc(var(--tw-header-height)+1.875rem)] z-10 left-auto" data-sticky-name="scrollspy" data-sticky-offset="200">
                        <div class="flex flex-col grow relative before:absolute before:left-[11px] before:top-0 before:bottom-0 before:border-l before:border-gray-200" data-scrollspy="true" data-scrollspy-offset="80px|lg:110px" data-scrollspy-target="body">
                            <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 active border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#basic_settings">
           <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
           </span>
                                Basic Settings
                            </a>
                            <div class="flex flex-col">
                                <div class="pl-6 pr-2.5 py-2.5 text-2sm font-semibold text-gray-900">
                                    Authentication
                                </div>
                                <div class="flex flex-col">
                                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#auth_password">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                                        Password
                                    </a>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="pl-6 pr-2.5 py-2.5 text-2sm font-semibold text-gray-900">
                                    Advanced Settings
                                </div>
                                <div class="flex flex-col">
                                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#advanced_settings_preferences">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                                        Preferences
                                    </a>
                                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#advanced_settings_notifications">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                                        Notifications
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-stretch grow gap-5 lg:gap-7.5">
                    <form @submit.prevent="saveBasicSettings" class="card pb-2.5">
                        <div class="card-header" id="basic_settings">
                            <h3 class="card-title">
                                Basic Settings
                            </h3>
                        </div>
                        <div class="card-body grid gap-5">
                            <div class="w-full">
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="form-label flex items-center gap-1 max-w-56">
                                        Name
                                    </label>
                                    <div class="w-full">
                                        <input class="input" type="text" v-model="basicSettingsForm.name" />
                                        <span class="text-danger text-xs" v-if="basicSettingsForm.errors.name">
                                            {{ basicSettingsForm.errors.name}}
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="flex justify-end pt-2.5">
                                <button type="submit" class="btn btn-primary">
                                    Opslaan
                                </button>
                            </div>
                        </div>
                    </form>
                    <form @submit.prevent="saveNewPassword" class="card">
                        <div class="card-header" id="auth_password">
                            <h3 class="card-title">
                                Password
                            </h3>
                        </div>
                        <div class="card-body grid gap-5">
                            <div class="w-full">
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="form-label max-w-56">
                                        Current Password
                                    </label>
                                    <div class="w-full">
                                        <input v-model="newPasswordForm.current_password" class="input" placeholder="Your current password" type="password">
                                        <span v-if="newPasswordForm.errors.current_password" class="text-danger text-xs">
                                            {{ newPasswordForm.errors.current_password }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="form-label max-w-56">
                                        New Password
                                    </label>
                                    <div class="w-full">
                                        <input v-model="newPasswordForm.password" class="input" placeholder="New password" type="password">
                                        <span v-if="newPasswordForm.errors.password" class="text-danger text-xs">
                                            {{ newPasswordForm.errors.password }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="form-label max-w-56">
                                        Confirm New Password
                                    </label>
                                    <div class="w-full">
                                        <input v-model="newPasswordForm.password_confirmation" class="input" placeholder="Confirm new password" type="password">
                                        <span v-if="newPasswordForm.errors.password_confirmation" class="text-danger text-xs">
                                            {{ newPasswordForm.errors.password_confirmation}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2.5">
                                <button type="submit" class="btn btn-primary">
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header" id="advanced_settings_preferences">
                            <h3 class="card-title">
                                Preferences
                            </h3>
                        </div>
                        <div class="card-body grid gap-5 lg:py-7.5">
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Language
                                </label>
                                <select class="select">
                                    <option>
                                        American English
                                    </option>
                                    <option>
                                        Option 2
                                    </option>
                                    <option>
                                        Option 3
                                    </option>
                                </select>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Time zone
                                </label>
                                <div class="grow">
                                    <select class="select">
                                        <option>
                                            GMT -5:00 - Eastern Time(US &amp; Canada)
                                        </option>
                                        <option>
                                            Option 2
                                        </option>
                                        <option>
                                            Option 3
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5 mb-2">
                                <label class="form-label max-w-56">
                                    Currency
                                </label>
                                <div class="grow">
                                    <select class="select">
                                        <option>
                                            United States Dollar (USD)
                                        </option>
                                        <option>
                                            Option 2
                                        </option>
                                        <option>
                                            Option 3
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Open tasks as...
                                </label>
                                <div class="flex items-center gap-5">
                                    <label class="radio-group">
                                        <input checked="" class="radio" name="open-tasks" type="radio" value="1">
                                        <span class="radio-label">
               Modal
              </span>
                                    </label>
                                    <label class="radio-group">
                                        <input class="radio" name="open-tasks" type="radio" value="2">
                                        <span class="radio-label">
               Fullscreen
              </span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2.5 mb-1.5">
                                <label class="form-label max-w-56">
                                    Attributes
                                </label>
                                <div class="flex flex-col items-start gap-5">
                                    <div class="flex flex-col gap-2.5">
                                        <label class="checkbox-group">
                                            <input class="checkbox" name="attributes" type="checkbox" value="1"/>
                                            <span class="checkbox-label">
               Show list names
              </span>
                                        </label>
                                        <div class="form-hint">
                                            See the name next to each icon
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2.5">
                                        <label class="checkbox-group">
                                            <input checked="" class="checkbox" name="attributes" type="checkbox" value="2"/>
                                            <span class="checkbox-label">
               Show linked task names
              </span>
                                        </label>
                                        <div class="form-hint">
                                            Show task names next to ids for linked project tasks.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center flex-wrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Email visibility
                                </label>
                                <label class="switch">
                                    <input checked="" type="checkbox" value="1"/>
                                    <span class="text-gray-800 text-sm">
             Visible
            </span>
                                </label>
                            </div>
                            <div class="flex justify-end">
                                <button class="btn btn-primary">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="advanced_settings_notifications">
                            <h3 class="card-title">
                                Notifications
                            </h3>
                        </div>
                        <div class="card-body lg:py-7.5">
                            <div class="flex flex-wrap items-center gap-5 mb-5 lg:mb-7">
                                <div class="flex items-center justify-between flex-wrap grow border border-gray-200 rounded-xl gap-2 px-3.5 py-2.5">
                                    <div class="flex items-center flex-wrap gap-3.5">
                                        <div class="relative size-[50px] shrink-0">
                                            <svg class="w-full h-full stroke-gray-300 fill-gray-100" fill="none" height="48" viewbox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                                </path>
                                                <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                                </path>
                                            </svg>
                                            <div class="absolute leading-none left-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4">
                                                <i class="ki-filled ki-sms text-xl text-gray-500">
                                                </i>
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                Email
                                            </a>
                                            <span class="text-2sm font-medium text-gray-600">
               Tailor Your Email Preferences.
              </span>
                                        </div>
                                    </div>
                                    <label class="switch switch-sm">
                                        <input checked="" type="checkbox" value="1"/>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between flex-wrap grow border border-gray-200 rounded-xl gap-2 px-3.5 py-2.5">
                                    <div class="flex items-center flex-wrap gap-3.5">
                                        <div class="relative size-[50px] shrink-0">
                                            <svg class="w-full h-full stroke-gray-300 fill-gray-100" fill="none" height="48" viewbox="0 0 44 48" width="44" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 2.4641C19.7128 0.320509 24.2872 0.320508 28 2.4641L37.6506 8.0359C41.3634 10.1795 43.6506 14.141 43.6506
			18.4282V29.5718C43.6506 33.859 41.3634 37.8205 37.6506 39.9641L28 45.5359C24.2872 47.6795 19.7128 47.6795 16 45.5359L6.34937
			39.9641C2.63655 37.8205 0.349365 33.859 0.349365 29.5718V18.4282C0.349365 14.141 2.63655 10.1795 6.34937 8.0359L16 2.4641Z" fill="">
                                                </path>
                                                <path d="M16.25 2.89711C19.8081 0.842838 24.1919 0.842837 27.75 2.89711L37.4006 8.46891C40.9587 10.5232 43.1506 14.3196 43.1506
			18.4282V29.5718C43.1506 33.6804 40.9587 37.4768 37.4006 39.5311L27.75 45.1029C24.1919 47.1572 19.8081 47.1572 16.25 45.1029L6.59937
			39.5311C3.04125 37.4768 0.849365 33.6803 0.849365 29.5718V18.4282C0.849365 14.3196 3.04125 10.5232 6.59937 8.46891L16.25 2.89711Z" stroke="">
                                                </path>
                                            </svg>
                                            <div class="absolute leading-none left-2/4 top-2/4 -translate-y-2/4 -translate-x-2/4">
                                                <img alt="" class="h-5" :src="assetPath + '/media/brand-logos/slack.svg'"/>
                                            </div>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                Slack
                                            </a>
                                            <span class="text-2sm font-medium text-gray-600">
               Stay Updated on Slack.
              </span>
                                        </div>
                                    </div>
                                    <label class="switch switch-sm">
                                        <input checked="" type="checkbox" value="1"/>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3.5 mb-7">
           <span class="text-md font-semibold text-gray-900 pb-0.5">
            Desktop notifications
           </span>
                                <div class="flex flex-col items-start gap-4">
                                    <label class="radio-group">
                                        <input class="radio" name="desktop_notification" type="radio" value="1">
                                        <span class="radio-label radio-label-sm">
               All new messages (Recommended)
              </span>
                                    </label>
                                    <label class="radio-group">
                                        <input checked="" class="radio" name="desktop_notification" type="radio" value="2">
                                        <span class="radio-label radio-label-sm">
               Direct @mentions
              </span>
                                    </label>
                                    <label class="radio-group">
                                        <input checked="" class="radio" name="desktop_notification" type="radio" value="3">
                                        <span class="radio-label radio-label-sm">
               Disabled
              </span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3.5 mb-7">
           <span class="text-md font-semibold text-gray-900 pb-0.5">
            Email notifications
           </span>
                                <div class="flex flex-col items-start gap-4">
                                    <label class="radio-group">
                                        <input class="radio" name="email_notification" type="radio" value="1">
                                        <span class="radio-label radio-label-sm">
               All new messages and statuses
              </span>
                                    </label>
                                    <label class="radio-group">
                                        <input checked="" class="radio" name="email_notification" type="radio" value="2">
                                        <span class="radio-label radio-label-sm">
               AUnread messages and statuses
              </span>
                                    </label>
                                    <label class="radio-group">
                                        <input checked="" class="radio" name="email_notification" type="radio" value="3">
                                        <span class="radio-label radio-label-sm">
               Disabled
              </span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3.5">
           <span class="text-md font-semibold text-gray-900 pb-0.5">
            Subscriptions
           </span>
                                <label class="checkbox-group">
                                    <input class="checkbox" name="check" type="checkbox" value="1">
                                    <span class="checkbox-label checkbox-label-sm">
              Automatically subscribe to tasks you create
             </span>
                                </label>
                            </div>
                            <div class="flex justify-end">
                                <button class="btn btn-primary">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="tab === 'ssh-keys'" class="container-fixed">
            <div class="card shadow sm:rounded-lg">
                <div class="card-header">
                    <h3 class="text-base font-semibold leading-6 text-gray-900">Keys</h3>
                    <div>
                        <button @click="openCreateSshKey" type="button" class="btn btn-sm btn-light">
                            New SSH Key
                        </button>
                    </div>
                </div>
                <div class="px-4 py-5 sm:p-6">
                    <div v-for="key in sshKeys" class="mb-4 rounded-md bg-light px-6 py-5 sm:flex sm:items-start sm:justify-between">
                        <div class="sm:flex sm:items-start">
                            <i :class="{ 'text-success': key.used_at, 'text-gray-500': !key.used_at }" class="ki-solid ki-key text-3xl"></i>
                            <div class="mt-3 sm:ml-4 sm:mt-0">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ key.name }} {{ key.signature ? `&middot; ${key.signature}` : '' }}
                                    <span v-if="key.primary" class="badge mx-2 badge-xs badge-primary font-bold">
                                        Primary
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-gray-600 sm:flex sm:items-center">
                                    <div>Added at {{ key.created_at }}</div>
                                    <span class="hidden sm:mx-2 sm:inline" aria-hidden="true">&middot;</span>

                                    <div v-if="key.used_at" class="mt-1 sm:mt-0">Last used at {{ key.used_at }}</div>
                                    <div v-else class="mt-1 sm:mt-0">Not used yet</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 sm:ml-6 sm:mt-0 sm:flex-shrink-0">
                            <button type="button" class="btn btn-sm btn-danger">
                                <i class="ki-solid ki-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="tab === 'integrations'">
            <div class="container-fixed">
                <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                    <div class="flex flex-col justify-center gap-2">
                        <h1 class="text-xl font-semibold leading-none text-gray-900">
                            Integrations
                        </h1>
                        <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                            Enhance Workflows with Advanced Integrations.
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fixed">
                <div class="grid gap-5 lg:gap-7.5">
                    <!-- begin: cards -->
                    <div id="integrations_cards">
                        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-7.5">
                            <div v-for="integration in integrations" :key="integration.name" class="card">
                                <div class="card-body p-5 lg:p-7.5">
                                    <div class="flex items-center justify-between mb-3 lg:mb-5">
                                        <div class="flex items-center justify-center">
                                            <img alt="github logo" class="h-11 shrink-0 dark:hidden" :src="assetPath + integration.logo.dark">
                                            <img alt="github logo" class="h-11 shrink-0 light:hidden" :src="assetPath + integration.logo.light">
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-1 lg:gap-2.5">
                                        <a class="text-base font-semibold text-gray-900 hover:text-primary-active" href="/metronic/tailwind/demo1/account/billing/basic">
                                            {{ integration.name }}
                                        </a>
                                        <span class="text-2sm font-medium text-gray-600">
                                            {{ integration.description }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer justify-between items-center py-3.5">
                                    <button v-if="integration.is_connected" class="btn btn-success font-bold btn-sm">
                                        <i class="ki-filled ki-check">
                                        </i>
                                        Connected
                                    </button>

                                    <div v-else>
                                        <a v-if="integration.auth_url" :href="integration.auth_url" class="btn btn-light font-bold btn-sm">
                                            Connect
                                        </a>
                                        <button :disabled="integration.has_access === false" v-else-if="integration.connect_url" @click="openConnectProvider(integration)" class="btn btn-light font-bold btn-sm">
                                            <i class="ki-filled ki-mouse-square">
                                            </i>
                                            Connect
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="connect_modal">
            <form @submit.prevent="connectProvider" class="modal-content max-w-[600px] top-[10%]">
                <div class="block items-center justify-between mb-3 lg:mb-5">
                    <div class="flex items-center justify-center p-5" v-if="selectedProvider">
                        <img alt="github logo" class="h-25 w-auto shrink-0 dark:hidden" :src="assetPath + selectedProvider.logo.dark">
                        <img alt="github logo" class="h-25 w-auto shrink-0 light:hidden" :src="assetPath + selectedProvider.logo.light">
                    </div>
                </div>
                <div class="modal-header">
                    <h3 class="modal-title">
                        Connect {{ selectedProvider?.name }}
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Token
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="connectProviderForm.token" type="text" />
                                <span v-if="connectProviderForm.errors.token" class="form-hint text-danger">
                                    {{ connectProviderForm.errors.token }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-end">
                    <div class="flex gap-4">
                        <button type="button" class="btn btn-light" data-modal-dismiss="true">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_ssh_key">
            <form @submit.prevent="createSshKey" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        New SSH Key
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createSshKeyForm.name" type="text" />
                                <span v-if="createSshKeyForm.errors.name" class="form-hint text-danger">
                                    {{ createSshKeyForm.errors.name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Public Key
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <textarea class="textarea" rows="14" v-model="createSshKeyForm.public_key" type="text"></textarea>
                                <span v-if="createSshKeyForm.errors.public_key" class="form-hint text-danger">
                                    {{ createSshKeyForm.errors.public_key }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-end">
                    <div class="flex gap-4">
                        <button type="button" class="btn btn-light" data-modal-dismiss="true">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
