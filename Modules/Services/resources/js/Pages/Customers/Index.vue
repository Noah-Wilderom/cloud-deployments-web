<script setup>
import AppLayout from "@/Pages/Layout/AppLayout.vue"
import {computed, onMounted, ref} from 'vue'
import {useForm, usePage, router} from '@inertiajs/vue3'
import { KTDataTable, KTModal } from "@metronic/core/index.ts";

const tab = ref(null)
const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const auth = computed(() => page.props.auth.user)

onMounted(() => {
    KTDataTable.init()
    KTModal.init()

    const el = document.getElementById("openCreateCustomer")
    if (el !== null) {
        el.addEventListener("click", () => {
            openCreateCustomer()
        })
    }
})

const createCustomerForm = useForm({
    company_name: null,
    name: null,
    email: null,
})

const openCreateCustomer = () => {
    const modalEl = document.getElementById("create_customer");
    const modal = KTModal.getInstance(modalEl);
    modal.show();
}

const create = () => {
    createCustomerForm.post(route("services::customers.store"), {
        onSuccess: (resp) => {
            const modalEl = document.getElementById("create_customer")
            const modal = KTModal.getInstance(modalEl)
            modal.hide()

            router.visit(resp.url, {
                preserveScroll: true,
                only: ["customers"]
            })
        },
    })
}

defineProps(["customers"])
</script>

<template>
    <AppLayout title="Customers">
        <!-- begin: container -->
        <div class="container-fixed">
            <div class="flex flex-wrap items-center lg:items-end justify-between gap-5 pb-7.5">
                <div class="flex flex-col justify-center gap-2">
                    <h1 class="text-xl font-semibold leading-none text-gray-900">
                        Customers
                    </h1>
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-600">
                        Intuitive Access to In-Depth Customization
                    </div>
                </div>
                <div class="flex items-center gap-2.5">
                    <button @click="openCreateCustomer" type="button" class="btn btn-sm btn-light">
                        Create Customer
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
                           Customers
                       </h3>
                       <div class="flex gap-6">
                           <div class="relative">
                               <i class="ki-outline ki-magnifier leading-none text-md text-gray-500 absolute top-1/2 left-0 -translate-y-1/2 ml-3">
                               </i>
                               <input class="input input-sm pl-8" placeholder="Search Members" type="text"/>
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
                                                   Company Name
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
                                                   Email
                                               </span>
                                               <span class="sort-icon"></span>
                                           </span>
                                       </th>
                                       <th class="w-[80px]">
                                       </th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-if="customers.length < 1">
                                       <td colspan="5">
                                           <button id="openCreateCustomer" type="button" class="relative block w-full rounded-lg border-2 border-dashed border-gray-300 p-12 text-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                               <i class="mx-auto text-5xl text-gray-400 ki-filled ki-users"></i>
                                               <span class="mt-2 block text-sm font-semibold text-gray-900">Create a new customer</span>
                                           </button>
                                       </td>
                                   </tr>

                                   <tr v-if="customers.length > 0" v-for="customer in customers" :key="customer.id">
                                       <td>
                                           <span class="text-xs font-bold">
                                               {{ customer.id }}
                                           </span>
                                       </td>
                                       <td>
                                           <span class="">
                                               {{ customer.company_name }}
                                           </span>
                                       </td>
                                       <td>
                                           <span class="">
                                               {{ customer.name }}
                                           </span>
                                       </td>
                                       <td>
                                           <span class="">
                                               {{ customer.email }}
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

        <div class="modal" data-modal-backdrop-static="true" data-modal="true" id="create_customer">
            <form @submit.prevent="create" class="modal-content max-w-[600px] top-[10%]">
                <div class="modal-header">
                    <h3 class="modal-title">
                        Create Customer
                    </h3>
                    <button type="button" class="btn btn-xs btn-icon btn-light" data-modal-dismiss="true">
                        <i class="ki-outline ki-cross"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="w-full">
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Company Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createCustomerForm.company_name" type="text" />
                                <span v-if="createCustomerForm.errors.company_name" class="form-hint text-danger">
                                    {{ createCustomerForm.errors.company_name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Name
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createCustomerForm.name" type="text" />
                                <span v-if="createCustomerForm.errors.name" class="form-hint text-danger">
                                    {{ createCustomerForm.errors.name }}
                                </span>
                            </div>
                        </div>
                        <div class="flex py-2 items-baseline flex-wrap lg:flex-nowrap gap-2.5">
                            <label class="form-label max-w-32">
                                Email
                            </label>
                            <div class="flex flex-col w-full gap-1">
                                <input class="input" v-model="createCustomerForm.email" type="email" />
                                <span v-if="createCustomerForm.errors.email" class="form-hint text-danger">
                                    {{ createCustomerForm.errors.email }}
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
