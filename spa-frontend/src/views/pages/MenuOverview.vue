<template>
    <v-row>
        <v-col cols="12" sm="12" lg="12" v-if="categories.length == 0">
            <v-card elevation="10" class="withbg mb-4">
                <v-card-item>
                    <h6 class="text-h6">Please setup your menu</h6>
                </v-card-item>
            </v-card>
        </v-col>
        <v-col cols="12" sm="12" lg="12" v-for="category in categories">
            <v-card elevation="10" class="withbg mb-4">
                <v-card-item>
                    <div class="d-inline-flex mb-5 category-parent-container" v-if="category.all_parents.length > 0">
                        <div class="category-parent-label d-inline-flex" v-for="(parent, index) in category.all_parents">
                            <ChevronRightIcon style="margin-left: -4px;" stroke-width="1.5" size="15" v-if="index > 0" str></ChevronRightIcon> {{ parent.name }}
                        </div>
                    </div>

                    <h6 class="text-h6" v-text="category.name"></h6>
                </v-card-item>
            </v-card>
            <v-row>
                <v-col cols="12" lg="3" sm="6" v-for="product in category.products" :key="product.title">
                    <v-card elevation="10" class="withbg">
                        <v-card-item>
                            <h6 class="text-h6" v-text="product.name"></h6>
                            <div class="d-flex align-center justify-space-between mt-1">
                                <div>
                                    <span class="text-h6" v-text="'$'+ product.price"></span><br/>
                                    <span class="text-discount" v-if="product.promotion != undefined">Discount : {{ product.promotion.discount_percentage }}%</span>
                                </div>
                            </div>
                            <div class="promotion-info mt-2" v-if="product.promotion != undefined">
                                Promotion : {{ product.promotion.code }} - {{ product.promotion.name }} <br/>
                                Promotion Type : {{ product.promotion.discount_type }} <br/>
                            </div>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<style scoped>
.v-card-item {
    padding: 20px 30px;
}
.promotion-info {
    background-color: rgb(228, 57, 57);
    color: #fff;
    font-size: 12px;
    padding: 5px;
    border-radius: 5px;
}
.category-parent-container {
    background-color: rgb(137, 33, 94);
    border-radius: 5px;
    width: auto;
    margin-right: auto;
}
.category-parent-label {
    align-items: center;
    padding: 5px;
    color: #fff;
    border-radius: 5px;
    font-size: 12px;
}
.text-discount {
    font-size: 12px;
    font-style: italic;
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
            items: [
                { text: 'Home' },
                { text: 'Categories' },
                { text: 'Subcategory' },
                { text: 'Current Page'},
            ],
            products: [
                {
                    title: '09:30 am',
                    subtitle: 'Payment received from John Doe of $385.90',
                    textcolor: 'primary',
                    boldtext: false,
                    line: true,
                    link: '',
                    url: ''
                },
            ],
            categories: []
        };
    },
    methods: {
        async getData() {
            try {
                const bearerToken = storageGetData(import.meta.env.VITE_API_TOKEN_IDENTIFIER);
                const apiUrl = import.meta.env.VITE_API_URL;

                const response = await axios.get(`${apiUrl}/menu-overview`, {
                    headers: {
                        Authorization: `Bearer ${bearerToken}`
                    }
                });

                const data = response.data;

                this.categories = this.fillParentsProperty(data.categories).filter((category) => {
                    return category.products.length > 0;
                });
                this.loading = false;
            } catch (error) {
                this.loading = false;
                console.log(error);
            }
        },
        fillParentsProperty(categories) {
            for (const category of categories) {
                category.all_parents = this.getCategoryParents(category);
            }

            return categories;
        },
        getCategoryParents(category, parents = []) {
            if (category.parent == undefined) {
                return parents;
            }

            parents.unshift(category.parent);

            return this.getCategoryParents(category.parent, parents)
        }
    },
    watch: {
    },
    components: {
        UiParentCard,
    },
    mounted() {
        this.getData();
    },
};
</script>