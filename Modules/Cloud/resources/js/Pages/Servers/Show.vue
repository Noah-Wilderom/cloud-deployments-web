<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, nextTick, onMounted, onBeforeUnmount, ref} from 'vue'
import {usePage, Link, router} from '@inertiajs/vue3'
import { KTAccordion } from "@metronic/core/index.ts";

const tab = ref(null)
const page = usePage()

const logs = ref([])

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)
const queryParams = computed(() => page.props.queryParams)

const props = defineProps({
    server: Object,
})

const joinChannels = () => {
    Echo.private(`server-updated.${props.server.id}`)
        .listen("\\Modules\\Cloud\\Events\\ServerUpdated", (e) => {
            router.reload({
                preserveScroll: true,
                only: ["server"]
            })
            console.log(e)
        })

    for(let i = 0; i < props.server.tasks.length; i++) {
        Echo.private(`ssh-logs.server.${props.server.id}.task.${props.server.tasks[i].id}`)
            .listen("\\Modules\\Cloud\\Events\\SSH\\ServerTaskLog", (e) => {
                console.log(e)
                let message = e.logLine
                if (! logs.value[e.task.id]) {
                    logs.value[e.task.id] = [e.logLine]
                } else {
                    logs.value[e.task.id].push(e.logLine)
                }
                scrollToBottom(e.task.id)
            })
    }

}

const leaveChannels = () => {
    Echo.leave(`server-updated.${props.server.id}`)
    for(let i = 0; i < props.server.tasks.length; i++) {
        Echo.leave(`ssh-logs.server.${props.server.id}.task.${props.server.tasks[i].id}`)
    }
}

const scrollToBottom = (taskId) => {
    const el = document.getElementById(`terminal_${taskId}`)
    if(el) {
        el.scrollTop = el.scrollHeight;
    }
}

onMounted(() => {
    KTAccordion.init()
    tab.value = queryParams.value?.tab ?? "overview"

    nextTick(() => {
        for(let i = 0; i < props.server.tasks.length; i++) {
            if(props.server.tasks[i].logFile !== null) {
                let messages = props.server.tasks[i].logFile.split("\n")
                logs.value[props.server.tasks[i].id] = messages
                console.log(logs[props.server.tasks[i].id])
            }
        }

        joinChannels()
    })
})

onBeforeUnmount(() => {
    leaveChannels()
})

</script>

<style>
.terminal {
    background-color: #1e1e1e;
    color: #d4d4d4;
    font-family: 'Courier New', Courier, monospace;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-y: scroll;
    max-height: 400px;
}
</style>

