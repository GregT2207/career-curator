<script setup lang="ts">
    import { Listing } from '../types';

    const props = defineProps<{
        listing: Listing
    }>();
</script>

<template>
    <div class="w-full p-3 space-y-2 bg-gray rounded">
        <h2 class="text-2xl">{{ props.listing.title }}</h2>
            <hr>
        <h4 class="text-lg">{{ props.listing.salaryRange }}</h4>
            <hr>
        <p>{{ props.listing.description }}</p>
    </div>
</template>