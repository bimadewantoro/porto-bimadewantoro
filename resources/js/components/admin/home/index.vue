<script setup>
    import { useRouter } from 'vue-router';

    const router = useRouter();

    const logout = () => {
        localStorage.removeItem('token');
        router.push({ path: '/' })
    }
</script>

<template>
    <h1>Welcome to admin overview website</h1>
    <div>
        <router-link to="/">Home Pages</router-link>
    </div>
    <br>
    <button @click="logout">
        Logout
    </button>
</template>