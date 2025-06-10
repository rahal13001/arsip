<script setup lang="ts">
// Define any props passed from Laravel here
defineProps<{
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <div>
        <h1>Welcome to Inertia!</h1>
        <p>You are using Laravel v{{ laravelVersion }} and PHP v{{ phpVersion }}</p>
    </div>
</template>

<style scoped>
/* Your component styles here */
</style>
