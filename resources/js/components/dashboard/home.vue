<template>
            <div class="row d-none">
                <div class="col-md-3 col-md-3">
                    <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one">
                        <div class="_1adminOverveiw_card_left">
                            <p class="_1adminOverveiw_card_left_num">3</p>

                            <p class="_1adminOverveiw_card_left_title">Xomashyo Eslatma</p>
                        </div>
                        <div class="_1adminOverveiw_card_right">
                            <Icon type="ios-paper" />
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-md-3">
                    <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                        <div class="_1adminOverveiw_card_left">
                            <p class="_1adminOverveiw_card_left_num">29</p>

                            <p class="_1adminOverveiw_card_left_title">Total News</p>
                        </div>
                        <div class="_1adminOverveiw_card_right">
                            <Icon type="ios-paper-outline" />
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-md-3">
                    <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                        <div class="_1adminOverveiw_card_left">
                            <p class="_1adminOverveiw_card_left_num">29</p>

                            <p class="_1adminOverveiw_card_left_title">Features News</p>
                        </div>
                        <div class="_1adminOverveiw_card_right">
                            <Icon type="md-copy" />
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-md-3">
                    <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                        <div class="_1adminOverveiw_card_left">
                            <p class="_1adminOverveiw_card_left_num">29</p>

                            <p class="_1adminOverveiw_card_left_title">Card News</p>
                        </div>
                        <div class="_1adminOverveiw_card_right">
                            <Icon type="md-list-box" />
                        </div>
                    </div>
                </div>
            </div>
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <Row border="bottom">
                    <Col span="21">
                        <p class="_title0">Buyurtmalar</p>
                    </Col>
                    <Col span="3" class="text-right">
                        <Button :to="{name: 'orders', params: {status: 0}}" type="success" class="text-white">Barchasini ko'rish <Icon type="ios-arrow-forward" /></Button>
                    </Col>
                </Row>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                            <tr class="blue_thead bordered">
                            <th class="_text_center align-top">Buyurtma raqami</th>
                            <th class="_text_center" style="min-width: 300px">Buyurtmachi</th>
                            <th class="text-right">Telefon</th>
                            <th class="text-right">Manzil</th>
                            <th class="text-right">Jami</th>
                            <th class="text-right">Jami So'm</th>
                            <th class="text-right">Kurs</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="orders.length">
                                <tr v-for="(order, o) in orders" :key="o" >
                                    <td>{{order.oid}}</td>
                                    <td>{{order.fullName}}</td>
                                    <td class="text-right">{{order.phone}}</td>
                                    <td class="text-right">{{order.address}}</td>
                                    <td class="text-right">${{order.total}}</td>
                                    <td class="text-right">{{roundPriceUp(order.totalUzb)}}</td>
                                    <td class="text-right">{{order.dollarRate}} so'm</td>
                                </tr>
                            </template>

                        </tbody>
                    </table>
                </div>
            </div>
</template>

<script>
export default {
    name: "home",
    data(){
        return {
            orderStatus: 0,
            orders: [],
            loggedUser: {},
        }
    },
    async created(){

        const loggedUser= await this.callApi('get', '/app/logged_user')
        this.loggedUser = loggedUser.data
        const orders    = await this.callApi('get', '/app/get_orders/' + this.loggedUser.store_id + '/' + this.orderStatus)

        if(orders.status == 200) {
            this.orders = orders.data

        } else {
            this.swr()
        }

    },
}
</script>

