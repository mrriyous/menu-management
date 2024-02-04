<template>
    <div class="d-flex align-center text-center mb-6">
        <div class="text-h6 w-100 px-5 font-weight-regular auth-divider position-relative">
            <span class="bg-surface px-5 py-3 position-relative text-subtitle-1 text-grey100">Enter your login details</span>
        </div>
    </div>
    <div>
        <v-row class="mb-3">
            <form @submit.prevent="login" style="min-width: 400px;">
                <v-col cols="12">
                    <v-label class="font-weight-medium mb-1">Email</v-label>
                    <v-text-field
                        v-model="email"
                        variant="outlined"
                        class="pwdInput"
                        type="email"
                        color="primary"
                        :error="errorState?.email?.length > 0"
                        :error-messages="errorState?.email?.length > 0 ? errorState?.email[0] : ''">
                    </v-text-field>
                </v-col>
                <v-col cols="12">
                    <v-label class="font-weight-medium mb-1">Password</v-label>
                    <v-text-field
                        v-model="password"
                        variant="outlined"
                        class="border-borderColor"
                        type="password"
                        color="primary"
                        :error="errorState?.password?.length > 0"
                        :error-messages="errorState?.password?.length > 0 ? errorState?.password[0] : ''">
                    </v-text-field>
                </v-col>
                <v-col cols="12 " class="py-0">
                    <div class="d-flex flex-wrap align-center w-100 ">
                        <!-- <v-checkbox hide-details color="primary">
                            <template v-slot:label class="">Remeber this Device</template>
                        </v-checkbox> -->
                        <!-- <div class="ml-sm-auto">
                            <RouterLink to=""
                                class="text-primary text-decoration-none text-body-1 opacity-1 font-weight-medium">
                                Forgot Password ?</RouterLink>
                        </div> -->
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-btn size="large" rounded="pill" color="primary" class="rounded-pill" block type="submit" flat>Sign
                        In</v-btn>
                </v-col>
            </form>
        </v-row>
    </div>
</template>

<script>
import axios from 'axios';
import { storageSetData, storageGetData } from '@/services/storage';

export default {
    data() {
        return {
            email: '',
            password: '',
            errorState: {
                email: [],
                password: []
            }
        };
    },
    methods: {
        checkLoggedInUser() {
            const apiToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
            if (apiToken != null && apiToken != '') {
                this.$router.push('/');
            }
        },
        async login() {
            try {
                const apiUrl = import.meta.env.VITE_API_URL;
                const response = await axios.post(`${apiUrl}/login`, {
                    email: this.email,
                    password: this.password
                });

                storageSetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER, response.data.token);

                this.$router.push('/');
            } catch (error) {
                this.errorState = null;
                if (error.response.status === 422) {
                    this.errorState = error.response.data.errors;
                }
            }
        },
    },
    mounted() {
        this.checkLoggedInUser();
    }
};
</script>