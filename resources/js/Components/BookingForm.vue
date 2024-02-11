<script setup>
    import { Inertia } from '@inertiajs/inertia';
    import { Head, Link, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import axios from 'axios'
    import Calendar from '@/Components/Calendar.vue';

    const { accommodationId, pricePerAdult, pricePerChild} = defineProps(['accommodationId', 'pricePerAdult', 'pricePerChild']);

    const bookingForm = useForm({
      idNumber: "",
      accomodation_id: 0,
      name: "",
      email: "",
      contactNumber: "",
      numberOfAdults: 1,
      numberOfKids: 0,
      price_per_adult: pricePerAdult,
      price_per_child: pricePerChild,
      startDate: "0000-00-00",
      endDate: "0000-00-00",
      totalAmount: pricePerAdult,
      user: {},
      status: "pending",
      note: ""
    })

    const selectedDate = ref([]);
    const personFound = ref(false);

    const calculateTotal = () => {
      const totalAdults = bookingForm.numberOfAdults * pricePerAdult;
      const totalKids = bookingForm.numberOfKids * pricePerChild;
      if(selectedDate.value.length > 0) {
        const formattedStartDate = selectedDate.value[0].toISOString().split('T')[0];
        const formattedEndDate = selectedDate.value[1].toISOString().split('T')[0];

        // Convert to Date objects
        const startDate = new Date(formattedStartDate);
        const endDate = new Date(formattedEndDate);

        // Calculate the time difference in milliseconds
        const timeDifference = endDate.getTime() - startDate.getTime();

        // Convert milliseconds to days
        const daysDifference = timeDifference / (1000 * 60 * 60 * 24);

        bookingForm.totalAmount = (totalAdults + totalKids) * daysDifference;
      } else {
        bookingForm.totalAmount = totalAdults + totalKids;
      }
      
    };

    const submitBooking = async () => {
      try {
        const formattedStartDate = selectedDate.value[0].toISOString().split('T')[0];
        const formattedEndDate = selectedDate.value[1].toISOString().split('T')[0];

        bookingForm.startDate = formattedStartDate;
        bookingForm.endDate = formattedEndDate;

        // Convert to Date objects
        const startDate = new Date(formattedStartDate);
        const endDate = new Date(formattedEndDate);

        // Calculate the time difference in milliseconds
        const timeDifference = endDate.getTime() - startDate.getTime();
        
        let formData = new FormData();

        formData.append('accommodation_id', accommodationId)
        formData.append('user_id', bookingForm.user.id ?? "")
        formData.append('name', bookingForm.name ?? "")
        formData.append('email', bookingForm.email ?? "")
        formData.append('contact_number', bookingForm.contactNumber ?? "")
        formData.append('id_number', bookingForm.idNumber)
        formData.append('status', bookingForm.status)
        formData.append('start_date', bookingForm.startDate)
        formData.append('end_date', bookingForm.endDate)
        formData.append('amount_of_days', timeDifference)
        formData.append('total', bookingForm.totalAmount)
        formData.append('note', bookingForm.note)
        await axios.post(route('reservations.store'), formData)
        .then( (response) => {
          console.log(response)
        })
        .error( (err) => {
          console.log(err)
        } );

      } catch (error) {
        console.error('Error submitting form:', error);
      }
    };

    const searchId = async (idNumber) => {
      // const response = await axios.post(route('dashboard.reservation.idnumber', {idNumber}))
      const response = await axios.post(route('dashboard.reservation.idnumber', {idNumber}))
      
      if(response.data.connection == 1) {
        if(response.data.search_response = 1) {
          bookingForm.user = response.data.user
        }
        personFound.value = true
      }
      // personFound.value = false
    }
</script>

<template>
    <div class="fixed inset-0 z-50 overflow-auto bg-slate-900 bg-opacity-20 flex items-center justify-center">
      <div class="bg-white p-8 rounded-md shadow-md w-[50%] overflow-y-auto max-h-[90vh] py-6">
        <h2 class="text-lg font-semibold mb-4">Make Booking</h2>
  
        <!-- Your booking form goes here -->
        <form @submit.prevent="submitBooking">
          <div class="mb-4">
            <label for="idNumber" class="block text-sm font-medium text-gray-700">ID Number</label>
            <div class="flex justify-center gap-x-3">
              <input v-model="bookingForm.idNumber" type="text" id="idNumber" name="idNumber" class="mt-1 p-2 w-[80%] border rounded-md">
              <button @click.prevent="searchId(bookingForm.idNumber)" class="bg-sky-500 px-[16px] py-[8px] text-slate-100 rounded-[4px] w-[20%]">Search ID</button>
            </div>
          </div>

          <div v-if="personFound">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input v-if="bookingForm.user.name" v-model="bookingForm.user.name" type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md">
                <input v-if="!bookingForm.user.name" v-model="bookingForm.name" type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input v-if="bookingForm.user.email" v-model="bookingForm.user.email" type="text" id="email" name="email" class="mt-1 p-2 w-full border rounded-md">
                <input v-if="!bookingForm.user.email" v-model="bookingForm.email" type="text" id="email" name="email" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
                <label for="contactNumber" class="block text-sm font-medium text-gray-700">Contact Number</label>
                <input v-if="bookingForm.user.contact_number" v-model="bookingForm.user.contact_number" type="text" id="contactNumber" name="contactNumber" class="mt-1 p-2 w-full border rounded-md">
                <input v-if="!bookingForm.user.contact_number" v-model="bookingForm.contactNumber" type="text" id="contactNumber" name="contactNumber" class="mt-1 p-2 w-full border rounded-md">
            </div>
            <div class="mb-4">
              <Calendar v-model="selectedDate" @change="calculateTotal" />
            </div>
            <div class="mb-4 columns-2">
              <div>
                <label for="numberOfAdults" class="block text-sm font-medium text-gray-700">Number of Adults</label>
                <input v-model="bookingForm.numberOfAdults" @change="calculateTotal" type="number" id="numberOfAdults" name="numberOfAdults" class="mt-1 p-2 w-full border rounded-md">
              </div>    
              <div>
                <div class="mb-4">
                  <label for="numberOfKids" class="block text-sm font-medium text-gray-700">Number of Kids</label>
                  <input v-model="bookingForm.numberOfKids" @change="calculateTotal" type="number" id="numberOfKids" name="numberOfKids" class="mt-1 p-2 w-full border rounded-md">
                </div>
              </div>
            </div>
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">note</label>
                <textarea v-model="bookingForm.note" id="notes" name="notes" class="mt-1 p-2 w-full border rounded-md"></textarea>
            </div>
            <div class="mb-4">
              <p>Total Amount: <strong>R{{ bookingForm.totalAmount }}</strong></p>
            </div>
          </div>
  
          <div class="flex justify-end mt-4">
            <button type="button" @click="closeModal" class="text-gray-600">Cancel</button>
            <button v-if="personFound" type="submit" class="ml-4 bg-green-600 text-white px-4 py-2 rounded-md">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      accommodationId: Number,
      pricePerAdult: {
        type: Number,
        validator: (value) => value % 1 !== 0,
      },
      pricePerChild: {
        type: Number,
        validator: (value) => value % 1 !== 0,
      },
      user: Object, 
    },
    data() {
      return {
        selectedDate: []
      };
    },
    components: {
      Calendar,
    },
    methods: {
      closeModal() {
        // Emit an event or use a prop to notify the parent component to close the modal
        this.$emit('close');
      },
    },
  };
  </script>