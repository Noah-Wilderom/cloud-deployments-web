<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref} from 'vue'
import {useForm, usePage, router, Link} from '@inertiajs/vue3'
import { KTDataTable, KTModal } from "@metronic/core/index.ts";
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const tab = ref(null)
const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)

onMounted(() => {
    KTDataTable.init()
    KTModal.init()

    const el = document.getElementById("openCreateDomain")
    if (el !== null) {
        el.addEventListener("click", () => {
            openCreateDomain()
        })
    }
})

const createDomainForm = useForm({
    customer_id: null,
    name: null,
})

const openCreateDomain = () => {
    const modalEl = document.getElementById("create_domain");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const create = () => {
    createDomainForm.post(route("services::domains.store"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_domain")
            const modal = KTModal.getInstance(modalEl)
            modal.hide()

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["domains"]
            })
        },
    })
}

defineProps(["domains", "customers", "cloudflareDomains"])
</script>

<template>
    <AppLayout title="Domains">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Domains
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Intuitive Access to In-Depth Customization
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button @click="openCreateDomain" type="button" class="btn btn-sm btn-light">
                        Create Domain
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
                           Domains
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
                                                   Customer
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
                                                   Verified
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                       </th>
                                       <th class="w-[80px]">
                                       </th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-if="domains.length < 1">
                                       <td colspan="5">
                                           <button id="openCreateDomain" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                               <i class="mx-auto text-5xl text-gray-400 ki-filled ki-laptop"></i>
                                               <span class="mt-2 block text-sm font-semibold text-gray-900">Create a new domain</span>
                                           </button>
                                       </td>
                                   </tr>

                                   <tr v-if="domains.length > 0" v-for="domain in domains" :key="domain.id">
                                       <td>
                                           <span class="text-xs font-bold">
                                               {{ domain.id }}
                                           </span>
                                       </td>
                                       <td>
                                           <Link :href="route('services::customers.show', {'customer': domain.customer.id})"
                                                 method="get"
                                                 class="link hover:text-blue-700">
                                               {{ domain.customer.name }}
                                           </Link>
                                       </td>
                                       <td>
                                           <span class="">
                                               {{ domain.name }}
                                           </span>
                                       </td>
                                       <td>
                                           <span v-if="domain.verified" class="badge badge-success">
                                               Active
                                           </span>
                                           <span v-else class="badge badge-danger">
                                               Inactive
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

        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_domain">
            <form @submit.prevent="create" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create Domain
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="w-full">
                        <div class="flex items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Customer
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createDomainForm.customer_id">
                                    <option value="" selected>No Customer</option>
                                    <option v-for="customer in customers" :value="customer.id">
                                        {{ customer.name }} <span class="font-light">({{ customer.email }})</span>
                                    </option>
                                </select>
                                <span v-if="createDomainForm.errors.customer_id" class="form-hint text-danger">
                                    {{ createDomainForm.errors.customer_id }}
                                </span>
                            </div>
                        </div>
                        <div v-if="cloudflareDomains" class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Cloudflare domain
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <select class="select" v-model="createDomainForm.name">
                                    <option v-for="domain in cloudflareDomains" :value="domain.name">
                                        {{ domain.name }} <span class="font-light">({{ domain.account.name }})</span>
                                    </option>
                                </select>
                                <span v-if="createDomainForm.errors.customer_id" class="form-hint text-danger">
                                    {{ createDomainForm.errors.customer_id }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createDomainForm.name" type="text" />
                                <span v-if="createDomainForm.errors.name" class="form-hint text-danger">
                                    {{ createDomainForm.errors.name }}
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
