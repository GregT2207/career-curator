<script setup lang="ts">
    import axios from 'axios';
    import { reactive, onMounted } from 'vue';
    import { Listing } from '../types';
    import { ListingLink } from '../types';
    import LoadingSpinner from './utilities/LoadingSpinner.vue';
    import ListingCard from './ListingCard.vue';

    // how many listings to fetch at one time
    var batchSize = 3;

    const state = reactive({
        loaded: false,
        loadingMore: false,
        links: [] as ListingLink[],
        listings: [] as Listing[],
        nextListingsIndex: 0,
        failedSites: 0,
        failedLinks: 0,
    });

    onMounted(() => {
        axios.get('/api/listings/links?search=php').then(response => {
            state.links = response.data.data;
            state.failedSites = response.data.failedSites;
            getNextListings();

            state.loaded = true;
        }).catch(error => {
            state.loaded = true;
        });
    });

    function getNextListings() {
        state.loadingMore = true;

        var nextLinks = state.links.splice(state.nextListingsIndex, batchSize);
        nextLinks.forEach(link => {
            axios.get('/api/listings', {
                params: {
                    site: link.site.toLowerCase(),
                    url: link.url,
                }
            }).then(response => {
                state.listings.push(response.data.data);

                state.loadingMore = false;
            }).catch(error => {
                state.failedLinks++;
            });
        });
    }
</script>

<template>
    <div v-if="state.loaded">
        <div v-if="state.listings.length" :class="'grid-cols-' + batchSize" class="grid gap-6">
            <ListingCard
                v-for="listing in state.listings"
                :listing="listing"
            />

            <div v-if="state.loadingMore">
                <LoadingSpinner />
            </div>
        </div>

        <div v-else>
            <h4>No listings found</h4>
        </div>
    </div>

    <div v-else class="flex justify-center items-center pt-12">
        <LoadingSpinner />
    </div>
</template>