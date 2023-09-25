<template>

    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

        <Row border="bottom">
            <Col span="3">
                <Button :to="{name: 'reportClients'}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Orqaga qaytish </Button>
            </Col>

        </Row>
        <Divider />

        <div><Icon type="md-person" />: {{client.fullName}}</div>
        <div><Icon type="md-call" />: {{client.phone}}</div>
        <br />

        <div class="_overflow _table_div" v-if="Object.keys(this.orderProducts).length">
            <template v-for="(products, o) in orderProducts" :key="o">
                <strong class="text-danger">{{myDateFormat(products[0].order_date, "MM.DD.YYYY hh:mm")}}</strong>
                <table class="_table mb-4">
                    <thead>
                    <tr class="blue_thead bordered">
                        <!-- <th width="20px" class="_text_center"></th> -->
                        <th class="_text_center align-bottom" style="min-width: 200px">Maxsulot nomi</th>
                        <th class="_text_center align-bottom">Kod</th>
                        <th class="_text_center align-bottom">Davlat</th>
                        <th class="_text_center align-bottom">Brend</th>
                        <th class="_text_center align-bottom">Birligi</th>
                        <th class="_text_center align-bottom">Miqdor</th>
                        <th class="_text_center">Narx</th>
                        <th class="_text_center">Chegirma</th>
                        <th class="_text_center">Jami (USD)</th>
                        <th class="_text_center">Jami (UZS)</th>
                        <th class="_text_center">Kurs (so'm)</th>
                        <th class="_text_center align-bottom">Sana</th>
                        <th class="text-right align-bottom">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(product, i) in products" :key="i">
                            <!-- <td width="20px">
                                <Image v-if="product.picture" :src="`/uploads/${product.picture}`" fit="contain" width="50px" height="50px" />
                                <Icon type="ios-image" size="24" v-else />
                            </td> -->
                            <td><strong class="text-primary">{{product.name}}</strong></td>
                            <td>{{product.code}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.brand}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.count == null ? 0:product.count}}</td>
                            <td><div>${{product.price}} / {{roundPriceUp(product.price * product.dollar_rate)}}</div></td>
                            <td><div>${{parseFloat(product.discount).toFixed(2)}}</div></td>
                            <td><div>${{parseFloat((product.price * product.count) - product.discount).toFixed(2)}}</div></td>
                            <td><div>{{(roundPriceUp(product.price * product.dollar_rate) - roundPriceUp(product.discount * product.dollar_rate)) * product.count}}</div></td>
                            <td><div>{{product.dollar_rate}}</div></td>
                            <td>{{myDateFormat(product.order_date, "MM.DD.YYYY hh:mm")}}</td>
                            <td class="text-right text-danger">{{orderStatus(product.status)}}</td>
                        </tr>
                    </tbody>
                    <tfoot>
                    <tr class="bg-secondary bordered">
                        <!-- <th width="20px" class="_text_center align-top"></th> -->
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>$ {{usdTotal(products)}}</th>
                        <th>{{uzsTotal(products)}} so'm</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </tfoot>
                </table>
            </template>
        </div>
    </div>


</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "Client Orders",
    computed: {    },
    data(){
        return {
            client_id: this.$route.params.client_id,
            orderProducts: [],
            total: 0,
            client: {}
        }
    },
    methods : {
        usdTotal(products){
            let total = 0;
            products.forEach((item, i) => {
                total += (item.price * item.count) - item.discount;
            });

            return parseFloat(total).toFixed(2);
        },
        uzsTotal(products){
            let total = 0;
            products.forEach((item, i) => {
                total += (this.roundPriceUp(item.price * item.dollar_rate) - this.roundPriceUp(item.discount * item.dollar_rate)) * item.count;
            });

            return total;
        },
        orderStatus(n){
            if(n == 0) {return "Yangi"}
            else if(n == 1) {return "Yo'lda"}
            else { return "Bajarilgan"}
        }
    },

    async created(){

        const client = await this.callApi("get", "/app/get_client/"+this.client_id)
        this.client = client.data
        const orderProducts = await this.callApi('get', '/app/get_client_orders/'+this.client_id)

        if(orderProducts.status == 200) {
            this.orderProducts = orderProducts.data

        } else if(orderProducts.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }

    },
}
</script>
