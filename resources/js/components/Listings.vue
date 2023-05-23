<script setup lang="ts">
    import axios from 'axios';
    import { reactive, onMounted } from 'vue';
    import { Listing } from '../types';
    import { ListingLink } from '../types';
    import LoadingSpinner from './utilities/LoadingSpinner.vue';
    import ListingCard from './ListingCard.vue';

    var batchSize = 3;

    var defaultReactiveValues = {
        loaded: false,
        loadingMore: false,
        searched: false,
        links: [] as ListingLink[],
        listings: [] as Listing[],
        nextListingsIndex: 0,
        failedSites: 0,
        failedLinks: 0,
    };
    const state = reactive(defaultReactiveValues);

    onMounted(() => {
        const searchBox = <HTMLInputElement>document.querySelector('#searchBox');
        if (searchBox) {
            searchBox.addEventListener('search', () => {
                Object.assign(state, defaultReactiveValues);
                state.searched = true;

                axios.get('/api/listings/links?search=' + searchBox.value).then(response => {
                    state.links = response.data.data;
                    state.failedSites = response.data.failedSites;

                    for (let i = 0; i < 3; i++) {
                        getNextBatch();
                    }
                }).catch(error => {
                    state.loaded = true;
                });
            })
        }
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
            }).catch(error => {
                state.failedLinks++;
            }).finally(() => {
                state.loaded = true;
                state.loadingMore = false;
            });
        });
    }
</script>

<template>
    <div v-if="!state.searched">
        <h4 class="flex justify-center w-full text-3xl font-semibold">Search for listings to begin</h4>
    </div>

    <div v-else-if="state.loaded">
        <div v-if="state.listings.length">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <ListingCard
                    v-for="listing in state.listings"
                    :listing="listing"
                />
            </div>

            <div v-if="state.loadingMore" class="flex justify-center items-center mt-6 pt-12">
                <LoadingSpinner />
            </div>

            <div v-else class="flex justify-center">
                <button @click="getNextBatch()" class="w-full mx-auto mt-6 px-4 py-4 text-3xl bg-gray rounded-lg">
                    See more
                </button>
            </div>
        </div>

        <div v-else>
            <h4 class="flex justify-center w-full text-2xl">No listings found</h4>
        </div>
    </div>

    <div v-else class="flex justify-center items-center pt-12">
        <LoadingSpinner />
    </div>
</template>