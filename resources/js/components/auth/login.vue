<script setup>
    import { reactive, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import axios from 'axios';

    const router = useRouter();
    
    const data = reactive({
        email: '',
        password: ''
    });

    const error = ref('');

    const login = async() => {
        try {
            const response = await axios.post('/api/login', data)
            localStorage.setItem('token', response.data.access_token);
            router.push({ path: '/admin/home' })
            console.log(response)
        } catch (err) {
            error.value = err.response.data.message;
        }
    }
</script>

<template>
    <div class="login">
        <div class="formLogin">
            <form @submit.prevent="login">
                <input type="text" name="email" placeholder="Enter your Email" v-model="data.email">
                <br>
                <input type="password" name="password" placeholder="Enter your Password" v-model="data.password">
                <br>
                <input type="submit" value="Login" class="submit">
            </form>
            <p class="text-danger" v-if="error">{{ error }}</p>
        </div>
    </div>
</template>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Fire Sans', sans-serif;
        font-size: 16px;
    }

    .login {
        background: #ffffff;
        padding: 0;
        margin: 0;
        width: 100%;
        height: 100vh;
    }

    ::placeholder {
        color: #2b2626;
        font-size: 1em;
    }

    .formLogin {
        display: flex;
        align-items: center;
        width: 22em;
        height: 55em;
        margin: 1em auto 0 auto;
        overflow: hidden;
    }

    input {
        width: 200%;
        height: 3em;
        margin: 1em 0;
        padding: 0 1em;
        border: none;
        border-radius: 5px;
        background: #ded9ee;
        color : #000000;
        font-size: 1em;
    }

    input:hover {
        background-color: #8e75e4;
    }

    input:focus {
        outline: 1px solid #ffffff;
    }
    
    .submit {
        width: 100%;
        height: 3em;
        margin: 1.5em 0 0 4.5em;
        padding: 0;
        border: none;
        border-radius: 5px;
        color: #ded9ee;
        background: #7464bc;
        font-size: 1em;
        text-transform: uppercase;
    }
    .text-danger{
        color: red;
        font-size: 16px;
        position: absolute;
        top: 50%;
    }
</style>