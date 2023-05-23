<script setup lang="ts">
    import axios from 'axios';
    import { reactive, onMounted } from 'vue';
    import { Listing } from '../types';
    import { ListingLink } from '../types';
    import LoadingSpinner from './utilities/LoadingSpinner.vue';
    import ListingCard from './ListingCard.vue';

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
        // get array of links for jobs
        axios.get('/api/listings/links?search=php').then(response => {
            state.links = response.data.data;
            state.failedSites = response.data.failedSites;

            getNextBatch();
        }).catch(error => {
            state.loaded = true;
        });
    });

    function getNextBatch() {
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
                state.loaded = true;
            }).catch(error => {
                state.failedLinks++;
            });
        });
    }
</script>

<template>
    <div v-if="state.loaded">
        <div v-if="state.listings.length">
            <div class="grid grid-cols-3 gap-6">
                <ListingCard
                    v-for="listing in state.listings"
                    :listing="listing"
                />
            </div>

            <div v-if="state.loadingMore" class="mt-6">
                <LoadingSpinner />
            </div>

            <div v-else class="flex justify-center">
                <button @click="getNextBatch()" class="w-full mx-auto mt-6 px-4 py-2 text-2xl bg-gray rounded-lg">
                    See more
                </button>
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