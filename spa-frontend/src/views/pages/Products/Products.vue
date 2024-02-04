
<template>
    <v-row>
        <v-col cols="12" md="12">
            <UiParentCard title="Products"> 
                <template v-slot:action>
                    <v-btn @click="addItem()" color="primary">
                        <PencilPlusIcon stroke-width="1"></PencilPlusIcon> Add Product
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
                        <th>Category</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-if="products.length == 0">
                            <td colspan="5" class="text-center">No data found</td>
                        </tr>
                        <tr v-for="(product, index) in products" :key="index" :class="{ 'loading-row': loading }">
                            <td>{{ product.code }}</td>
                            <td>{{ product.name }}</td>
                            <td>
                                <span v-if="product.category != undefined">
                                    {{ product.category.code }} - {{ product.category.name }}
                                </span>
                            </td>
                            <td>{{ product.price }}</td>
                            <td>
                                <div class="d-flex gap-3">
                                    <v-btn @click="editItem(product)" color="primary" icon size="x-small" flat>
                                        <EditIcon stroke-width="1"  />
                                    </v-btn>
                                    <v-btn @click="deleteItem(product)" color="error" icon size="x-small" flat>
                                        <TrashIcon stroke-width="1"  />
                                    </v-btn>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <v-pagination v-model="currentPage" :length="totalPages" v-if="products.length > 0"></v-pagination>
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
            products: [
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

                const response = await axios.get(`${apiUrl}/products?page=${this.currentPage}&search=${this.searchQuery}`, {
                    headers: {
                        Authorization: `Bearer ${bearerToken}`
                    }
                });

                this.products = response.data.products.data;
                this.totalPages = response.data.products.last_page;
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.log(error);
            }
        },
        addItem() {
            router.push(`/products/add`);
        },
        editItem(product) {
            router.push(`/products/${product.id}`);
        }, 
        async deleteItem(product) {
            const confirm = window.confirm("Are you sure to delete this product?");
            if (!confirm) {
                return;
            }

            try {
                const bearerToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const apiUrl = import.meta.env.VITE_API_URL;

                const response = await axios.post(`${apiUrl}/products/${product.id}`, {
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