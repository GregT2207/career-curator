<script setup lang="ts"></script>

<template>
    <div class="flex justify-between w-full bg-gray-800 px-6 py-4">
        <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25" />
            </svg>

            <p class="text-md md:text-2xl">Career Curator</p>
        </div>

        <div>
            <div class="flex items-center w-[40vw] max-w-[300px] h-[40px] text-black bg-white rounded-full">
                <input type="search" id="searchBox" class="w-full h-full mx-4 bg-transparent outline-none appearance-none" placeholder="Find your perfect job...">
            </div>
        </div>
    </div>
</template>