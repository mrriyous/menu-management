
<template>
    <v-row>
        <v-col cols="12" md="12">
            <UiParentCard :title="'Editing : '+(category?.name ?? '...')"> 
                <template v-slot:action>
                    <v-btn @click="goBack()" color="error">Go Back</v-btn>
                </template>

                <v-container v-if="loadingData">
                    <v-progress-circular
                        indeterminate
                        size="64"
                        color="primary"
                    ></v-progress-circular>
                </v-container>

                <v-row class="mb-3" v-if="!loadingData">
                    <v-alert class="mb-3" type="error" v-if="errorMessage != undefined && errorMessage != ''">{{ errorMessage }}</v-alert>
                    <v-alert class="mb-3" type="success" v-if="successMessage != undefined && successMessage != ''">{{ successMessage }}</v-alert>

                    <form @submit.prevent="submit" style="width: 100%">
                        <v-col cols="12">
                            <v-label class="font-weight-medium mb-1">Code <sup style="color: red">*</sup></v-label>
                            <v-text-field
                                v-model="code"
                                variant="outlined"
                                class="pwdInput"
                                type="text"
                                color="primary"
                                :error="errorState?.code?.length > 0"
                                :error-messages="errorState?.code?.length > 0 ? errorState?.code[0] : ''">
                            </v-text-field>


                            <v-label class="font-weight-medium mb-1">Name <sup style="color: red">*</sup></v-label>
                            <v-text-field
                                v-model="name"
                                variant="outlined"
                                class="pwdInput"
                                type="text"
                                color="primary"
                                :error="errorState?.name?.length > 0"
                                :error-messages="errorState?.name?.length > 0 ? errorState?.name[0] : ''">
                            </v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-select
                                v-model="parent_category_id"
                                :items="parentCategories"
                                label="Select Parent Category"
                                :error="errorState?.parent_category_id?.length > 0"
                                :error-messages="errorState?.parent_category_id?.length > 0 ? errorState?.parent_category_id[0] : ''"
                                :item-props="categoryProperty"
                                variant="outlined"
                            >
                            </v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-btn size="large" rounded="pill" color="primary" class="rounded-pill" block type="submit" :class="{ 'loading': loading }" flat>Submit</v-btn>
                        </v-col>
                    </form>
                </v-row>
            </UiParentCard>
        </v-col>
    </v-row>
</template>

<style>
.loading {
    opacity: 0.4;
}
</style>

<script>
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { router } from '@/router';
import { storageGetData } from '@/services/storage';
import axios from 'axios';

export default {
    props: ['id'],
    data() {
        return {
            loadingData: true,
            errorMessage: '',
            successMessage: '',
            loading: false,
            category: {},
            name: '',
            code: '',
            parent_category_id: '',
            errorState: {
                code: [],
                name: [],
                parent_category_id: []
            },
            parentCategories: [
            ]
        };
    },
    methods: {
        async submit() {
            if (this.loading) {
                return false;
            }

            this.errorMessage = '';
            this.loading = false;

            try {
                const apiUrl = import.meta.env.VITE_API_URL;
                const token = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const response = await axios.post(`${apiUrl}/categories/${this.category.id}`, {
                    name: this.name,
                    code: this.code,
                    parent_category_id: this.parent_category_id,
                    _method: "PUT"
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });

                this.loading = false;
                if (response.data.error) {
                    this.errorMessage = response.data.error_message;
                    return;
                }

                this.successMessage = response.data.message;
                setTimeout(() => {
                    this.successMessage = '';
                }, 2000);
            } catch (error) {
                this.loading = false;
                this.errorState = null;
                if (error.response.status === 422) {
                    this.errorState = error.response.data.errors;
                }
            }
        },
        async setupForm() {
            try {
                const apiUrl = import.meta.env.VITE_API_URL;
                const token = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const response = await axios.get(`${apiUrl}/categories/get-hierarchically?category_id=${this.$route.params.id}&max_level=3`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                
                const data = response.data;
                this.parentCategories = this.flattenedCategories(data.categories);
                console.log(this.parentCategories, 'testing');
                this.loadingData = false;
            } catch (error) {
                this.loadingData = false;
            }
        },
        async getCategoryData() {
            try {
                const bearerToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const apiUrl = import.meta.env.VITE_API_URL;

                const response = await axios.get(`${apiUrl}/categories/${this.$route.params.id}`, {
                    headers: {
                        Authorization: `Bearer ${bearerToken}`
                    }
                });

                const category = response.data.category;

                this.category = category;
                this.name = category.name;
                this.code = category.code;
                this.parent_category_id = category.parent_category_id;
                this.loadingData = false;
            } catch (error) {
                console.log(error);
                if(error.response != undefined) {
                    router.push('/categories');
                }
            }
        },
        goBack() {
            router.push('/categories');
        },
        categoryProperty(item) {
            return {
                title: item.category_label,
                value: item.id
            };
        },
        flattenedCategories(categories, level = 0) {
            let flattenedCategories = [];

            for (const category of categories) {
                category.level = level;
                let prefix = "";
                for(let i = 0; i < category.level; i++) {
                    prefix += "-";
                }

                category.category_label = prefix + " " + category.name + " (" + category.code + ")";
                flattenedCategories.push(category);

                if (category.children && category.children.length > 0) {
                    flattenedCategories = flattenedCategories.concat(this.flattenedCategories(category.children, level + 1));
                }
            }

            return flattenedCategories;
        }
    },
    components: {
        UiParentCard
    },
    mounted() {
        this.setupForm();
        this.getCategoryData();
    },
};
</script>