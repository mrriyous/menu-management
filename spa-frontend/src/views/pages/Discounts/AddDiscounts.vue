
<template>
    <v-row>
        <v-col cols="12" md="12">
            <UiParentCard title="Add Discount"> 
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

                            <v-label class="font-weight-medium mb-1">Discount Percentage <sup style="color: red">*</sup></v-label>
                            <v-text-field
                                v-model="discount_percentage"
                                variant="outlined"
                                class="pwdInput"
                                type="number"
                                step="0.1"
                                color="primary"
                                :error="errorState?.discount_percentage?.length > 0"
                                :error-messages="errorState?.discount_percentage?.length > 0 ? errorState?.discount_percentage[0] : ''">
                            </v-text-field>

                            <v-label class="font-weight-medium mb-1">Discount Type <sup style="color: red">*</sup></v-label>
                            <select v-model="discount_type" class="v-select-style">
                                <option value="" disabled>Select Discount Type</option>
                                <option v-for="item in ['All', 'Category', 'Item']" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                        </v-col>

                        <v-col cols="12">
                            <v-select
                                v-if="discount_type == 'Category'"
                                v-model="product_category_id"
                                :items="productCategories"
                                label="Select Category"
                                :error="errorState?.product_category_id?.length > 0"
                                :error-messages="errorState?.product_category_id?.length > 0 ? errorState?.product_category_id[0] : ''"
                                :item-props="categoryProperty"
                                variant="outlined"
                            >
                            </v-select>

                            <v-select
                                v-if="discount_type == 'Item'"
                                v-model="product_id"
                                :items="products"
                                label="Select Item"
                                :error="errorState?.product_id?.length > 0"
                                :error-messages="errorState?.product_id?.length > 0 ? errorState?.product_id[0] : ''"
                                :item-props="productProperty"
                                variant="outlined"
                            >
                            </v-select>

                            <!-- <select v-model="parentCategoryId" class="v-select-style">
                                <option value="" disabled>Select an Item</option>
                                <option v-for="item in items" :key="item.value" :value="item.value">
                                    {{ item.name }}
                                </option>
                            </select> -->
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

<style scoped>
.loading {
    opacity: 0.4;
}

/* Add Vuetify styles to the select element */
.v-select-style {
  width: 100%;
  padding: 8px;
  font-size: 16px;
  border: 1px solid #ced4da;
  border-radius: 4px;
  background-color: #fff;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Add Vuetify styles to the selected option */
.v-select-style option {
  color: #495057;
  background-color: #fff;
}

</style>

<script>
import UiParentCard from '@/components/shared/UiParentCard.vue';
import { router } from '@/router';
import axios from 'axios';

export default {
    data() {
        return {
            loadingData: true,
            errorMessage: '',
            loading: false,
            name: '',
            code: '',
            discount_type: 'All',
            discount_percentage: 0,
            product_category_id: '',
            product_id: '',
            errorState: {
                code: [],
                name: [],
                discount_percentage: [],
                discount_type: [],
                product_category_id: [],
                product_id: []
            },
            productCategories: [],
            products: []
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
                const token = localStorage.getItem(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const response = await axios.post(`${apiUrl}/discounts`, {
                    name: this.name,
                    code: this.code,
                    discount_percentage: this.discount_percentage,
                    product_category_id: this.product_category_id,
                    product_id: this.product_id,
                    discount_type: this.discount_type
                }, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                
                const data = response.data;

                this.loading = false;
                if (data.error) {
                    this.errorMessage = data.error_message;
                    return;
                }

                this.$router.push(`/discounts/${data.discount.id}`);
            } catch (error) {
                this.loading = false;
                this.errorState = null;
                if (error.response != undefined) {
                    if (error.response.status === 422) {
                        this.errorState = error.response.data.errors;
                    }
                }
            }
        },
        goBack() {
            router.push('/discounts');
        },
        categoryProperty(item) {
            return {
                title: item.category_label,
                value: item.id
            };
        },
        productProperty(item) {
            return {
                title: item.name,
                value: item.id
            };
        },
        async setupForm() {
            try {
                const apiUrl = import.meta.env.VITE_API_URL;
                const token = localStorage.getItem(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                // Get category
                const response = await axios.get(`${apiUrl}/categories/get-hierarchically`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                const data = response.data;
                this.productCategories = this.flattenedCategories(data.categories);

                // Get product
                const responseProduct = await axios.get(`${apiUrl}/products?paginate=no`, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
                const dataProduct = responseProduct.data;
                this.products = dataProduct.products;

                this.loadingData = false;
            } catch (error) {
                console.log(error);
                this.loadingData = false;
            }
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
    },
};
</script>