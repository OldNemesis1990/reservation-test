<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import BookingForm from '@/Components/BookingForm.vue';
</script>

<template>
    <Head title="Accommodations List" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Accommodation</h2>
        </template>

        <div class="py-12">
            <!-- THIS MUST BE THE SEARCH COMPONENT -->
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 ">
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        <div class="columns-2 flex justify-center">
                            <div>
                                <button @click="createAccommodation()" class="bg-blue-500 text-white px-[18px] py-[9px]">Create New</button>
                            </div>
                            <div></div>
                        </div>
                    </div>
                </div>
                <!-- THIS MUST BE THE ACCOMMODATION LIST -->
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-3 text-gray-900 dark:text-gray-100">
                        <div class="flex justify-center">
                            <div>
                                <div v-for="accommodation in accommodations" :key="accommodation.id">
                                    <!-- Details section -->
                                    <div class="my-4 p-6 bg-white shadow-sm shadow-slate-300 dark:bg-gray-800 ">
                                        <div class="flex w-[100%]">
                                            <div class="w-[70%]">
                                                <div class="p-3 bg-slate-100 shadow-sm shadow-slate-300">
                                                    <h2 class="text-[1.7vw]">
                                                        Name: {{ accommodation.title }}
                                                    </h2>
                                                </div>
                                                <div class="p-3">
                                                    <h3 class="mb-1 text-[1.4vw]">Description</h3>
                                                    <p>{{ accommodation.description }}</p>
                                                </div>
                                            </div>
                                            <div class="w-[30%]">
                                                <img v-if="accommodation.images && accommodation.images.length > 0" :src="`${$page.props.ziggy.url}/storage/${accommodation.images[0].image_path}`" alt="Featured Image" class="w-[100%]">
                                            </div>
                                        </div>
                                        <!-- Accommodation Amenities -->
                                        <!-- <div>
                                            <div>
                                                <h3>Amenities</h3>
                                                <ul>
                                                    <li v-for="amenity in accommodation.amenities" :key="amenity">{{ amenity }}</li>
                                                </ul>
                                            </div>
                                        </div> -->
                                        <!-- Action buttons -->
                                        <div class="flex space-x-4 mt-6">
                                            <button @click="viewAccommodation(accommodation.id)" class="bg-sky-800 hover:bg-sky-400 hover:text-gray-900 py-[8px] px-[16px] mt-4 text-slate-200 rounded-[2px]">View</button>
                                            <!-- Display update and delete buttons only for admin or manager -->
                                            <template v-if="isAdminOrManager()">
                                                <button @click="editAccommodation(accommodation.id)" class="bg-lime-800 hover:bg-lime-400 hover:text-gray-900 py-[8px] px-[16px] mt-4 text-slate-200 rounded-[2px]">Update</button>
                                                <button @click.prevent="deleteAccommodation(accommodation.id)" class="bg-red-800 hover:bg-red-400 hover:text-gray-900 py-[8px] px-[16px] mt-4 text-slate-200 rounded-[2px]">Delete</button>
                                            
                                            </template>

                                            <template v-if="isStaff()">
                                                <button @click="createReservation(accommodation.id, accommodation.price_per_adult, accommodation.price_per_child)" class="bg-green-800 hover:bg-green-400 hover:text-gray-900 py-[8px] px-[16px] mt-4 text-slate-200 rounded-[2px]">Make Booking</button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <BookingForm v-if="showBookingModal" @close="closeBookingModal" :accommodationId="selectedAccommodationId"
                                :pricePerAdult="pricePerAdult"
                                :pricePerChild="pricePerChild"  />
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
    data() {
        return {
        showBookingModal: false,
        selectedAccommodationId: null,
        pricePerAdult: null,
        pricePerChild: null,
        };
    },
    components: {
        BookingForm,
    },
    props: {
        accommodations: Array,
    },
    methods: {
        viewAccommodation(id) {
            // Handle view logic, e.g., navigate to the detailed view
            this.$inertia.visit(route('accommodations.show', { id }));
        },
        createAccommodation() {
            this.$inertia.visit(route('accommodation.create'))
        },
        editAccommodation(id) {
            console.log(id)
            this.$inertia.visit(route('accommodations.edit', { id }));
        },
        updateAccommodation(id) {
            // Handle update logic, e.g., navigate to the update form
            console.log('Update accommodation:', id);
        },
        deleteAccommodation(id) {
            const isConfirmed = confirm('Are you sure you want to delete this accommodation?');

            if (isConfirmed) {
                    // Call the backend to delete the accommodation
                this.$inertia.delete(route('accommodation.destroy', {id}))
                this.$inertia.visit(route('accommodations.index'));
            }
        },
        isAdminOrManager() {
            if(this.$page.props.auth.user.roles[0].name == 'admin') {
                return true;
            }
            if(this.$page.props.auth.user.roles[0].name === 'manager') {
                return true;
            }
            
        },
        createReservation(accommodationId, pricePerAdult, pricePerChild) {
            // Set the selected accommodation ID and show the booking modal
            this.selectedAccommodationId = accommodationId;
            this.showBookingModal = true;
            this.pricePerAdult = pricePerAdult;
            this.pricePerChild = pricePerChild;
        },
        closeBookingModal() {
            // Reset selected accommodation ID and hide the booking modal
            this.selectedAccommodationId = null;
            this.showBookingModal = false;
        },
        isStaff() {
            if(this.$page.props.auth.user.roles[0].name == 'admin') {
                return true;
            }
            if(this.$page.props.auth.user.roles[0].name === 'manager') {
                return true;
            }
            if(this.$page.props.auth.user.roles[0].name === 'clerk') {
                return true
            }
        }
  },
};
</script>