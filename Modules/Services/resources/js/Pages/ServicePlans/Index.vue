<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref} from 'vue'
import {useForm, usePage, router, Link} from '@inertiajs/vue3'
import { KTDataTable, KTModal } from "@metronic/core/index.ts";

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)

onMounted(() => {
    KTDataTable.init()
    KTModal.init()

    const el = document.getElementById("openCreateServicePlan")
    if (el !== null) {
        el.addEventListener("click", () => {
            openCreateServicePlan()
        })
    }
})

const createServicePlanForm = useForm({
    service_type: null,
    name: null,
    base_price: null,
})

const openCreateServicePlan = () => {
    const modalEl = document.getElementById("create_serviceplan");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const create = () => {
    createServicePlanForm.post(route("services::service-plans.store"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_serviceplan")
            const modal = KTModal.getInstance(modalEl)
            modal.hide()

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["plans"]
            })
        },
    })
}

defineProps(["plans", "service_types"])
</script>

<template>
    <AppLayout title="Service Plans">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Service Plans
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Intuitive Access to In-Depth Customization
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button @click="openCreateServicePlan" type="button" class="btn btn-sm btn-light">
                        Create Service Plan
                    </button>
                </div>
            </div>
        </div>
        <!-- end: container -->

        <div class="container-fixed">
            <div class="grid">
                <div class="card card-grid min-w-full">
                    <div class="card-header py-5 flex-wrap">
                        <h3 class="card-title">
                            Service Plans
                        </h3>
                        <div class="flex gap-6">
                            <div class="relative">
                                <i class="ki-outline ki-magnifier leading-none text-md text-gray-500 absolute top-1/2 left-0 -translate-y-1/2 ml-3">
                                </i>
                                <input class="input input-sm pl-8" placeholder="Search Domains" type="text"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="5">
                            <div class="scrollable-x-auto">
                                <table class="table table-auto table-border" data-datatable-table="true" id="grid_table">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[175px]">
                                           <span class="sort asc">
                                               <span class="sort-label">
                                                   ID
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="min-w-[150px]">
                                           <span class="sort">
                                               <span class="sort-label">
                                                   Service Type
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="min-w-[150px]">
                                           <span class="sort">
                                               <span class="sort-label">
                                                   Name
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="min-w-[150px]">
                                           <span class="sort">
                                               <span class="sort-label">
                                                   Base Price
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="w-[80px]">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="plans.length < 1">
                                        <td colspan="5">
                                            <button id="openCreateServicePlan" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                <i class="mx-auto text-5xl text-gray-400 ki-filled ki-book-open"></i>
                                                <span class="mt-2 block text-sm font-semibold text-gray-900">Create a new Service Plan</span>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="plans.length > 0" v-for="plan in plans" :key="plan.id">
                                        <td>
                                           <span class="text-xs font-bold">
                                               {{ plan.id }}
                                           </span>
                                        </td>
                                        <td>
                                            <span>
                                                {{ service_types[plan.service_type] ?? "-" }}
                                            </span>
                                        </td>
                                        <td>
                                           <span class="">
                                               {{ plan.name }}
                                           </span>
                                        </td>
                                        <td>
                                            <span class="">
                                               &euro;{{ (plan.base_price / 100).toFixed(2) }}
                                           </span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-light" href="#">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-3 text-gray-600 text-2sm font-medium">
                                <div class="flex items-center gap-2">
                                    Show
                                    <select class="select select-sm w-16" data-datatable-size="true" name="perpage">
                                    </select>
                                    per page
                                </div>
                                <div class="flex items-center gap-4">
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

        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_serviceplan">
            <form @submit.prevent="create" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create Service Plan
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="w-full">
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Service Plan
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createServicePlanForm.service_type">
                                    <option value="" selected>No Type</option>
                                    <option v-for="(type, val) in service_types" :value="val">
                                        {{ type }}
                                    </option>
                                </select>
                                <span v-if="createServicePlanForm.errors.service_type" class="form-hint text-danger">
                                    {{ createServicePlanForm.errors.service_type }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createServicePlanForm.name" type="text" />
                                <span v-if="createServicePlanForm.errors.name" class="form-hint text-danger">
                                    {{ createServicePlanForm.errors.name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Base Price
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createServicePlanForm.base_price" type="number" step="0.01" />
                                <span v-if="createServicePlanForm.errors.base_price" class="form-hint text-danger">
                                    {{ createServicePlanForm.errors.base_price }}
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
