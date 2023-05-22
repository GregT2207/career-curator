<script setup lang="ts">
    import axios from 'axios';
    import { reactive, onMounted } from 'vue';
    import { Listing } from '../types';
    import ListingCard from './ListingCard.vue';

    const state = reactive({
        loaded: false,
        listings: [] as Listing[],
    });

    onMounted(() => {
        axios.get('/api/listings?search_term=laravel').then(response => {
            state.listings = response.data.data;
            state.loaded = true;
        });
    });
</script>

<template>
    <div v-if="state.loaded">
        <div v-if="state.listings.length"  class="grid grid-cols-4 gap-6">
            <div v-for="listing in state.listings">
                <ListingCard
                    :listing="listing"
                />
            </div>
        </div>

        <div v-else>
            <h4>No listings found</h4>
        </div>
    </div>

    <div v-else>
        <h4>Loading...</h4>
    </div>
</template>