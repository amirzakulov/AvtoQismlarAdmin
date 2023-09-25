<template>
    <div class="row pl-2 pr-4 ml-0 mr-0">
        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">{{productsCount}}</p>

                    <p class="_1adminOverveiw_card_left_title">Maxsulotlar turlari</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="ios-construct" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">{{categoriesCount}}</p>
                    <p class="_1adminOverveiw_card_left_title">Kategoriyalar turlari</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="ios-car" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">{{brandsCount}}</p>

                    <p class="_1adminOverveiw_card_left_title">Brendlar</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="logo-bitcoin" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">{{countriesCount}}</p>

                    <p class="_1adminOverveiw_card_left_title">Davlatlar</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="ios-plane" color="#ff0000" />
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs ml-4">
        <li class="nav-item">
            <router-link :to="{ name: 'products' }" class="nav-link">Maxsulotlar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'categories'}" class="nav-link">Kategoriyalar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'brands'}" class="nav-link">Brendlar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'countries' }" class="nav-link">Davlatlar</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    name: "rmstock_header",
    data() {
        return {
            activeCategory: 0,
            productsCount: 0,
            brandsCount: 0,
            countriesCount: 0,
            categoriesCount: 0,
        }
    },
    methods: {

    },
    async created() {
        const products = await this.callApi("get", "/app/get_products")
        this.productsCount = products.data.length

        const categories = await this.callApi("get", "/app/get_categories")
        this.categoriesCount = categories.data.length

        const brands = await this.callApi("get", "/app/get_brands")
        this.brandsCount = brands.data.length

        const countries = await this.callApi("get", "/app/get_countries")
        this.countriesCount = countries.data.length

    }

}
</script>

