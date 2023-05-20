<script setup>
    import axios from 'axios';
import Header from '../components/Header.vue';
    import Listing from '../components/Listing.vue';
    import { onMounted } from 'vue';

    onMounted(() => {
        axios.get('/api/listings?search_term=laravel').then(response => {
            console.log(response);
        });
    });
</script>

<template>
    <Header />

    <div class="container mx-auto p-6">
        <div class="grid grid-cols-4 gap-6">
            <Listing />
            <Listing />
            <Listing />
            <Listing />
            <!-- <div v-for=""></div> -->
        </div>
    </div>
</template>