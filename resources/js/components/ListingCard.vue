<script setup lang="ts">
    import { Listing } from '../types';

    const props = defineProps<{
        listing: Listing
    }>();
</script>

<template>
    <div class="w-full p-3 space-y-2 bg-gray rounded">
        <h2 class="text-4xl"><a :href="props.listing.link">{{ props.listing.title }}</a></h2>
            <hr>
        <h4 class="text-xl">{{ props.listing.salaryRange }}</h4>
            <hr>
        <p v-html="props.listing.description"></p>
    </div>
</template>