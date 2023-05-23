<script setup lang="ts">
    import { Listing } from '../types';

    const props = defineProps<{
        listing: Listing
    }>();
</script>

<template>
    <div class="w-full p-3 space-y-2 bg-gray rounded">
        <h2 class="text-3xl">
            <a :href="props.listing.link">
                <span>{{ props.listing.title }}</span>
                <span class="text-lg"> ({{ props.listing.site }})</span>
            </a>
        </h2>
            <hr>
        <h4 class="text-xl">{{ props.listing.salaryRange }}</h4>
            <hr>
        <p v-html="props.listing.description"></p>
    </div>
</template>