<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Staff Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        <h2>Hi {{ $page.props.auth.user.name }}!</h2>
                    </div>
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        <p>Welcome to this applications which I will call {{ $page.props.envName }}!</p>
                        <p>Please browse this application to experience capability!</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>