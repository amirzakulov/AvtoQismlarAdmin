<template>
    <ProductsHeader :soldData="soldData" :chartData="chartData" />

    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

        <Form ref="orderProducts"  class="ivu-mb">
            <Row :gutter="8">
                <Col span="4" v-if="loggedUser.store_id == 1">
                    <FormItem label="Do'kon">
                        <Select v-model="data.store_id" placeholder="Tanlash...">
                            <Option :value="0">Barchasi</Option>
                            <Option v-for="store in stores" :value="store.id" :key="store.id">{{ store.name }}</Option>
                        </Select>
                    </FormItem>
                </Col>
                <Col span="6">
                    <FormItem label="Oraliq muddat">
                        <Space size="large">
                            <DatePicker type="daterange"
                            :model-value="data.dates"
                            format="dd.MM.yyyy"
                            @on-change="dateChange"
                            placement="bottom-end"
                            placeholder="Select date"
                            />
                            <Button type="primary" @click="getProducts()">Ko'rsatish</Button>
                        </Space>
                    </FormItem>
                </Col>

            </Row>
        </Form>



        <!-- <Row> -->
            <!-- <Col span="2" class="text-left">
                <span class="ivu-mr">Do'konlar:</span>
            </Col>
            <Col span="4" class="text-left">
                
                <Select v-model="data.store_id" placeholder="Tanlash...">
                    <Option v-for="store in stores" :value="store.id" :key="store.id">{{ store.name }}</Option>
                </Select>
            </Col> -->
            <!-- <Col span="12" class="text-right">
                <span class="ivu-mr">Oraliq muddatni tanlang:</span>
                <Space size="large" class="ivu-mb">
                    <DatePicker type="daterange"
                    :model-value="data.dates"
                    format="dd.MM.yyyy"
                    @on-change="dateChange"
                    placement="bottom-end"
                    placeholder="Select date"
                    style="width: 300px" />
                    <Button type="primary" @click="getProducts()">Ko'rsatish</Button>
                </Space>

            </Col> -->
        <!-- </Row> -->

        <div class="_overflow _table_div">
            <table class="_table">
                <!-- TABLE TITLE -->
                <thead>
                <tr class="blue_thead bordered">
                    <th class="_text_center">Nomi</th>
                    <th class="_text_center">Nomi Rus</th>
                    <th class="_text_center">Kodi</th>
                    <th class="_text_center">Brend</th>
                    <th class="_text_center">Davlat</th>
                    <th class="_text_center">Birligi</th>
                    <th class="_text_center">Soni</th>
                    <th class="_text_center">Sotuv Narx</th>
                    <th class="_text_center">Tan Narx</th>
                    <th class="_text_center">Chegirma</th>
                    <th class="_text_center">Foyda</th>
                </tr>
                </thead>
                <tbody>
                    <template v-if="products.length">
                        <tr v-for="(product, i) in products" :key="i">
                            <td>{{product.name}}</td>
                            <td>{{product.name_ru}}</td>
                            <td>{{product.code}}</td>
                            <td>{{product.brand}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.count}}</td>
                            <td>{{parseFloat(product.sell_price).toFixed(2)}}</td>
                            <td>{{parseFloat(product.price).toFixed(2)}}</td>
                            <td>{{parseFloat(product.discount).toFixed(2)}}</td>
                            <td>{{getBenifit(product.sell_price, product.price, product.discount, product.count)}}</td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>


</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "reports",
    components: {
        ProductsHeader:ProductsHeader
    },
    data(){
        return {
            data: {
                store_id:   0,
                dates:      [],
                // filterDates:[],
            },
            
            // data: {
            //      store_id: 0,
            //      datePeriod: []
            // },
            soldData:   [],
            chartData:  [],
            products:   [],
            stores:     [],
            loggedUser: {},

        }
    },
    methods : {
        getCurrentMonthDateRage(){
            var date    = new Date();
            var firstDay= new Date(date.getFullYear(), date.getMonth(), 1);
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

            this.data.dates = [firstDay,lastDay]
            // this.data.filterDates = [firstDay,lastDay]
        },
        dateChange(date){
            this.data.dates = date
            // this.data.filterDates = date

        },
        async getProducts(){
            console.log(this.data);

            const products = await this.callApi('post', '/app/get_products_report', this.data)

            this.products = products.data
            this.getSoldData(this.products)

        },
        getBenifit(sell_price, price, discount, count){

            sell_price  = parseFloat(sell_price)
            price       = parseFloat(price)
            discount    = parseFloat(discount)

            return parseFloat((sell_price - (price + discount)) * count).toFixed(2)

        },
        getSoldData(products){
            let sell_price  = 0
            let price       = 0
            let discount    = 0
            products.forEach((product, index) => {
                sell_price  += product.sell_price * product.count
                price       += product.price * product.count
                discount    += product.discount * product.count

            })

            this.soldData[0] = parseFloat(sell_price).toFixed(2)
            this.soldData[1] = parseFloat(price).toFixed(2)
            this.soldData[2] = parseFloat(discount).toFixed(2)
            this.soldData[3] = parseFloat(sell_price - (price + discount)).toFixed(2)

            this.chartData[0] = this.soldData[3]
            this.chartData[1] = this.soldData[1]
            this.chartData[2] = this.soldData[2]

        }

    },

    async created(){
        this.token = window.Laravel.csrfToken
        
        const loggedUser = await this.callApi('get', '/app/logged_user')
        this.loggedUser = loggedUser.data

        if(this.loggedUser.store_id != 1){
            this.data.store_id = this.loggedUser.store_id
        }

        this.getCurrentMonthDateRage()
        // let data = {dates : this.data.filterDates}

        const products = await this.callApi('post', '/app/get_products_report', this.data)
        if(products.status == 200) {
            this.products = products.data
            this.getSoldData(this.products)

        } else if(products.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }

        const stores = await this.callApi('get', '/app/get_stores')
        this.stores = stores.data

        
    },
}
</script>
