<template>
    <v-locale-provider >
        <v-app>
            <MainView />
            <v-main>
                <v-container fluid class="page-wrapper bg-background px-sm-5 px-4  pt-12 rounded-xl">
                    <div class="maxWidth">
                        <RouterView />
                    </div>
                </v-container>
            </v-main>
        </v-app>
    </v-locale-provider>
</template>

<script>
import { RouterView } from 'vue-router';
import MainView from './Main.vue';
import axios from 'axios';
import { router } from '@/router';

export default {
  components: {
    RouterView,
    MainView,
  },
  methods: {
    async checkLoggedInUser() {
        try {
            const bearerToken = localStorage.getItem(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
            const apiUrl = import.meta.env.VITE_API_URL;

            const response = await axios.get(`${apiUrl}/user`, {
                headers: {
                    Authorization: `Bearer ${bearerToken}`
                }
            });

            // Access the user data from the response
            const userData = response.data;

            console.log('User Data:', userData);
        } catch (error) {
            console.log(error);
            if(error.response != undefined) {
                if (error.response.status === 401) {
                    localStorage.removeItem(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                    console.error('Unauthorized access. Redirect to login page or perform logout.');
                    router.push('/login');
                }
            }
        }
    }
  },
  mounted() {
    this.checkLoggedInUser();
  },
};
</script>