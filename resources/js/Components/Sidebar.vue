<script setup>
import SidebarItem from './SidebarItem.vue'
import SidebarGroup from './SidebarGroup.vue'
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import SidebarDropdown from "./SidebarDropdown.vue";
import SidebarDropdownSub from "./SidebarDropdownSub.vue";
import SidebarDropdownItem from "./SidebarDropdownItem.vue";

const page = usePage()

const assetPath = computed(() => page.props.assetPath)
const version = computed(() => page.props.version)
</script>

<template>
    <div class="sidebar dark:bg-coal-600 bg-light border-r border-r-gray-200 dark:border-r-coal-100 fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0" data-drawer="true" data-drawer-class="drawer drawer-start top-0 bottom-0" data-drawer-enable="true|lg:false" id="sidebar">
        <div class="sidebar-header hidden lg:flex items-center relative justify-between px-3 lg:px-6 shrink-0" id="sidebar_header">
            <a :href="$route('dashboard')">
                <img alt="Logo" class="default-logo dark:hidden h-10 max-w-none" :src="assetPath + '/logos/logo.svg'"/>
                <img alt="Logo" class="default-logo light:hidden h-10 max-w-none" :src="assetPath + '/logos/logo.svg'"/>

                <img alt="Logo" class="small-logo min-h-[22px] max-w-none" :src="assetPath + '/logos/logo.svg'"/>
            </a>
            <button class="btn btn-icon btn-icon-md size-[30px] rounded-lg border border-gray-200 dark:border-gray-300 bg-light text-gray-500 hover:text-gray-700 toggle absolute left-full top-2/4 -translate-x-2/4 -translate-y-2/4" data-toggle="body" data-toggle-class="sidebar-collapse" id="sidebar_toggle">
                <i class="ki-filled ki-black-left-line toggle-active:rotate-180 transition-all duration-300">
                </i>
            </button>
        </div>
        <div class="sidebar-content flex grow shrink-0 py-5 pr-2" id="sidebar_content">
            <div class="scrollable-y-hover grow shrink-0 flex pl-2 lg:pl-5 pr-1 lg:pr-3" data-scrollable="true" data-scrollable-dependencies="#sidebar_header" data-scrollable-height="auto" data-scrollable-offset="0px" data-scrollable-wrappers="#sidebar_content" id="sidebar_scrollable">
                <div class="menu flex flex-col grow gap-0.5" data-menu="true" data-menu-accordion-expand-all="false" id="sidebar_menu">
                    <div class="flex justify-end items-end">
                        <span class="badge badge-xs bg-gray-300 font-bold text-xs dark:text-white text-dark">
                            {{ version }}
                        </span>
                    </div>
                    <SidebarItem
                        :route="$route('dashboard')"
                        icon="ki-home"
                    >
                        {{ trans('pages.sidebar.dashboard') }}
                    </SidebarItem>
                    <SidebarDropdown
                        title="Teams"
                        route="teams::*"
                        icon="ki-users"
                    >
                        <SidebarDropdownItem
                            title="Teams"
                            :route="$route('teams::users.index')"
                        />
                        <SidebarDropdownItem
                            title="Users"
                            :route="$route('teams::users.index')"
                        />
                        <SidebarDropdownItem
                            :route="$route('teams::roles.index')"
                            title="Roles"
                        />
                    </SidebarDropdown>

                    <SidebarGroup title="Services" />
                    <SidebarItem
                        :route="$route('services::customers.index')"
                        icon="ki-users"
                    >
                        Customers
                    </SidebarItem>
                    <SidebarItem
                        :route="$route('services::domains.index')"
                        icon="ki-laptop"
                    >
                        Domains
                    </SidebarItem>
                    <SidebarItem
                        :route="$route('services::subscriptions.index')"
                        icon="ki-abstract-30"
                    >
                        Subscriptions
                    </SidebarItem>
                    <SidebarItem
                        :route="$route('services::service-plans.index')"
                        icon="ki-book-open"
                    >
                        Service Plans
                    </SidebarItem>

                    <SidebarGroup title="Cloud" />
                    <SidebarItem
                        :route="$route('cloud::servers.index')"
                        icon="ki-cloud"
                    >
                        Servers
                    </SidebarItem>

                    <SidebarItem
                        :route="$route('cloud::projects.index')"
                        icon="ki-code"
                    >
                        Projects
                    </SidebarItem>

                    <SidebarItem
                        :route="$route('cloud::database.index')"
                        icon="ki-abstract-14"
                    >
                        Database
                    </SidebarItem>

                    <SidebarItem
                        :route="$route('cloud::scripts.index')"
                        icon="ki-underlining"
                    >
                        Scripts
                    </SidebarItem>
                    <SidebarItem
                        :route="$route('cloud::backups.index')"
                        icon="ki-abstract-26"
                    >
                        Backups
                    </SidebarItem>

                    <SidebarGroup title="UserPortal" />
                    <SidebarDropdown
                        title="Profile"
                        route="userportal::*"
                        icon="ki-user"
                    >
                        <SidebarDropdownItem
                            title="Profile"
                            :route="$route('userportal::profile.index', {'tab': 'general'})"
                        />
                        <SidebarDropdownItem
                            title="SSH Keys"
                            :route="$route('userportal::profile.index', {'tab': 'ssh-keys'})"
                        />
                        <SidebarDropdownItem
                            title="Integrations"
                            :route="$route('userportal::profile.index', {'tab': 'integrations'})"
                        />
                        <SidebarDropdownItem
                            title="Backup configuration"
                            :route="$route('userportal::profile.index')"
                        />
                        <SidebarDropdownItem
                            title="Security"
                            :route="$route('userportal::profile.index')"
                        />
                        <SidebarDropdownItem
                            title="Settings"
                            :route="$route('userportal::profile.index')"
                        />
                        <SidebarDropdownItem
                            title="Invoices"
                            :route="$route('userportal::profile.index')"
                        />

                    </SidebarDropdown>
                    <SidebarItem
                        :route="$route('userportal::subscription.index')"
                        icon="ki-credit-cart"
                    >
                        Subscription
                    </SidebarItem>
                    <SidebarItem
                        route="/docs"
                        icon="ki-book"
                    >
                        Documentation
                    </SidebarItem>
                    <SidebarItem
                        :route="$route('userportal::support.index')"
                        icon="ki-abstract-35"
                    >
                        Support
                    </SidebarItem>
                </div>
            </div>
        </div>
    </div>
</template>