<template>
    <AppLayout :title="server.name + '- Server'">
        <!-- begin: container -->
        <div class="container-fixed" id="content_container">
        </div>
        <!-- end: container -->
        <div class="bg-center bg-cover bg-no-repeat hero-bg">
            <!-- begin: container -->
            <div class="container-fixed">
                <div class="flex flex-col items-center gap-2 lg:gap-3.5 py-4 lg:pt-5 lg:pb-10">
                    <div class="w-full">
                        <div class="card">
                            <div class="card-body">
                                <div class="flex lg:px-10 py-1.5 gap-2">
                                    <div class="grid grid-cols-1 place-content-center flex-1 gap-1 text-center">
                                        <span class="text-gray-900 text-2xl lg:text-2.5xl leading-none font-semibold">
                                            {{ server.name }}
                                        </span>
                                        <span class="text-gray-600 text-sm font-medium">
                                            Name
                                        </span>
                                    </div>
                                    <span class="[&amp;:not(:last-child)]:border-r border-r-gray-300 my-1"></span>
                                    <div class="grid grid-cols-1 place-content-center flex-1 gap-1 text-center">

                                        <span class="text-gray-900 text-2xl lg:text-2.5xl leading-none font-semibold">
                                            {{ server.host.ipv4 }}
                                        </span>
                                        <span class="text-gray-600 text-sm font-medium">
                                            Server IP
                                        </span>
                                    </div>
                                    <span class="[&amp;:not(:last-child)]:border-r border-r-gray-300 my-1"></span>
                                    <div class="grid grid-cols-1 place-content-center flex-1 gap-1 text-center">
                                        <span class="text-gray-900 text-2xl lg:text-2.5xl leading-none font-semibold">
                                            {{ server.status }}
                                        </span>
                                        <span class="text-gray-600 text-sm font-medium">
                                            Status
                                        </span>
                                    </div>
                                    <span class="[&amp;:not(:last-child)]:border-r border-r-gray-300 my-1"></span>
                                    <div class="grid grid-cols-1 place-content-center flex-1 gap-1 text-center">
                                        <span class="text-gray-900 text-2xl lg:text-2.5xl leading-none font-semibold">
                                            {{ server.projects.length }}
                                        </span>
                                        <span class="text-gray-600 text-sm font-medium">
                                            Projects
                                        </span>
                                    </div>
                                    <span class="[&amp;:not(:last-child)]:border-r border-r-gray-300 my-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end: container -->
        </div>
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-nowrap items-center lg:items-end justify-between border-b border-b-gray-200 dark:border-b-coal-100 gap-6 mb-5 lg:mb-10">
                <div class="grid">
                    <div class="scrollable-x-auto">
                        <div class="menu gap-3" data-menu="true">
                            <div :class="{'here': tab === 'overview'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('cloud::servers.show', {'server': server.id, 'tab': 'overview'})" method="get" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Overview
                                    </span>
                                </Link>
                            </div>
                            <div :class="{'here': tab === 'software'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('cloud::servers.show', {'server': server.id, 'tab': 'software'})" method="get" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Software
                                    </span>
                                </Link>
                            </div>
                            <div :class="{'here': tab === 'logs'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('cloud::servers.show', {'server': server.id, 'tab': 'logs'})" method="get" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Logs
                                    </span>
                                </Link>
                            </div>
                            <div :class="{'here': tab === 'settings'}" class="menu-item border-b-2 border-b-transparent menu-item-active:border-b-primary menu-item-here:border-b-primary">
                                <Link :href="route('cloud::servers.show', {'server': server.id, 'tab': 'settings'})" method="get" class="menu-link gap-1.5 pb-2 lg:pb-4 px-2">
                                    <span class="menu-title text-nowrap font-medium text-sm text-gray-700 menu-item-active:text-primary menu-item-active:font-semibold menu-item-here:text-primary menu-item-here:font-semibold menu-item-show:text-primary menu-link-hover:text-primary">
                                        Settings
                                    </span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center lg:pb-4 gap-2.5">
                    <div class="dropdown" data-dropdown="true" data-dropdown-placement="bottom-end" data-dropdown-trigger="click">
                        <button class="dropdown-toggle btn btn-sm btn-icon btn-light">
                            <i class="ki-filled ki-dots-vertical"></i>
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
        <!-- end: container -->
        <!-- begin: container -->
        <div v-if="tab === 'logs'" class="container-fixed" data-accordion="true">
            <div v-for="task in server.tasks" :key="task.id" :class="{'active': task.status === 'running'}" class="mb-4 accordion-item border rounded-xl" data-accordion-item="true">
                <button class="accordion-toggle p-4" :data-accordion-toggle="'#log_accordion_' + task.id">
                    <span class="flex items-center text-base text-gray-900 font-medium">
                        <svg :class="{
                            'text-white': task.status === 'created',
                            'text-blue-700': task.status === 'running',
                        }"
                             v-if="task.status === 'created' || task.status === 'running'"
                             class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span v-else class="px-2">
                            <i :class="{
                                'ki-check-circle text-success': task.status === 'success',
                                'ki-cross-circle text-danger': task.status === 'failed'
                            }" class="ki-duotone"></i>
                        </span>
                        {{ task.name }}
                    </span>
                    <i class="ki-outline ki-plus text-gray-600 text-2sm accordion-active:hidden block"></i>
                    <i class="ki-outline ki-minus text-gray-600 text-2sm accordion-active:block hidden"></i>
                </button>
                <div :class="{'hidden': task.status !== 'running'}" class="accordion-content border-t" :id="'log_accordion_' + task.id">
                    <div class="text-gray-700 text-md p-4">
                        <div class="terminal" :id="'terminal_' + task.id" >
                            <div v-for="(log, index) in logs[task.id]" :key="index">
                                {{ log }}
                            </div>
                        </div>
                        <button class="btn btn-primary btn-block mt-3 scrollBtn"
                                @click="scrollToBottom(task.id)">Scroll to
                            Bottom
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="tab === 'overview'" class="container-fixed">
            <!-- begin: grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-7.5">
                <div class="col-span-1">
                    <div class="flex flex-col gap-5 lg:gap-7.5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Highlights
                                </h3>
                            </div>
                            <div class="card-body pt-3.5 pb-3.5">
                                <table class="table-auto">
                                    <tbody>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            Locations:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
                                            79
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            Founded:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
                                            2011
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            Status:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
               <span class="badge badge-sm badge-success badge-outline">
                Subscribed
               </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            Area:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
                                            Worldwide
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            CEO:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
                                            <a class="link" href="#">
                                                Luis von Ahn
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-sm font-medium text-gray-500 pb-3 pe-4 lg:pe-10">
                                            Sector:
                                        </td>
                                        <td class="text-sm font-medium text-gray-800 pb-3">
                                            Online Education
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Open Jobs
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="grid gap-5">
                                    <div class="flex align-start gap-3.5">
                                        <div class="flex items-center justify-center w-[1.875rem] h-[1.875rem] bg-gray-100 rounded-lg border border-gray-300">
                                            <i class="ki-filled ki-chart-line-star text-base text-gray-600">
                                            </i>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold leading-none link mb-1" href="#">
                                                Data Science
                                            </a>
                                            <span class="text-sm font-medium text-gray-800">
               Data Science Ninja
              </span>
                                            <span class="text-xs font-medium text-gray-500">
               $80,000 - $110,000
              </span>
                                        </div>
                                    </div>
                                    <div class="flex align-start gap-3.5">
                                        <div class="flex items-center justify-center w-[1.875rem] h-[1.875rem] bg-gray-100 rounded-lg border border-gray-300">
                                            <i class="ki-filled ki-rocket text-base text-gray-600">
                                            </i>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold leading-none link mb-1" href="#">
                                                Exploration
                                            </a>
                                            <span class="text-sm font-medium text-gray-800">
               Galactic Guide Writer
              </span>
                                            <span class="text-xs font-medium text-gray-500">
               $45,000 - $60,000
              </span>
                                        </div>
                                    </div>
                                    <div class="flex align-start gap-3.5">
                                        <div class="flex items-center justify-center w-[1.875rem] h-[1.875rem] bg-gray-100 rounded-lg border border-gray-300">
                                            <i class="ki-filled ki-milk text-base text-gray-600">
                                            </i>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold leading-none link mb-1" href="#">
                                                Drinking Arts
                                            </a>
                                            <span class="text-sm font-medium text-gray-800">
               Taste
              </span>
                                            <span class="text-xs font-medium text-gray-500">
               $40,000 - $55,000
              </span>
                                        </div>
                                    </div>
                                    <div class="flex align-start gap-3.5">
                                        <div class="flex items-center justify-center w-[1.875rem] h-[1.875rem] bg-gray-100 rounded-lg border border-gray-300">
                                            <i class="ki-filled ki-abstract-44 text-base text-gray-600">
                                            </i>
                                        </div>
                                        <div class="flex flex-col">
                                            <a class="text-sm font-semibold leading-none link mb-1" href="#">
                                                Film Production
                                            </a>
                                            <span class="text-sm font-medium text-gray-800">
               Zombie Makeup Artist
              </span>
                                            <span class="text-xs font-medium text-gray-500">
               $55,000 - $75,000
              </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer justify-center">
                                <a class="btn btn-link" href="html/demo1/public-profile/works.html">
                                    View &amp; Apply
                                </a>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Network
                                </h3>
                            </div>
                            <div class="card-body pt-4">
                                <div class="grid gap-2.5 mb-1">
                                    <div class="flex items-center gap-2.5">
             <span class="">
              <i class="ki-filled ki-dribbble text-lg text-gray-500">
              </i>
             </span>
                                        <a class="text-gray-800 hover:text-primary-active text-sm font-medium" href="#">
                                            https://duolingo.com
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2.5">
             <span class="">
              <i class="ki-filled ki-sms text-lg text-gray-500">
              </i>
             </span>
                                        <a class="text-gray-800 hover:text-primary-active text-sm font-medium" href="#">
                                            info@duolingo.com
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2.5">
             <span class="">
              <i class="ki-filled ki-facebook text-lg text-gray-500">
              </i>
             </span>
                                        <a class="text-gray-800 hover:text-primary-active text-sm font-medium" href="#">
                                            duolingo
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2.5">
             <span class="">
              <i class="ki-filled ki-twitter text-lg text-gray-500">
              </i>
             </span>
                                        <a class="text-gray-800 hover:text-primary-active text-sm font-medium" href="#">
                                            duolingo-news
                                        </a>
                                    </div>
                                    <div class="flex items-center gap-2.5">
             <span class="">
              <i class="ki-filled ki-youtube text-lg text-gray-500">
              </i>
             </span>
                                        <a class="text-gray-800 hover:text-primary-active text-sm font-medium" href="#">
                                            duolingo-tuts
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Tags
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="flex flex-wrap gap-2.5 mb-2">
            <span class="badge badge-sm badge-gray-200">
             Web Design
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             Code Review
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             Figma
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             Product Development
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             Webflow
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             AI
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             noCode
            </span>
                                    <span class="badge badge-sm badge-gray-200">
             Management
            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-1 lg:col-span-2">
                    <div class="flex flex-col gap-5 lg:gap-7.5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Company Profle
                                </h3>
                            </div>
                            <div class="card-body">
                                <h3 class="text-base font-semibold text-gray-900 leading-none mb-5">
                                    Headquarter
                                </h3>
                                <div class="flex flex-wrap items-center gap-5 mb-10">
                                    <div class="rounded-xl w-full md:w-80 min-h-52" id="company_profile_map">
                                    </div>
                                    <div class="flex flex-col gap-2.5">
                                        <div class="flex items-center gap-2.5">
              <span class="">
               <i class="ki-filled ki-dribbble text-lg text-gray-500">
               </i>
              </span>
                                            <a class="link text-sm font-medium" href="#">
                                                https://duolingo.com
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2.5">
              <span class="">
               <i class="ki-filled ki-facebook text-lg text-gray-500">
               </i>
              </span>
                                            <a class="link text-sm font-medium" href="#">
                                                duolingo
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2.5">
              <span class="">
               <i class="ki-filled ki-youtube text-lg text-gray-500">
               </i>
              </span>
                                            <a class="link text-sm font-medium" href="#">
                                                duolingo-tuts
                                            </a>
                                        </div>
                                        <div class="flex items-center gap-2.5">
              <span class="">
               <i class="ki-filled ki-whatsapp text-lg text-gray-500">
               </i>
              </span>
                                            <span class="text-sm font-medium text-gray-800">
               (31) 6-1235-4567
              </span>
                                        </div>
                                        <div class="flex items-center gap-2.5">
              <span class="">
               <i class="ki-filled ki-map text-lg text-gray-500">
               </i>
              </span>
                                            <span class="text-sm font-medium text-gray-800">
               Herengracht 501, 1017 BV Amsterdam, NL
              </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="grid gap-2.5 mb-7">
                                    <div class="text-md font-semibold text-gray-900">
                                        About
                                    </div>
                                    <p class="text-sm text-gray-700 font-medium leading-5.5">
                                        Now that I’m done thoroughly mangling that vague metaphor, let’s get down to business.
                                        You know you need to start blogging to grow your business, but you don’t know how. In this post,
                                        I’ll show you how to write a great blog post in five simple steps that people will actually want to read.
                                    </p>
                                </div>
                                <div class="flex flex-col gap-4 mb-2.5">
                                    <div class="text-md font-semibold text-gray-900">
                                        Products
                                    </div>
                                    <div class="flex flex-wrap gap-2.5">
             <span class="badge badge-outline">
              Lingo Kids
             </span>
                                        <span class="badge badge-outline">
              Lingo Express
             </span>
                                        <span class="badge badge-outline">
              Fun Learning
             </span>
                                        <span class="badge badge-outline">
              Lingo Espanol
             </span>
                                        <span class="badge badge-outline">
              Speaking Mastery
             </span>
                                        <span class="badge badge-outline">
              Grammar Guru
             </span>
                                        <span class="badge badge-outline">
              Lingo Quest
             </span>
                                        <span class="badge badge-outline">
              History Lessons
             </span>
                                        <span class="badge badge-outline">
              Global Explorer
             </span>
                                        <span class="badge badge-outline">
              Translator
             </span>
                                        <span class="badge badge-outline">
              Webflow
             </span>
                                        <span class="badge badge-outline">
              Language Lab
             </span>
                                        <span class="badge badge-outline">
              Lingo Plus
             </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Locations
                                </h3>
                                <button class="btn btn-success btn-sm">
                                    <i class="ki-filled ki-geolocation">
                                    </i>
                                    Offer Location
                                </button>
                            </div>
                            <div class="card-body p-5 lg:p-7.5 lg:pb-7">
                                <div class="flex gap-5 scrollable-x">
                                    <div class="card shadow-none w-[280px] border-0 mb-4">
                                        <img alt="" class="rounded-t-xl max-w-[280px] shrink-0" src="assets/media/images/600x400/10.jpg"/>
                                        <div class="card-border card-rounded-b px-3.5 h-full pt-3 pb-3.5">
                                            <a class="font-semibold block text-gray-900 hover:text-primary text-md mb-2" href="#">
                                                Duolingo Tech Hub
                                            </a>
                                            <p class="text-2sm font-medium text-gray-600">
                                                456 Innovation Street, Floor 6, Techland, New York 54321
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card shadow-none w-[280px] border-0 mb-4">
                                        <img alt="" class="rounded-t-xl max-w-[280px] shrink-0" src="assets/media/images/600x400/11.jpg"/>
                                        <div class="card-border card-rounded-b px-3.5 h-full pt-3 pb-3.5">
                                            <a class="font-semibold block text-gray-900 hover:text-primary text-md mb-2" href="#">
                                                Duolingo Language Lab
                                            </a>
                                            <p class="text-2sm font-medium text-gray-600">
                                                789 Learning Lane, 3rd Floor, Lingoville, Texas 98765
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card shadow-none w-[280px] border-0 mb-4">
                                        <img alt="" class="rounded-t-xl max-w-[280px] shrink-0" src="assets/media/images/600x400/12.jpg"/>
                                        <div class="card-border card-rounded-b px-3.5 h-full pt-3 pb-3.5">
                                            <a class="font-semibold block text-gray-900 hover:text-primary text-md mb-2" href="#">
                                                Duolingo Research Institute
                                            </a>
                                            <p class="text-2sm font-medium text-gray-600">
                                                246 Innovation Road, Research Wing, Innovacity, Arizona 13579
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card shadow-none w-[280px] border-0 mb-4">
                                        <img alt="" class="rounded-t-xl max-w-[280px] shrink-0" src="assets/media/images/600x400/7.jpg"/>
                                        <div class="card-border card-rounded-b px-3.5 h-full pt-3 pb-3.5">
                                            <a class="font-semibold block text-gray-900 hover:text-primary text-md mb-2" href="#">
                                                Duolingo Research Institute
                                            </a>
                                            <p class="text-2sm font-medium text-gray-600">
                                                246 Innovation Road, Research Wing, Innovacity, Arizona 13579
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card shadow-none w-[280px] border-0 mb-4">
                                        <img alt="" class="rounded-t-xl max-w-[280px] shrink-0" src="assets/media/images/600x400/8.jpg"/>
                                        <div class="card-border card-rounded-b px-3.5 h-full pt-3 pb-3.5">
                                            <a class="font-semibold block text-gray-900 hover:text-primary text-md mb-2" href="#">
                                                Duolingo Research Institute
                                            </a>
                                            <p class="text-2sm font-medium text-gray-600">
                                                246 Innovation Road, Research Wing, Innovacity, Arizona 13579
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Projects
                                </h3>
                                <div class="menu" data-menu="true">
                                    <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                        <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                            <i class="ki-filled ki-dots-vertical">
                                            </i>
                                        </button>
                                        <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                            <div class="menu-item">
                                                <a class="menu-link" href="html/demo1/account/home/settings-plain.html">
                <span class="menu-icon">
                 <i class="ki-filled ki-add-files">
                 </i>
                </span>
                                                    <span class="menu-title">
                 Add
                </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link" href="html/demo1/account/members/import-members.html">
                <span class="menu-icon">
                 <i class="ki-filled ki-file-down">
                 </i>
                </span>
                                                    <span class="menu-title">
                 Import
                </span>
                                                </a>
                                            </div>
                                            <div class="menu-item" data-menu-item-offset="-15px, 0" data-menu-item-placement="right-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:hover">
                                                <div class="menu-link">
                <span class="menu-icon">
                 <i class="ki-filled ki-file-up">
                 </i>
                </span>
                                                    <span class="menu-title">
                 Export
                </span>
                                                    <span class="menu-arrow">
                 <i class="ki-filled ki-right text-3xs">
                 </i>
                </span>
                                                </div>
                                                <div class="menu-dropdown menu-default w-full max-w-[125px]">
                                                    <div class="menu-item">
                                                        <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                  <span class="menu-title">
                   PDF
                  </span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                  <span class="menu-title">
                   CVS
                  </span>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item">
                                                        <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                  <span class="menu-title">
                   Excel
                  </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="menu-item">
                                                <a class="menu-link" href="html/demo1/account/security/privacy-settings.html">
                <span class="menu-icon">
                 <i class="ki-filled ki-setting-3">
                 </i>
                </span>
                                                    <span class="menu-title">
                 Settings
                </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-table scrollable-x-auto">
                                <table class="table text-right">
                                    <thead>
                                    <tr>
                                        <th class="text-left min-w-52">
                                            Project Name
                                        </th>
                                        <th class="min-w-40">
                                            Progress
                                        </th>
                                        <th class="min-w-32">
                                            People
                                        </th>
                                        <th class="min-w-32">
                                            Due Date
                                        </th>
                                        <th class="w-[30px]">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-left">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary" href="#">
                                                Acme software development
                                            </a>
                                        </td>
                                        <td>
                                            <div class="progress progress-primary">
                                                <div class="progress-bar" style="width: 60%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-end shrink-0">
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-4.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-1.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-2.png"/>
                                                    </div>
                                                    <div class="flex">
                  <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-6 text-success-inverse ring-success-light bg-success">
                   +3
                  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm font-medium text-gray-700">
                                            24 Aug, 2024
                                        </td>
                                        <td class="text-left">
                                            <div class="menu" data-menu="true">
                                                <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                                    <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                        <i class="ki-filled ki-dots-vertical">
                                                        </i>
                                                    </button>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-search-list">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     View
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-file-up">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Export
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-pencil">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Edit
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-copy">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Make a copy
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-trash">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Remove
                    </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary" href="#">
                                                Strategic Partnership Deal
                                            </a>
                                        </td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar" style="width: 100%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-end shrink-0">
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-1.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-2.png"/>
                                                    </div>
                                                    <div class="flex">
                  <span class="hover:z-5 relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-6 text-danger-inverse ring-danger-light bg-danger">
                   M
                  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm font-medium text-gray-700">
                                            10 Sep, 2024
                                        </td>
                                        <td class="text-left">
                                            <div class="menu" data-menu="true">
                                                <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                                    <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                        <i class="ki-filled ki-dots-vertical">
                                                        </i>
                                                    </button>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-search-list">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     View
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-file-up">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Export
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-pencil">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Edit
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-copy">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Make a copy
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-trash">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Remove
                    </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary" href="#">
                                                Client Onboarding
                                            </a>
                                        </td>
                                        <td>
                                            <div class="progress progress-primary">
                                                <div class="progress-bar" style="width: 20%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-end shrink-0">
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-20.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-7.png"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm font-medium text-gray-700">
                                            19 Sep, 2024
                                        </td>
                                        <td class="text-left">
                                            <div class="menu" data-menu="true">
                                                <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                                    <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                        <i class="ki-filled ki-dots-vertical">
                                                        </i>
                                                    </button>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-search-list">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     View
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-file-up">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Export
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-pencil">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Edit
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-copy">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Make a copy
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-trash">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Remove
                    </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary" href="#">
                                                Widget Supply Agreement
                                            </a>
                                        </td>
                                        <td>
                                            <div class="progress progress-success">
                                                <div class="progress-bar" style="width: 100%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-end shrink-0">
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-6.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-23.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-12.png"/>
                                                    </div>
                                                    <div class="flex">
                  <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-6 text-primary-inverse ring-primary-light bg-primary">
                   +1
                  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm font-medium text-gray-700">
                                            5 May, 2024
                                        </td>
                                        <td class="text-left">
                                            <div class="menu" data-menu="true">
                                                <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                                    <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                        <i class="ki-filled ki-dots-vertical">
                                                        </i>
                                                    </button>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-search-list">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     View
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-file-up">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Export
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-pencil">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Edit
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-copy">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Make a copy
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-trash">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Remove
                    </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">
                                            <a class="text-sm font-semibold text-gray-900 hover:text-primary" href="#">
                                                Project X Redesign
                                            </a>
                                        </td>
                                        <td>
                                            <div class="progress progress-primary">
                                                <div class="progress-bar" style="width: 80%">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="flex justify-end shrink-0">
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-2.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-15.png"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-6" src="assets/media/avatars/300-18.png"/>
                                                    </div>
                                                    <div class="flex">
                  <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-6 text-success-inverse ring-success-light bg-success">
                   +2
                  </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-sm font-medium text-gray-700">
                                            1 Feb, 2025
                                        </td>
                                        <td class="text-left">
                                            <div class="menu" data-menu="true">
                                                <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                                    <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                        <i class="ki-filled ki-dots-vertical">
                                                        </i>
                                                    </button>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]" data-menu-dismiss="true">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-search-list">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     View
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-file-up">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Export
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-pencil">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Edit
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-copy">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Make a copy
                    </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-separator">
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="#">
                    <span class="menu-icon">
                     <i class="ki-filled ki-trash">
                     </i>
                    </span>
                                                                <span class="menu-title">
                     Remove
                    </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer justify-center">
                                <a class="btn btn-link" href="html/demo1/public-profile/projects/3-columns.html">
                                    All Projects
                                </a>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 lg:gap-7.5">
                            <div class="card">
                                <div class="card-header gap-2">
                                    <h3 class="card-title">
                                        Members
                                    </h3>
                                    <div class="menu" data-menu="true">
                                        <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                            <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                <i class="ki-filled ki-dots-vertical">
                                                </i>
                                            </button>
                                            <div class="menu-dropdown menu-default w-full max-w-[200px]" data-menu-dismiss="true">
                                                <div class="menu-item">
                                                    <a class="menu-link" href="html/demo1/account/activity.html">
                 <span class="menu-icon">
                  <i class="ki-filled ki-cloud-change">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Activity
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" data-modal-toggle="#share_profile_modal" href="#">
                 <span class="menu-icon">
                  <i class="ki-filled ki-share">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Share
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-item" data-menu-item-offset="-15px, 0" data-menu-item-placement="right-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:hover">
                                                    <div class="menu-link">
                 <span class="menu-icon">
                  <i class="ki-filled ki-notification-status">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Notifications
                 </span>
                                                        <span class="menu-arrow">
                  <i class="ki-filled ki-right text-3xs">
                  </i>
                 </span>
                                                    </div>
                                                    <div class="menu-dropdown menu-default w-full max-w-[175px]">
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                   <span class="menu-icon">
                    <i class="ki-filled ki-sms">
                    </i>
                   </span>
                                                                <span class="menu-title">
                    Email
                   </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                   <span class="menu-icon">
                    <i class="ki-filled ki-message-notify">
                    </i>
                   </span>
                                                                <span class="menu-title">
                    SMS
                   </span>
                                                            </a>
                                                        </div>
                                                        <div class="menu-item">
                                                            <a class="menu-link" href="html/demo1/account/home/settings-sidebar.html">
                   <span class="menu-icon">
                    <i class="ki-filled ki-notification-status">
                    </i>
                   </span>
                                                                <span class="menu-title">
                    Push
                   </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" data-modal-toggle="#report_user_modal" href="#">
                 <span class="menu-icon">
                  <i class="ki-filled ki-dislike">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Report
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-separator">
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" href="html/demo1/account/home/settings-enterprise.html">
                 <span class="menu-icon">
                  <i class="ki-filled ki-setting-3">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Settings
                 </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="flex flex-col gap-2 lg:gap-5">
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center grow gap-2.5">
                                                <img alt="" class="rounded-full size-9 shrink-0" src="assets/media/avatars/300-3.png"/>
                                                <div class="flex flex-col">
                                                    <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                        Tyler Hero
                                                    </a>
                                                    <span class="text-xs font-medium text-gray-600">
                 6 connections
                </span>
                                                </div>
                                            </div>
                                            <button class="btn btn-xs btn-icon btn-primary btn-outline rounded-full">
                                                <i class="ki-filled ki-plus">
                                                </i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center grow gap-2.5">
                                                <img alt="" class="rounded-full size-9 shrink-0" src="assets/media/avatars/300-1.png"/>
                                                <div class="flex flex-col">
                                                    <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                        Esther Howard
                                                    </a>
                                                    <span class="text-xs font-medium text-gray-600">
                 29 connections
                </span>
                                                </div>
                                            </div>
                                            <button class="btn btn-xs btn-icon btn-primary btn-outline rounded-full active">
                                                <i class="ki-filled ki-check">
                                                </i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center grow gap-2.5">
                                                <img alt="" class="rounded-full size-9 shrink-0" src="assets/media/avatars/300-14.png"/>
                                                <div class="flex flex-col">
                                                    <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                        Cody Fisher
                                                    </a>
                                                    <span class="text-xs font-medium text-gray-600">
                 34 connections
                </span>
                                                </div>
                                            </div>
                                            <button class="btn btn-xs btn-icon btn-primary btn-outline rounded-full">
                                                <i class="ki-filled ki-plus">
                                                </i>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center grow gap-2.5">
                                                <img alt="" class="rounded-full size-9 shrink-0" src="assets/media/avatars/300-7.png"/>
                                                <div class="flex flex-col">
                                                    <a class="text-sm font-semibold text-gray-900 hover:text-primary-active mb-px" href="#">
                                                        Arlene McCoy
                                                    </a>
                                                    <span class="text-xs font-medium text-gray-600">
                 1 connections
                </span>
                                                </div>
                                            </div>
                                            <button class="btn btn-xs btn-icon btn-primary btn-outline rounded-full active">
                                                <i class="ki-filled ki-check">
                                                </i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer justify-center">
                                    <a class="btn btn-link" href="html/demo1/public-profile/network.html">
                                        All Contributors
                                    </a>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Investments
                                    </h3>
                                    <div class="menu" data-menu="true">
                                        <div class="menu-item" data-menu-item-offset="0, 10px" data-menu-item-placement="bottom-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
                                            <button class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
                                                <i class="ki-filled ki-dots-vertical">
                                                </i>
                                            </button>
                                            <div class="menu-dropdown menu-default w-full max-w-[200px]" data-menu-dismiss="true">
                                                <div class="menu-item">
                                                    <a class="menu-link" href="html/demo1/account/home/settings-enterprise.html">
                 <span class="menu-icon">
                  <i class="ki-filled ki-setting-3">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Settings
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" href="html/demo1/account/members/import-members.html">
                 <span class="menu-icon">
                  <i class="ki-filled ki-some-files">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Import
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" href="html/demo1/account/activity.html">
                 <span class="menu-icon">
                  <i class="ki-filled ki-cloud-change">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Activity
                 </span>
                                                    </a>
                                                </div>
                                                <div class="menu-item">
                                                    <a class="menu-link" data-modal-toggle="#report_user_modal" href="#">
                 <span class="menu-icon">
                  <i class="ki-filled ki-dislike">
                  </i>
                 </span>
                                                        <span class="menu-title">
                  Report
                 </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body flex justify-center items-center px-3 py-1">
                                    <div id="contributions_chart">
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
