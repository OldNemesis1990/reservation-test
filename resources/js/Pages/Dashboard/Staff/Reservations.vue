<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios'
import { ref, defineProps } from 'vue'

let { reservations } = defineProps(['reservations']);

let moreInfo = ref(false);
let currentItemId = ref(null);



const showMoreInfo = (id) => {
    moreInfo.value = !moreInfo.value;
    currentItemId.value = id;
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
                <div v-for="reservation in reservations" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg my-6">
                    <div>
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
                                <p>Status</p>
                                <p>{{ reservation.status }}</p>
                            </div>
                            <div>
                                <p>Check In</p>
                                <p>{{ reservation.check_in == 0 ? 'False' : "True" }}</p>
                            </div>
                        </div>
                        <div v-if="moreInfo && currentItemId == reservation.id" class="w-[100%] p-6 bg-slate-100 flex p-6 items-center justify-between">
                            <div v-if="reservation.check_in">
                                <p>Check In Time</p>
                                <p>{{ reservation.time_check_in }}</p>
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
                                <p>Total Days</p>
                                <p>{{ reservation.amount_of_days }}</p>
                            </div>
                            <div>
                                <p>Total Amount</p>
                                <p>R{{ reservation.total }}</p>
                            </div>
                        </div>
                        <div class="flex columns-3 gap-x-1">
                            <div>
                                <button @click.prevent="checkGuestIn(reservation.id)" class="bg-lime-700 hover:bg-lime-400 text-slate-100 hover:text-slate-800 py-[8px] px-[16px] rounded-[2px]">
                                {{ reservation.check_in ? 'Guest Checked In' : 'Check Guest In' }}
                                </button> 
                            </div>
                            <div>
                                <button @click.prevent="showMoreInfo(reservation.id)" class="bg-sky-700 hover:bg-sky-400 text-slate-100 hover:text-slate-800 py-[8px] px-[16px] rounded-[2px]">
                                    {{ moreInfo && currentItemId == reservation.id ? 'Less Info' : 'More Info' }}
                                </button> 
                            </div>
                            <div>
                                <button v-if="$page.props.auth.user.roles[0].name == 'admin'" @click.prevent="deleteReservation(reservation.id)" class="bg-red-700 hover:bg-red-400 text-slate-100 hover:text-slate-800 py-[8px] px-[16px] rounded-[2px]">
                                    Delete
                                </button> 
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
        return{
            reservations: this.reservations
        }
    },
    methods: {
        checkGuestIn(id) {
            let formData = new FormData()

            formData.append('id', id)
            // using axios for json response
            axios.post(route('dashboard.reservation.checkIn'), formData )
            .then( (response) => {
                this.reservations = response.data.reservations
                
            })
        },
        deleteReservation(id) {
            let formData = new FormData()

            formData.append('id', id) 

            axios.post(route('dashboard.reservation.destroy'), formData)
            .then( (response) => {
                this.reservations = response.data.reservations
            } )
        }
    }
  }
</script>