<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Accommodations List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Accommodation</h2>
        </template>

        <div class="accommodation-title w-[100%] bg-slate-500 text-center text-xl py-[3vw] text-slate-300">
            <h1>{{ accommodation.title }}</h1>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">                
                <!-- THIS MUST BE THE ACCOMMODATION LIST -->
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-center">
                            <div>
                                <p>{{ accommodation.title }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    props: {
        accommodation: Array,
    },
    mounted() {
        console.log('Accommodations:', this.accommodation);
    },
    methods: {
    viewAccommodation(id) {
        // Handle view logic, e.g., navigate to the detailed view
        this.$inertia.visit(route('accommodations.show', { id }));
    },
    updateAccommodation(id) {
        // Handle update logic, e.g., navigate to the update form
        console.log('Update accommodation:', id);
    },
    deleteAccommodation(id) {
        // Handle delete logic, e.g., show confirmation modal
        console.log('Delete accommodation:', id);
    },
  },
};
</script>