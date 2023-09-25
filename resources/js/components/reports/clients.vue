<template>
    <ProductsHeader :soldData="soldData" :chartData="chartData" />

    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

        <div class="_overflow _table_div">
            <table class="_table">
                <!-- TABLE TITLE -->
                <thead>
                <tr class="blue_thead bordered">
                    <th class="_text_center">Ism</th>
                    <th class="_text_center">Telefon</th>
                    <th class="_text_center">Manzil</th>
                    <th class="_text_center">Jami</th>
                    <!-- <th class="_text_center">Status</th> -->
                    <th class="_text_center">action</th>
                </tr>
                </thead>
                <tbody>
                    <template v-if="clients.length">
                        <tr v-for="(client, i) in clients" :key="i">
                            <td>{{client.fullName}}</td>
                            <td>{{client.phone}}</td>
                            <td>{{client.address}}</td>
                            <td>$ {{parseFloat(client.total).toFixed(2)}}</td>
                            <!-- <td>Oddiy</td> -->
                            <td class="text-right">
                                <ButtonGroup>
                                    <Button type="primary" title="Ko'rish" class="text-white" size="small" :to="{name:'reportClientOrders', params: {client_id:client.client_id}}"><Icon type="md-eye"></Icon></Button>
                                </ButtonGroup>
                            </td>
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
            soldData: [],
            chartData: [],
            clients: []

        }
    },
    methods : {

    },

    async created(){
        // this.token = window.Laravel.csrfToken
        const clients = await this.callApi('post', '/app/get_clients_report')

        if(clients.status == 200) {
            this.clients = clients.data

        } else if(clients.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }

    },
}
</script>
