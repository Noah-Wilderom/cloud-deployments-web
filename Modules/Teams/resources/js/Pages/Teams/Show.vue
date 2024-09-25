<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref, watch} from 'vue'
import {useForm, usePage, router, Link} from '@inertiajs/vue3'

const tab = ref(null)
const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)
const queryParams = computed(() => page.props.queryParams)

const props = defineProps({
    user: Object,
    roles: Object,
})

const userLogs = ref([])

onMounted(() => {
    tab.value = queryParams.value?.tab ?? "details"
    console.log(tab.value)
})

watch(tab, () => {
    if (tab.value === "activities" && userLogs.value.length === 0) {
        fetchUserLogs()
    }
})

const basicSettingsForm = useForm({
    name: props.user.name,
    email: props.user.email,
})

const saveBasicSettings = () => {
    basicSettingsForm.post(route("profile.update"))
}

const newPasswordForm = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
})

const saveNewPassword = () => {
    newPasswordForm.post(route("profile.change-password"))
    newPasswordForm.reset()
}

const fetchUserLogs = () => {
    router.get(route("management::users.fetch-activity", props.user), {}, {
        onFinish: (res) => {
            // userLogs.value = res.data
            console.log(res)
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
        <div class="container-fixed">
            <div class="flex flex-nowrap items-center lg:items-end justify-between border-b border-b-gray-200 dark:border-b-coal-100 gap-6 mb-5 lg:mb-10">
                <div class="grid">
                    <div class="scrollable-x-auto">
                        <div class="menu gap-3" data-menu="true">
                            <div :class="{ 'active': tab === 'details'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('teams::users.show', {'user': user, 'tab': 'details'})" method="GET" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2" tabindex="0">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Details
                                    </span>
                                </Link>
                            </div>
                            <div :class="{ 'active': tab === 'activities'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('teams::users.show', {'user': user, 'tab': 'activities'})" method="GET" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2" tabindex="0">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Activiteiten
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
        <div class="container-fixed" v-if="tab === 'details'">
            <div class="flex grow gap-5 lg:gap-7.5">
                <div class="hidden lg:block w-[230px] shrink-0">
                    <div class="w-[230px]" data-sticky="true" data-sticky-animation="true" data-sticky-class="fixed top-[calc(var(--tw-header-height)+1.875rem)] z-10 left-auto" data-sticky-name="scrollspy" data-sticky-offset="200">
                        <div class="flex flex-col grow relative before:absolute before:left-[11px] before:top-0 before:bottom-0 before:border-l before:border-gray-200" data-scrollspy="true" data-scrollspy-offset="80px|lg:110px" data-scrollspy-target="body">
                            <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-1.5 active border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#basic_settings">
                                <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary"></span>
                                Basic Settings
                            </a>
                            <div class="flex flex-col">
                                <div class="pl-6 pr-2.5 py-2.5 text-2sm font-semibold text-gray-900">
                                    Authentication
                                </div>
                                <div class="flex flex-col">
                                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#auth_password">
                                        <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary"></span>
                                        Password
                                    </a>
                                </div>
                                <div class="flex flex-col">
                                    <a class="flex items-center rounded-lg pl-2.5 pr-2.5 py-2.5 gap-3.5 border border-transparent text-2sm font-medium text-gray-700 hover:text-primary scrollspy-active:bg-secondary-active scrollspy-active:text-primary dark:hover:bg-coal-300 dark:hover:border-gray-100 hover:rounded-lg dark:scrollspy-active:bg-coal-300 dark:scrollspy-active:border-gray-100" data-scrollspy-anchor="true" href="#auth_security">
             <span class="flex w-1.5 relative before:absolute before:top-0 before:left-px before:size-1.5 before:rounded-full before:-translate-x-2/4 before:-translate-y-2/4 scrollspy-active:before:bg-primary">
             </span>
                                        Security
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

                            <div class="w-full">
                                <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                    <label class="form-label flex items-center gap-1 max-w-56">
                                        Email
                                    </label>
                                    <div class="w-full">
                                        <input class="input" type="email" v-model="basicSettingsForm.email" />
                                        <span class="text-danger text-xs" v-if="basicSettingsForm.errors.email">
                                            {{ basicSettingsForm.errors.email}}
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

                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6" id="auth_password">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Reset Wachtwoord</h3>
                            <div class="mt-2 max-w-xl text-sm text-gray-500">
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Recusandae voluptatibus corrupti atque repudiandae nam.</p>
                            </div>
                            <div class="mt-5">
                                <button type="button" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                                    Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="auth_security">
                            <h3 class="card-title">
                                Security Preferences
                            </h3>
                        </div>
                        <div class="card-body grid gap-5 lg:py-7.5">
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Role
                                </label>

                                <select v-model="user.role" class="select">
                                    <option v-for="role in roles" :key="role.id" :value="role.name">
                                        {{ role.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                                <label class="form-label max-w-56">
                                    Enforce 2FA
                                </label>
                                <label class="switch">
                                    <input checked="" type="checkbox" value="1"/>
                                </label>
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

        <div class="container-fixed" v-if="tab === 'activities'">
            <div class="container-fixed">
                <!-- begin: toolbar -->
                <div class="flex flex-wrap items-center gap-5 justify-between mb-7.5">
                    <h3 class="text-lg text-gray-900 font-semibold">
                        Activity
                    </h3>
                </div>
                <!-- end: toolbar -->
                <!-- begin: activity -->
                <div class="flex gap-5 lg:gap-7.5">
                    <div class="card grow" id="activity_2024">
                        <div class="card-header">
                            <h3 class="card-title">
                                Activity
                            </h3>
                            <div class="flex items-center gap-2">
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="flex flex-col">
                                <div class="flex items-start relative" v-for="log in user.logs" :key="log.id">
                                    <div class="w-9 left-0 top-9 absolute bottom-0 translate-x-1/2 border-l border-l-gray-300">
                                    </div>
                                    <div class="flex items-center justify-center shrink-0 rounded-full bg-gray-100 border border-gray-300 size-9 text-gray-600">
                                        <i class="ki-filled ki-people text-base"></i>
                                    </div>
                                    <div class="pl-2.5 mb-7 text-md grow">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-800">
                                                Posted a new article
                                                <a class="text-sm font-medium link" href="html/demo1/public-profile/profiles/blogger.html">
                                                    Top 10 Tech Trends
                                                </a>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500">
                                                Today, 9:00 AM</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-start relative">
                                    <div class="w-9 left-0 top-9 absolute bottom-0 translate-x-1/2 border-l border-l-gray-300">
                                    </div>
                                    <div class="flex items-center justify-center shrink-0 rounded-full bg-gray-100 border border-gray-300 size-9 text-gray-600">
                                        <i class="ki-filled ki-entrance-left text-base">
                                        </i>
                                    </div>
                                    <div class="pl-2.5 mb-7 text-md grow">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-gray-800">
                                                I had the privilege of interviewing an industry expert for an
                                                <a class="text-sm font-medium link" href="">
                                                    upcoming blog post
                                                </a>
                                            </div>
                                            <span class="text-xs font-medium text-gray-500">
                                                2 days ago, 4:07 PM
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2.5" data-tabs="true">
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary active" data-tab-toggle="#activity_2024" href="#">
                            2024
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2023" href="#">
                            2023
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2022" href="#">
                            2022
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2021" href="#">
                            2021
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2020" href="#">
                            2020
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2019" href="#">
                            2019
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2018" href="#">
                            2018
                        </a>
                        <a class="btn btn-sm text-gray-600 hover:text-primary tab-active:bg-primary-light tab-active:text-primary" data-tab-toggle="#activity_2017" href="#">
                            2017
                        </a>
                    </div>
                </div>
                <!-- end: activity -->
            </div>
        </div>
        <!-- end: container -->
    </AppLayout>
</template>
