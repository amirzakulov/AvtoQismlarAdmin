<template>
    <OrdersHeader :store-id="loggedUser.store_id" :order-status="orderStatus" />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <!-- <p class="_title0" v-if="!orderStatus"> -->
                    <!-- <Button type="primary" :to="{ name: 'orderAdd'}" class="text-white"><Icon type="md-add-circle" class="mr-1" />Buyurtma q'shish</Button> -->
                <!-- </p> -->

                <Row :gutter="8" border="bottom" class="mb-3">
                    <Col span="4">
                        <Button v-if="orderStatus == ''" type="primary" :to="{ name: 'orderAdd'}" class="text-white"><Icon type="md-add-circle" class="mr-1" />Buyurtma q'shish</Button>
                    </Col>
                    <Col span="12"></Col>
                    <Col span="4">
                        <Input v-model="keywordOID" placeholder="Buyurtma raqami..." />
                    </Col>
                    <Col span="4">
                        <Input v-model="keywordPhone" placeholder="Telefon raqami..." />
                    </Col>
                </Row>

                <div class="_overflow _table_div">
                    <Table size="small" :row-class-name="rowClassName" :columns="columns" :data="filteredOrders" :loading="loading"></Table>


                    <!-- <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th class="_text_center align-top">Buyurtma raqami</th>
                            <th class="_text_center" style="min-width: 300px">Buyurtmachi</th>
                            <th class="text-right">Telefon</th>
                            <th class="text-right">Manzil</th>
                            <th class="text-right">Jami</th>
                            <th class="text-right">Jami So'm</th>
                            <th class="text-right">Kurs</th>
                            <th class="text-right">Do'kon</th>
                            <th class="text-right">Sana</th>
                            <th>action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="orders.length">
                                <tr v-for="(order, o) in orders" :key="o" :class="{'bg-warning': loggedUser.store_id != order.store_id}">
                                    <td>{{order.oid}}</td>
                                    <td>{{order.fullName}}</td>
                                    <td class="text-right">{{order.phone}}</td>
                                    <td class="text-right">{{order.address}}</td>
                                    <td class="text-right">${{order.total}}</td>
                                    <td class="text-right">{{roundPriceUp(order.totalUzb)}}</td>
                                    <td class="text-right">{{order.dollarRate}} so'm</td>
                                    <td class="text-right">{{order.store_name }}</td>
                                    <td class="text-right">{{myDateFormat(order.created_at, "MM.DD.YYYY hh:mm") }}</td>
                                    <td class="text-right">
                                        <ButtonGroup>
                                            <Button size="small" type="primary" title="Ko'rish" class="text-white" :to="{name:'orderDetails', params: {order_id:order.id}}"><Icon type="md-eye"></Icon></Button>
                                            <Button size="small" type="success" title="Taxrirlash" @click="showEditModal(order, o)"><Icon type="md-person"></Icon></Button>
                                            <Button size="small" type="error"   title="O'chirish" :disabled="orderStatus != 0 || loggedUser.store_id != order.store_id" @click="showDeletingModal(order, o)" :loading="order.isDeleting"><Icon type="md-close"></Icon></Button>
                                        </ButtonGroup>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr><td colspan="10">Buyurtma yo'q</td></tr>
                            </template>
                        </tbody>
                    </table> -->
                </div>
            </div>

    <Modal
        v-model="editModal"
        title="Xaridorning ma'lumovlarini taxrirlash"
        :mask-closable="false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="editClientData.id">
            <FormItem label="* Xaridorning ismi" class="ivu-mb">
                <Input v-model="editClientData.fullName" prefix="md-person" placeholder="Xaridorning ismi"></Input>
                <Text :class="{ 'd-none': !client_name_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="* Xaridorning telefon raqami" class="ivu-mb">
                <Input v-model="editClientData.phone" prefix="md-call" placeholder="Xaridorning telefon raqami"></Input>
                <Text :class="{ 'd-none': !client_phone_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="* Xaridorning manzili" class="ivu-mb">
                <Input v-model="editClientData.address" prefix="md-pin" placeholder="Xaridorning manzili"></Input>
                <Text :class="{ 'd-none': !client_address_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editClient" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Xaridor...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Delete tag modal -->
    <Modal v-model="showDeleteModal" width="360">
        <template #header>
            <p style="color:#f60; text-align:center">
                <Icon type="ios-close-circle" size="20" />
                <span>O'chirishni tasdiqlash?</span>
            </p>
        </template>
        <div style="text-align:center">
            <p>Xaqiqatdan ham o'chirasizmi?</p>
        </div>
        <template #footer>
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteClient">ХА</Button>
        </template>
    </Modal>

</template>

