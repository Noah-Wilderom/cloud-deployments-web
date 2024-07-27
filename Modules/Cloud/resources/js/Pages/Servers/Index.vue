<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onBeforeUnmount, onMounted, nextTick, ref} from 'vue'
import {useForm, usePage, router, Link} from '@inertiajs/vue3'
import { KTDataTable, KTModal } from "@metronic/core/index.ts";

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)

onMounted(() => {
    KTDataTable.init()
    KTModal.init()

    const el = document.getElementById("openCreateServer")
    if (el !== null) {
        el.addEventListener("click", () => {
            openCreateServer()
        })
    }
})

const createServerForm = useForm({
    customer_id: null,
    type: null,
    name: null,
    cloud_provider_type: null,
})

const openCreateServer = () => {
    const modalEl = document.getElementById("create_server");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const create = () => {
    createServerForm.post(route("cloud::servers.store"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_server")
            const modal = KTModal.getInstance(modalEl)
            modal.hide()

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["servers"]
            })
        },
    })
}

defineProps(["servers", "customers", "cloudTypes", "serverTypes"])
</script>

<template>
    <AppLayout title="Servers">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Servers
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Intuitive Access to In-Depth Customization
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button @click="openCreateServer" type="button" class="btn btn-sm btn-light">
                        Create Server
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
                            Servers
                        </h3>
                        <div class="flex gap-6">
                            <div class="relative">
                                <i class="ki-outline ki-magnifier leading-none text-md text-gray-500 absolute top-1/2 left-0 -translate-y-1/2 ml-3">
                                </i>
                                <input class="input input-sm pl-8" placeholder="Search Servers" type="text"/>
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
                                                   Server Type
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="min-w-[150px]">
                                           <span class="sort">
                                               <span class="sort-label">
                                                   Status
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
                                                   IP
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                        </th>
                                        <th class="w-[80px]">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="servers.length < 1">
                                        <td colspan="6">
                                            <button id="openCreateServer" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                <i class="mx-auto text-5xl text-gray-400 ki-filled ki-cloud"></i>
                                                <span class="mt-2 block text-sm font-semibold text-gray-900">Create a new server</span>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr v-if="servers.length > 0" v-for="server in servers" :key="server.id">
                                        <td>
                                           <span class="text-xs font-bold">
                                               {{ server.id }}
                                           </span>
                                        </td>
                                        <td>
                                           <span class="">
                                               {{ server.type }}
                                           </span>
                                        </td>
                                        <td>
                                           <span class="badge font-bold text-white"
                                               :class="{
                                                   'badge-success': server.status === 'online',
                                                   'badge-danger': server.status === 'offline',
                                                   'badge-warning': server.status === 'created',
                                                   'badge-info': server.status === 'dispatched' || server.status === 'provisioning'
                                               }"
                                           >
                                               {{ server.status }}
                                           </span>
                                        </td>
                                        <td>
                                           <span class="badge badge-dark">
                                               {{ server.name }}
                                           </span>
                                        </td>
                                        <td>
                                           <span class="">
                                               {{ server.host?.ipv4 ?? "loading..." }}
                                           </span>
                                        </td>
                                        <td>
                                            <Link :href="route('cloud::servers.show', {'server': server.id})" method="get" class="btn btn-sm btn-light">
                                                Edit
                                            </Link>
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

        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_server">
            <form @submit.prevent="create" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create Server
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Customer
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createServerForm.customer_id">
                                    <option value="" selected>No Customer</option>
                                    <option v-for="customer in customers" :value="customer.id">
                                        {{ customer.name }} <span class="font-light">({{ customer.email }})</span>
                                    </option>
                                </select>
                                <span v-if="createServerForm.errors.customer_id" class="form-hint text-danger">
                                    {{ createServerForm.errors.customer_id }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Type
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createServerForm.type">
                                    <option v-for="serverType in serverTypes" :value="serverType.value">
                                        {{ serverType.name }}
                                    </option>
                                </select>
                                <span v-if="createServerForm.errors.type" class="form-hint text-danger">
                                    {{ createServerForm.errors.type }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createServerForm.name" type="text" />
                                <span v-if="createServerForm.errors.name" class="form-hint text-danger">
                                    {{ createServerForm.errors.name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Cloud Provider Type
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createServerForm.cloud_provider_type">
                                    <option v-for="cloudType in cloudTypes" :value="cloudType.id">
                                        {{ cloudType.name }} | Price: {{ cloudType.monthly_price }} | Cores: {{ cloudType.cores }} | Memory: {{ cloudType.memory }}GB | Disk: {{ cloudType.disk }}GB
                                    </option>
                                </select>
                                <span v-if="createServerForm.errors.cloud_provider_type" class="form-hint text-danger">
                                    {{ createServerForm.errors.cloud_provider_type }}
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
                        <button :disabled="createServerForm.processing" type="submit" class="btn btn-primary">
                           <svg aria-hidden="true" :class="{'hidden': !createServerForm.processing}" class="w-4 h-4 text-blue-700 animate-spin dark:text-white fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                               <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                               <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                           </svg>
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
