<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedNavigationLayout from './AuthenticatedNavigationLayout.vue';

const showingNavigationDropdown = ref(false);

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="dashboard-content flex justify-around">
                <div class="nav-left w-[12vw]">
                    <AuthenticatedNavigationLayout :isAdmin="isAdmin" :isManager="isManager" :isClerk="isClerk"></AuthenticatedNavigationLayout>
                </div>
                <div class="main-right w-[88vw]">
                    <slot />
                </div>

            </main>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        isAdmin() {
            return this.$page.props.auth.user.roles[0].name == 'admin';
        },
        isManager() {
            return this.$page.props.auth.user.roles[0].name === 'manager';
        },
        isClerk() {
            return this.$page.props.auth.user.roles[0].name === 'clerk';
        }
    },
    mounted() {
        // console.log(this.isAdmin, this.isManager, this.isClerk)
    }
}
</script>

<style scoped>
    .dashboard-content {
        width: 100%;
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        height: calc(100vh - 73px);
    }
    .nav-left {
        height: calc(100vh - 73px);
    }
    .main-right {
        height: calc(100vh - 73px);
        overflow-y: scroll;
    }
</style>