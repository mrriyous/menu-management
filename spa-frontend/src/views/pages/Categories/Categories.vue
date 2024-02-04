
<template>
    <v-row>
        <v-col cols="12" md="12">
            <UiParentCard title="Categories"> 
                <template v-slot:action>
                    <v-btn @click="addItem()" color="primary">
                        <PencilPlusIcon stroke-width="1"></PencilPlusIcon> Add Category
                    </v-btn>
                </template>

                <v-alert class="mb-3" type="error" v-if="errorMessage != undefined && errorMessage != ''">{{ errorMessage }}</v-alert>
                <v-alert class="mb-3" type="success" v-if="successMessage != undefined && successMessage != ''">{{ successMessage }}</v-alert>

                <div class="d-flex justify-space-between">
                    <v-text-field class="table-search-field" v-model="searchQuery" label="Search" outlined @keyup="currentPage = 1;_debounceGetData()"></v-text-field>
                </div>
                <table class="styled-table mb-5">
                    <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-if="categories.length == 0">
                            <td colspan="4" class="text-center">No data found</td>
                        </tr>
                        <tr v-for="(category, index) in categories" :key="index" :class="{ 'loading-row': loading }">
                            <td>{{ category.code }}</td>
                            <td>{{ category.name }}</td>
                            <td>
                                <span v-if="category.parent != undefined">
                                    {{ category.parent.code }} - {{ category.parent.name }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-3">
                                    <v-btn @click="editItem(category)" color="primary" icon size="x-small" flat>
                                        <EditIcon stroke-width="1"  />
                                    </v-btn>
                                    <v-btn @click="deleteItem(category)" color="error" icon size="x-small" flat>
                                        <TrashIcon stroke-width="1"  />
                                    </v-btn>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <v-pagination v-model="currentPage" :length="totalPages" v-if="categories.length > 0"></v-pagination>
            </UiParentCard>
        </v-col>
    </v-row>
</template>

<style>
.styled-table tr {
    transition: all .4s;
}
.loading-row {
    opacity: 0.5;
}
</style>

<script>
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { router } from '@/router';
import { storageGetData } from '@/services/storage';
import axios from 'axios';

export default {
    data() {
        return {
            errorMessage: '',
            successMessage: '',
            loading: false,
            currentPage: 1,
            totalPages: 1,
            searchQuery: '',
            categories: [
            ],
            _debounceState: 0
        };
    },
    methods: {
        handlePaginationChange(newPage) {
            this._debounceGetData();
        },
        _debounceGetData() {
            clearTimeout(this._debounceState);
            this.loading = true;
            this._debounceState = setTimeout(() => {
                this.getData();
            }, 500);
        },
        async getData() {
            try {
                const bearerToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const apiUrl = import.meta.env.VITE_API_URL;

                const response = await axios.get(`${apiUrl}/categories?page=${this.currentPage}&search=${this.searchQuery}`, {
                    headers: {
                        Authorization: `Bearer ${bearerToken}`
                    }
                });

                this.categories = response.data.categories.data;
                this.totalPages = response.data.categories.last_page;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.log(error);
            }
        },
        addItem() {
            router.push(`/categories/add`);
        },
        editItem(category) {
            router.push(`/categories/${category.id}`);
        }, 
        async deleteItem(category) {
            const confirm = window.confirm("Are you sure to delete this category?");
            if (!confirm) {
                return;
            }

            try {
                const bearerToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const apiUrl = import.meta.env.VITE_API_URL;

                const response = await axios.post(`${apiUrl}/categories/${category.id}`, {
                    _method: "DELETE"
                }, {
                    headers: {
                        Authorization: `Bearer ${bearerToken}`
                    }
                });
                const data = response.data;

                if (data.error) {
                    this.errorMessage = data.error_message;
                    return;
                }

                this.successMessage = data.message;
                this._debounceGetData();
                this.resetMessages();
            } catch (error) {
                this.loading = false;
                this.errorMessage = 'Something went wrong, please try again later!'
            }
        },
        resetMessages() {
            setTimeout(() => {
                this.successMessage = '';
                this.errorMessage = '';
            }, 2500);
        }
    },
    watch: {
        currentPage(newPage) {
            this.handlePaginationChange(newPage);
        },
    },
    components: {
        UiParentCard
    },
    mounted() {
        this.getData();
    },
};
</script>