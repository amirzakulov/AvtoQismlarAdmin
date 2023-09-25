<template>
    <div class="row pl-2 pr-4 ml-0 mr-0 _mar_b30"
    v-if="soldData.length">
        <div class="col-md-4">
            <Card style="width:100%">
                <Row><Col span="12">Sotildi:</Col><Col span="12" class="text-right">${{soldData[0]}}</Col></Row>
                <Row><Col span="12">Tani:</Col><Col span="12" class="text-right">${{soldData[1]}}</Col></Row>
                <Row class="text-danger"><Col span="12">Chegirma:</Col><Col span="12" class="text-right">${{soldData[2]}}</Col></Row>
                <Divider />
                <Row class="font-weight-bold text-info"><Col span="12">Foyda:</Col><Col span="12" class="text-right">${{soldData[3]}}</Col></Row>
            </Card>
        </div>
        <div class="col-md-4 offset-md-4">
            <div>
                <PieChart :pie-chart-data="chartData" />
            </div>
        </div>

        <div class="col-md-3 col-md-3 d-none">
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

        <div class="col-md-3 col-md-3 d-none">
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
            <router-link :to="{ name: 'reports' }" class="nav-link">Maxsulotlar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'reportClients'}" class="nav-link">Klientlar</router-link>
        </li>
    </ul>

</template>

<script>
import BarChart from './barChart.vue'
import PieChart from './pieChart.vue'
export default {
    name: "reports_header",
    components: {
        BarChart:BarChart,
        PieChart:PieChart,
    },
    computed: {

    },
    props: ['soldData', 'chartData'],
    data() {
        return {
            activeCategory: 0,
            productsCount: 0,
            brandsCount: 0,
            countriesCount: 0,
            categoriesCount: 0,
            benifit: 0,
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

