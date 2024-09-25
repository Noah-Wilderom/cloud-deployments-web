<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref} from 'vue'
import {useForm, usePage, router, Link} from '@inertiajs/vue3'
import { KTDataTable, KTModal } from "@metronic/core/index.ts";

const page = usePage()

const assetPath = computed(() => page.props.assetPath)

onMounted(() => {
    KTDataTable.init()
    KTModal.init()

    const el = document.getElementById("openCreateTeam")
    if (el !== null) {
        el.addEventListener("click", () => {
            openCreateTeam()
        })
    }
})

const props = defineProps(["teams"])

const createTeamForm = useForm({
    name: "",
})

const openCreateTeam = () => {
    const modelEl = document.getElementById("create_team")
    const modal = KTModal.getInstance(modelEl)
    modal.show()
}

const create = () => {
    createTeamForm.post(route("teams::store"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_team")
            const modal = KTModal.getInstance(modalEl)
            modal.hide()

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["teams"]
            })
        },
    })
}


</script>

<template>
    <AppLayout title="Teams">
        <!-- begin: container -->
        <div class="container-fixed" id="content_container">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Teams
                    </h1>
                    <div class="flex items-center flex-wrap gap-1.5 font-medium">
                        <span class="text-md text-gray-600">
                            All Teams:
                        </span>
                        <span class="text-md text-gray-800 font-semibold me-2">
                            {{ teams.length }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button @click="openCreateTeam" type="button" class="btn btn-sm btn-light">
                        Create Team
                    </button>
                </div>
            </div>
        </div>
        <!-- end: container -->
        <div class="container-fixed">
            <div class="max-w-7xl mx-auto">
                <div class="grid gap-5 lg:gap-7.5">
                    <div class="card card-grid min-w-full">
                        <div class="card-header flex-wrap py-5">
                            <h3 class="card-title">
                                Teams
                            </h3>
                            <div class="flex gap-6">
                                <div class="relative">
                                    <i class="ki-filled ki-magnifier leading-none text-md text-gray-500 absolute top-1/2 left-0 -translate-y-1/2 ml-3">
                                    </i>
                                    <input class="input input-sm pl-8" data-datatable-search="#teams_table" placeholder="Search Teams" type="text"/>
                                </div>
                                <label class="switch switch-sm">
                                    <input class="order-2" name="check" type="checkbox" value="1"/>
                                    <span class="switch-label order-1">
                                        Only Active Groups
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="card-body">
                            <div data-datatable="true" data-datatable-page-size="10" id="teams_table">
                                <div class="scrollable-x-auto">
                                    <table class="table table-fixed table-border" data-datatable-table="true">
                                        <thead>
                                        <tr>
                                            <th class="w-[60px] text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-check="true" type="checkbox"/>
                                            </th>
                                            <th class="w-[350px]">
                                                <span class="sort asc">
                                                    <span class="sort-label">
                                                        Team
                                                    </span>
                                                    <span class="sort-icon"></span>
                                                </span>
                                            </th>
                                            <th class="w-[200px]">
                                                <span class="sort">
                                                    <span class="sort-label">
                                                        Rating
                                                    </span>
                                                    <span class="sort-icon"></span>
                                                </span>
                                            </th>
                                            <th class="w-[200px]">
               <span class="sort">
                <span class="sort-label">
                 Last Modified
                </span>
                <span class="sort-icon">
                </span>
               </span>
                                            </th>
                                            <th class="w-[200px]">
               <span class="sort asc">
                <span class="sort-label">
                 Members
                </span>
                <span class="sort-icon">
                </span>
               </span>
                                            </th>
                                            <th class="w-[60px]">
                                            </th>
                                            <th class="w-[60px]">
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="teams.length < 1">
                                            <td colspan="">
                                                <button id="openCreateTeam" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                    <i class="mx-auto text-5xl text-gray-400 ki-filled ki-users"></i>
                                                    <span class="mt-2 block text-sm font-semibold text-gray-900">Create a new team</span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="1"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Product Management
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Overseeing product development and lifecycle
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                21 Oct, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-4.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-1.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-2.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +10
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="2"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Marketing Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Developing campaigns, market analysis
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                15 Oct, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-4.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="hover:z-5 relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] uppercase text-warning-inverse ring-warning-light bg-warning">
                  g
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="3"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        HR Department
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Talent acquisition, employee welfare
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                10 Oct, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-4.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-1.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-2.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +A
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="4"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Sales Division
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Customer relations, sales strategy execution
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                05 Oct, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-24.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-7.png'"/>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="5"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        IT Support
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Maintaining IT infrastructure, user support
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                21 Oct, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-4.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-2.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary">
                  +s
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="6"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Research and Development
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Innovating and developing new products
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                28 Sep, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-3.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-6.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +4
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="7"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Finance Department
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing company finances
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                22 Sep, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-8.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-9.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +8
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="8"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Operations Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Ensuring smooth day-to-day operations
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                15 Sep, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-10.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-11.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +5
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="9"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Legal Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Handling all legal matters
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                10 Sep, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-12.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-13.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +7
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="10"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Customer Service
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Providing customer support and assistance
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                05 Sep, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-14.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-15.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +3
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="11"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Procurement Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Sourcing and purchasing materials
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                30 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-16.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-17.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary">
                  +6
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="12"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Quality Assurance
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Ensuring product quality
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                25 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-18.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-19.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +2
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="13"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Logistics Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing supply chain and distribution
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                20 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-20.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-21.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +9
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="14"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Design Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Creating visual content and UI designs
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                15 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-22.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-23.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +4
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="15"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Technical Writing
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Documenting product features and guides
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                10 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-24.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-25.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +3
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="16"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Data Analytics
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Analyzing data and generating insights
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                05 Aug, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-26.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-27.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary">
                  +6
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="17"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Security Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Ensuring data and system security
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                30 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-28.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-29.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +2
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="18"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Admin Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Handling administrative tasks
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                25 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-30.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-31.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +3
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="19"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Customer Relations
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing customer interactions
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                20 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-32.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-33.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +7
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="20"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Training Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Training employees on new systems
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                15 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-34.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-1.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +5
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="21"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Project Management
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing company projects
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                10 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-2.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-3.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary">
                  +8
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="22"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Business Analysis
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Analyzing business processes
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                05 Jul, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-4.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-5.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +4
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="23"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Corporate Communications
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing internal and external communications
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                30 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-6.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-7.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +6
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="24"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Compliance Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Ensuring regulatory compliance
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                25 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-8.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-9.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +7
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="25"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Risk Management
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Identifying and managing risks
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                20 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-10.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-11.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +5
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="26"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Strategy Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Developing and implementing strategies
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                15 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-12.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-13.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-primary-inverse ring-primary-light bg-primary">
                  +6
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="27"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Executive Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Leading the company
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                10 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-14.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-15.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +8
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="28"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Social Media Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Managing social media channels
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                05 Jun, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-16.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-17.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-success-inverse ring-success-light bg-success">
                  +4
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="29"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Supply Chain Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Overseeing the supply chain
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                30 May, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-18.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-19.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-warning-inverse ring-warning-light bg-warning">
                  +5
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                                <input class="checkbox checkbox-sm" data-datatable-row-check="true" type="checkbox" value="30"/>
                                            </td>
                                            <td>
                                                <div class="flex flex-col gap-1.5">
                                                    <a class="leading-none font-semibold text-sm text-gray-900 hover:text-primary" href="#">
                                                        Content Team
                                                    </a>
                                                    <span class="text-2sm text-gray-600">
                 Creating and managing content
                </span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="rating">
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label checked">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                    <div class="rating-label indeterminate">
                                                        <i class="rating-on ki-solid ki-star text-base leading-none" style="width: 50.0%">
                                                        </i>
                                                        <i class="rating-off ki-outline ki-star text-base leading-none">
                                                        </i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                25 May, 2024
                                            </td>
                                            <td>
                                                <div class="flex -space-x-2">
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-20.png'"/>
                                                    </div>
                                                    <div class="flex">
                                                        <img class="hover:z-5 relative shrink-0 rounded-full ring-1 ring-light-light size-[30px]" :src="assetPath + '/media/avatars/300-21.png'"/>
                                                    </div>
                                                    <div class="flex">
                 <span class="relative inline-flex items-center justify-center shrink-0 rounded-full ring-1 font-semibold leading-none text-3xs size-[30px] text-info-inverse ring-info-light bg-info">
                  +3
                 </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-notepad-edit">
                                                    </i>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-icon btn-clear btn-light" href="#">
                                                    <i class="ki-filled ki-trash">
                                                    </i>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
                                    <div class="flex items-center gap-2 order-2 md:order-1">
                                        Show
                                        <select class="select select-sm w-16" data-datatable-size="true" name="perpage">
                                        </select>
                                        per page
                                    </div>
                                    <div class="flex items-center gap-4 order-1 md:order-2">
            <span data-datatable-info="true">
            </span>
                                        <div class="pagination" data-datatable-pagination="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_team">
            <form @submit.prevent="create" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create Team
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
                                <input class="input" v-model="createTeamForm.name" type="text" />
                                <span v-if="createTeamForm.errors.name" class="form-hint text-danger">
                                    {{ createTeamForm.errors.name }}
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
