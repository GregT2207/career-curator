<script setup lang="ts">
    import { reactive, watch, onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import { Listing } from '../types';
    import { ListingLink } from '../types';
    import LoadingSpinner from './utilities/LoadingSpinner.vue';
    import ListingCard from './ListingCard.vue';

    var batchSize = 9;

    const router = useRouter();

    const initialState = {
        loaded: false,
        loadingMore: false,
        searched: false,
        links: [] as ListingLink[],
        listings: [] as Listing[],
        failedSites: 0,
        failedLinks: 0,
    };
    const state = reactive({...initialState});

    onMounted(() => {
        configureSearch();
    });

    watch(() => router.currentRoute.value, (to, from) => {
        if (!to.query.search) {
            Object.assign(state, initialState);
            configureSearch();
        }
    });

    function configureSearch() {
        const searchBox = <HTMLInputElement>document.querySelector('#searchBox');
        if (searchBox) {
            searchBox.addEventListener('search', () => {
                search(searchBox.value);
            });

            var searchTerm = router.currentRoute.value.query.search?.toString();
            if (searchTerm) {
                searchBox.value = searchTerm;
                search(searchTerm);
            } else {
                searchBox.value = '';
            }
        }
    }

    function search(term) {
        Object.assign(state, initialState);
        state.searched = true;

        router.push({ query: { search: term } });
        axios.get('/api/listings/links?search=' + term).then(response => {
            state.links = response.data.data;
            state.failedSites = response.data.failedSites;

            if (state.links.length) {
                getNextBatch();
            } else {
                state.loaded = true;
            }
        }).catch(error => {
            state.loaded = true;
        });
    }

    function getNextBatch(forcedBatchSize = 0) {
        state.loadingMore = true;

        var nextLinks = state.links.splice(0, forcedBatchSize ? forcedBatchSize : batchSize);
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
                if (state.failedLinks < 10) {
                    // getNextBatch(1);
                }
            }).finally(() => {
                state.loaded = true;
                state.loadingMore = false;
            });
        });
    }
</script>

<template>
    <div v-if="!state.searched">
        <h4 class="flex justify-center w-full text-xl md:text-3xl font-semibold">Search for listings to begin</h4>
    </div>

    <div v-else-if="state.loaded">
        <div v-if="state.listings.length">
            <div class="relative grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 transition-all">
                <ListingCard
                    v-for="listing in state.listings"
                    :listing="listing"
                />
            </div>

            <div v-if="state.loadingMore" class="flex justify-center items-center mt-6 pt-12">
                <LoadingSpinner />
            </div>

            <div v-else class="flex justify-center">
                <button @click="getNextBatch()" class="w-full mx-auto mt-6 px-4 py-4 text-3xl bg-gray-700 rounded-lg">
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