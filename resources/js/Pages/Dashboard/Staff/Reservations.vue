<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'
import { ref } from 'vue'

let { reservations } = defineProps(['reservations']);

const checkGuestIn = async (id) => {
    console.log(id)
    let formData = new FormData()

    formData.append('id', id)
    // using axios for json response
    await axios.post(route('dashboard.reservation.checkIn'), formData )
    .then( (response) => {
        reservations = response.data.reservations
    })
}
</script>

<template>
    <Head title="Reservation Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div v-for="reservation in reservations">
                        <div class="flex p-6 items-center justify-between">
                            <div>
                                <p>Guest Name</p>
                                <p>{{ reservation.user.name }}</p>
                            </div>
                            <div>
                                <p>Accommodation Title</p>
                                <p>{{ reservation.accommodation.title }}</p>
                            </div>
                            <div>
                                <p>Arrival Date</p>
                                <p>{{ reservation.start_date }}</p>
                            </div>
                            <div>
                                <p>Depart Date</p>
                                <p>{{ reservation.end_date }}</p>
                            </div>
                            <div>
                                <p>Status</p>
                                <p>{{ reservation.status }}</p>
                            </div>
                            <div>
                                <p>Check In</p>
                                <p>{{ reservation.check_in == 0 ? 'False' : "True" }}</p>
                            </div>
                            <div>
                                <button @click.prevent="checkGuestIn(reservation.id)" class="bg-lime-700 hover:bg-lime-400 text-slate-100 hover:text-slate-800 py-[8px] px-[16px] rounded-[2px]">Check in</button> 
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
    // methods: {
    //     checkGuestIn(id) {

    //     }
    // }
  }
</script>