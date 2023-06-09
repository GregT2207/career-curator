<script setup lang="ts">
    import { reactive, onMounted } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';
    import { Listing } from '../types';
    import { ListingLink } from '../types';
    import LoadingSpinner from './utilities/LoadingSpinner.vue';
    import ListingCard from './ListingCard.vue';

    var batchSize = 12;

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

    function configureSearch() {
        var searchBox = <HTMLInputElement>document.querySelector('#searchBox');
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

    function search(term: string) {
        state.loaded = false;
        state.searched = true;
        state.links.splice(0);
        state.listings.splice(0);

        router.push({ query: { search: term } });
        axios.get('/api/listings/links?search=' + term).then(response => {
            state.links = response.data.data;
            state.failedSites = response.data.failedSites;

            if (state.links.length) {
                getBatch();
            } else {
                state.loaded = true;
            }
        }).catch(error => {
            state.loaded = true;
        });
    }

    function getBatch(forcedBatchSize = 0) {
        state.loadingMore = true;

        var promises: Promise<Listing>[] = [];

        var nextLinks = state.links.splice(0, forcedBatchSize ? forcedBatchSize : batchSize);
        nextLinks.forEach(link => {
            promises.push(
                axios.get('/api/listings', {
                    params: {
                        site: link.site.toLowerCase(),
                        url: link.url,
                    }
                }).then(response => {
                    return response.data.data;
                }).catch(error => {
                    state.failedLinks++;
                    if (state.failedLinks < 10) {
                        getBatch(1);
                    }
                })
            );
        });

        Promise.all(promises).then(newListings => {
            state.listings = state.listings.concat(newListings);

            state.loaded = true;
            state.loadingMore = false;
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
                <button @click="getBatch()" class="w-full mx-auto mt-6 px-4 py-4 text-3xl bg-gray-700 rounded-lg">
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