<script>
import OrdersHeader from './parts/header.vue'
import { resolveComponent } from 'vue';
export default {
    name: "orders",
    components: {
        OrdersHeader:OrdersHeader
    },
    computed: {
        filteredOrders(){
            let ordersTmp = this.orders

            if (this.keywordOID != '' && this.keywordOID) {
                ordersTmp = ordersTmp.filter((order) => {
                return order.oid.toLowerCase().includes(this.keywordOID.toLowerCase())
                })
            }

            if (this.keywordPhone != '' && this.keywordPhone) {
                ordersTmp = ordersTmp.filter((order) => {
                return order.phone.toLowerCase().includes(this.keywordPhone.toLowerCase())
                })
            }

            return ordersTmp

        }
    },
    data(){
        return {
            loading: true,
            addModal : false,
            editModal : false,
            isEditing: false,
            isAdding : false,
            editClientData: {
                id: 0,
                fullName: '',
                phone: '',
                address: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            order: {},
            deletingIndex: -1,
            orders: [],

            client_name_err: false,
            client_phone_err: false,
            client_address_err: false,
            is_invalid: false,
            orderStatus: '',
            token: '',
            loggedUser: {},
            columns: [
                {
                    title: "Buyurtma",
                    key: 'oid'
                },
                {
                    title: "Buyurtmachi",
                    key: 'fullName',
                    width: "180"
                },
                {
                    title: "Telefon",
                    key: 'phone'
                },
                {
                    title: "Manzil",
                    key: 'address'
                },
                {
                    title: "Jami",
                    key: 'total',
                    render: (h, params) => {
                        return "$"+params.row.total
                    }
                },
                {
                    title: "Jami So'm",
                    key: 'totalUzb'
                },
                {
                    title: "Kurs",
                    key: 'dollarRate',
                    render: (h, params) => {
                        return params.row.dollarRate + " so'm"
                    }
                },
                {
                    title: "Do'kon",
                    key: 'store_name'
                },
                {
                    title: "Sana",
                    key: 'created_at'
                },
                {
                    title: "action",
                    key: 'action',
                    width: 180,
                    align: 'right',
                    render: (h, params) => {
                        
                        return h('div', {
                            class: 'ivu-btn-group ivu-btn-group-default'
                        }, 
                        [
                            h(resolveComponent('Button'),
                            {
                                title: "Ko'rish",
                                type: 'primary',
                                size: 'small',
                                to: {name:'orderDetails', params: {order_id:params.row.id}},
                                class: 'text-white'
                            }, {
                                default() {
                                    return h(resolveComponent('Icon'), {
                                        type: 'md-eye'
                                    })
                                }
                            }),

                            h(resolveComponent('Button'),
                            {
                                title:"Taxrirlash",
                                type: 'success',
                                size: 'small',
                                
                                onClick: () => {
                                    this.showEditModal(params.row, params.index)
                                }
                            }, {
                                default() {
                                    return h(resolveComponent('Icon'), {
                                        type: 'md-person'
                                    })
                                }
                            }),
                            
                            h(resolveComponent('Button'),
                            {
                                title:"O'chirish",
                                type: 'error',
                                size: 'small',
                                loading: params.row.isDeleting,
                                disabled: params.row.status != 0 || this.loggedUser.store_id != params.row.store_id,
                                
                                onClick: () => {
                                    this.showDeletingModal(params.row, params.index)
                                }
                            }, {
                                default() {
                                    return h(resolveComponent('Icon'), {
                                        type: 'md-close'
                                    })
                                }
                            }),


                        ])
                    }
                },
                
            ],
            columnsData: [],
            keywordOID: '',
            keywordPhone: '',
        }
    },
    methods : {
        async editClient () {
            if(this.editClientData.fullName == '') {
                this.is_invalid = true
                this.client_name_err = true
            } else {
                this.client_name_err = false
            }

            if(this.editClientData.phone == '') {
                this.is_invalid = true
                this.client_phone_err = true
            } else {
                this.client_phone_err = false
            }

            if(this.editClientData.address == '') {
                this.is_invalid = true
                this.client_address_err = true
            } else {
                this.client_address_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/edit_client', this.editClientData)

            if(res.status == 200) {

                const orders = await this.callApi('get', '/app/get_orders/' + this.loggedUser.store_id + '/' + this.orderStatus)
                this.orders = orders.data

                this.s("Xaridorning ma'lumovlarini muvaffaqiyatli saqlandi!")
                this.editModal = false
            }
        },

        async showEditModal(order, i){
            let productObj = {
                'id':           order.client_id,
                'fullName':     order.fullName,
                'phone':        order.phone,
                'address':      order.address,
            }

            this.editClientData = productObj
            this.editModal = true
            this.index = i
        },

        async deleteClient() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_order", this.order)
            if(res.status === 200) {
                this.orders.splice(this.deletingIndex,1)
                this.s("Buyurtma muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(order, i){
            this.order              = order
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },

        rowClassName (row, index) {
            
            if (row.store_id != this.loggedUser.store_id) {
                return 'demo-table-info-row';
            }

            return '';
        }
    },

    async created(){

        const loggedUser = await this.callApi('get', '/app/logged_user')
        this.loggedUser = loggedUser.data
        
        this.orderStatus = this.$route.params.status.length == 0 ? this.orderStatus : this.$route.params.status

        const orders = await this.callApi('get', '/app/get_orders/'+this.loggedUser.store_id+'/'+this.orderStatus)

        if(orders.status == 200) {
            this.orders = orders.data
            this.loading = false

        } else if(orders.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }



    },
}
</script>
<style>
.ivu-table .demo-table-info-row td{
    background-color: #ff6600;
    color: #fff;
}
</style>