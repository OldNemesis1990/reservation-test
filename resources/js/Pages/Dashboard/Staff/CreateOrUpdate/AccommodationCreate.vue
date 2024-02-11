<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

const form = useForm({
  title: '',
  description: '',
  images: [], 
  price_per_adult: 0,
  price_per_child: 0,
  amenities: [''],
  isSubmitting: false, // Track the form submission state
  successMessage: null, // Track success message
});

const handleFileChange = (event) => {
    const fileList = event.target.files;
    form.images = fileList;
};

const addAmenity = () => {
  form.amenities.push('');
};

const removeAmenity = (index) => {
  form.amenities.splice(index, 1);
};

const submitForm = async () => {
    try {
        form.isSubmitting = true;
        const formData = new FormData();

        // Append form data
        formData.append('title', form.title);
        formData.append('description', form.description);
        formData.append('price_per_adult', form.price_per_adult);
        formData.append('price_per_child', form.price_per_child);
        formData.append('amenities', JSON.stringify(form.amenities));

        // Append images
        for (let i = 0; i < form.images.length; i++) {
            formData.append(`images[${i}]`, form.images[i]);
        }

        // Append featuredImage index
        formData.append('featuredImage', form.featuredImage);

        // Make an Axios request
        await axios.post(route('accommodation.store'), formData, {
            headers: {
            'Content-Type': 'multipart/form-data',
            },
        });

         // Handle the success response
        form.successMessage = 'Accommodation created successfully. Redirecting...';

        // Wait for 2 seconds before redirecting
        setTimeout(() => {
        form.successMessage = null; // Clear the success message
        
        window.location = route('accommodations.index');
        }, 2000);
    } catch (error) {
        form.isSubmitting = false;
        form.successMessage = false;
        form.successMessage = "An error occured";
        console.error('Error submitting form:', error);
    }
};
</script>

<template>
    <Head title="Accommodations List" />
    
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Accommodation</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submitForm">
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                            <input v-model="form.title" type="text" id="title" name="title" class="mt-1 p-2 w-full border rounded-md">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea v-model="form.description" id="description" name="description" rows="4" class="mt-1 p-2 w-full border rounded-md"></textarea>
                        </div>

                        <div class="mb-4 columns-2">
                            <div>
                                <label for="price_per_adult" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per adult</label>
                                <input v-model="form.price_per_adult" type="text" id="price_per_adult" name="price_per_adult" class="mt-1 p-2 w-full border rounded-md">
                            </div>
                            <div>
                                <label for="price_per_child" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price per child</label>
                                <input v-model="form.price_per_child" type="text" id="price_per_child" name="price_per_child" class="mt-1 p-2 w-full border rounded-md">
                            </div>
                        </div>

                        <!-- Create amenities for this accommodations -->
                        <div class="mb-4">
                            <div>
                                <label for="amenities" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amenities</label>
                                <div id="amenities" v-for="(amenity, index) in form.amenities" :key="index">
                                    <input v-model="form.amenities[index]" type="text" placeholder="Amenity" />
                                    <button @click.prevent="removeAmenity(index)" v-if="form.amenities.length > 1">Remove</button>
                                </div>
                                <button @click.prevent="addAmenity">Add More</button>
                            </div>
                        </div>

                        <!-- File input for image uploads -->
                        <div class="mb-4">
                            <div class="mb-4">
                                <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Images</label>
                                <input @change="handleFileChange" type="file" id="images" name="images" accept="image/*" multiple class="mt-1 p-2 w-full border rounded-md">
                            </div>

                            <!-- Checkbox for featured image -->
                            <div v-if="form.images.length">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select a Featured Image</label>
                                <div v-for="(image, index) in form.images" :key="index" class="flex items-center">
                                    <input v-model="form.featuredImage" :value="index" type="radio" :id="'featuredImage' + index" :name="'featuredImage' + index" class="mr-2">
                                    <label :for="'featuredImage' + index" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ image.name }}</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">{{ form.isSubmitting ? 'Creating...' : (form.successMessage ? 'Created' : 'Create') }}</button>

                        <div v-if="form.successMessage" class="text-green-500 mt-2">{{ form.successMessage }}</div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>