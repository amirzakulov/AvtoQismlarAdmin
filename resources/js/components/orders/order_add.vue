<template>
    <OrderHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{ name: 'orders', params: {status: ''}}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Orqaga qaytish </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button type="primary" :long="true" @click="addProducts('orderProducts')">Saqlash</Button>
                    </Col>
                </Row>
                <Divider />
                <h3><Icon type="md-cloud-download" size="30" class="text-primary" /> Buyurtma</h3>
                <br>
                <Form ref="orderProducts" label-position="left">
                    <h4 class="_title0">Асосий маълумотлар</h4>
                    <Row style="border: 1px solid #ccc; margin-bottom: 50px; padding: 10px; background-color: #eeeff3;">
                        <Col span="6" style="padding-right:10px;">
                            <FormItem label="* Xaridor">
                                <Select v-model="client_id" placeholder="Tanlash...">
                                    <Option v-for="client in clients" :value="client.id" :key="client.id">{{ client.fullName +" ("+ client.phone+")" }}</Option>
                                </Select>
                                <Text :class="{ 'd-none': !client_id_err }" type="danger">Tanlash majburiy!</Text>
                            </FormItem>
                        </Col>
                    </Row>
                    <h4 class="_title0">Maxsulotlar</h4>
                    <template v-for="(productField, index) in orderProducts" :key="index">
                        <Row style="border: 1px solid #ccc; margin-bottom: 5px; padding: 10px; background-color: #eeeff3;">
                            <Col span="6" style="padding-right:10px;">
                                <FormItem label="* Kategoriya" :key="index">
                                    <Select v-model="orderProducts[index].category_id" placeholder="Kategoriyani tanlang..."
                                    @on-change="categorySelected(orderProducts[index].category_id, index)">
                                        <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                                    </Select>
                                    <Text :class="{ 'd-none': !category_id_err[index] }" type="danger">To'ldirish majburiy!</Text>
                                </FormItem>
                            </Col>
                            <Col span="5" style="padding-right:10px;">
                                <FormItem label="* Maxsulot nomi" :key="index">
                                    <Select v-model="orderProducts[index].product_id" filterable placeholder="Maxsulotni tanlang..."
                                            @on-change="productSelected(orderProducts[index].product_id, index)">
                                        <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.country + ", " + product.brand + ")" }}</Option>
                                    </Select>
                                    <Text :class="{ 'd-none': !product_id_err[index] }" type="danger">To'ldirish majburiy!</Text>
                                </FormItem>
                            </Col>
                            <Col span="4" style="padding-right:10px">
                                <FormItem label="* Narx">
                                    <Input v-model="orderProducts[index].sell_price" placeholder="Maxsulot Narxi" readonly></Input>
                                    <Text :class="{ 'd-none': !product_price_err[index] }" type="danger">To'ldirish majburiy!</Text>
                                </FormItem>
                            </Col>
                            <Col span="4" style="padding-right:10px">
                                <FormItem label="* Miqdor" class="mb-0">
                                    <Input v-model="orderProducts[index].count" placeholder="Maxsulot miqdori"></Input>
                                    <Text :class="{ 'd-none': !product_count_err[index].display }" type="danger">{{product_count_err[index].message}}</Text>
                                </FormItem>
                            </Col>
                            <Col span="4" style="padding-right:10px">
                                <FormItem label="* Chegirma" class="mb-0">
                                    <Input v-model="orderProducts[index].discount" placeholder="Chegirma"></Input>
                                    <Text :class="{ 'd-none': !product_discount_err[index] }" type="danger">To'ldirish majburiy!</Text>
                                </FormItem>
                            </Col>
                            <Col span="1" style="padding-top:45px; padding-left: 8px;">
                                <a href="javascript:void(0);">
                                    <Icon type="md-remove-circle" size="20" color="#dc3545" @click="handleRemove(index)" />
                                </a>
                            </Col>
                        </Row>
                    </template>

                <FormItem>
                    <Row>
                        <Col span="3" offset="21">
<!--                            <Icon type="md-add-circle" size="20" class="text-success" />-->
                            <Button type="dashed" :long="true" @click="handleAdd" icon="md-add" class="bg-success text-white">Махсулот қўшиш</Button>
                        </Col>
                    </Row>
                </FormItem>

                </Form>
            </div>
    <router-view />

</template>



<script>
import OrderHeader from './parts/header.vue'
export default {
    name: "orderAdd",
    components: {
        OrderHeader: OrderHeader
    },
    data(){
        return {
            index: 0,
            orderProducts: [
                {
                    category_id:'',
                    product_id: '',
                    sell_price: 0,
                    price:      0,
                    count:      0,
                    discount:   0,
                }
            ],
            clients: [],
            client_id: '',
            categories: [],
            products: [],

            client_id_err:      false,
            category_id_err:    [false],
            product_id_err:     [false],
            product_price_err:  [false],
            product_count_err:  [{display:false, message: "To'ldirish majburiy!"}],
            product_discount_err:  [false],
            is_invalid:         false,

            selectedProductCount: -1,
            loggedUser: {},
        }
    },
    methods: {
        async addProducts () {

            if(this.client_id == '') {
                this.is_invalid = true
                this.client_id_err = true
            } else {
                this.client_id_err = false
            }

            this.orderProducts.forEach((row, index) => {

                //check category_id
                if(row.category_id == '') {
                    this.is_invalid = true
                    this.category_id_err[index] = true
                } else {
                    this.category_id_err[index] = false
                }

                //check product_id
                if(row.product_id == '') {
                    this.is_invalid = true
                    this.product_id_err[index] = true
                } else {
                    this.product_id_err[index] = false
                }

                //check product price
                if(row.sell_price == '') {
                    this.is_invalid = true
                    this.product_price_err[index] = true
                } else {
                    this.product_price_err[index] = false
                }

                //check product Count
                if(row.count == '') {
                    this.is_invalid = true
                    // this.product_count_err[index] = true
                    this.product_count_err[index].display = true
                } else {
                    // this.product_count_err[index] = false
                    this.product_count_err[index].display = false
                }
            });

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Barcha kataklarni to\'ldirish majburiy!')
            }

            let orderDetails = {
                client_id: this.client_id,
                store_id:  this.loggedUser.store_id,
                products:  this.orderProducts,
            }

            const res = await this.callApi('post', '/app/add_order', orderDetails)
            console.log(res.data);

            if(res) {
                this.$router.push('/orders')
            }

        },
        handleAdd () {
            this.index++;
            this.orderProducts.push({
                product_id: '',
                sell_price: 0,
                price: 0,
                count: 0,
                discount: 0
            });

            this.product_count_err.push({display:false, message: "To'ldirish majburiy!"})
        },
        handleRemove(index) {

            if(Object.keys(this.orderProducts).length > 1){
                this.orderProducts.splice(index, 1);
            } else {
                return this.err('Qatorni o\'chiraolmaysiz!')
            }
        },
        async categorySelected(category_id, index) {
            const products  = await this.callApi('get', '/app/get_category_products/' + category_id)
            this.products   = products.data

        },
        async productSelected(product_id, index) {
            const product = await this.callApi('get', '/app/get_product/' + product_id)
            this.orderProducts[index].sell_price = product.data.sell_price
            this.orderProducts[index].price      = product.data.price
        },

    },

    async created(){
        const clients = await this.callApi('get', '/app/get_clients')
        if(clients.status == 200) {
            this.clients = clients.data
        } else if(clients.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }

        
        const categories = await this.callApi('get', '/app/get_categories')
        this.categories = categories.data

        // const products = await this.callApi('get', '/app/get_products')
        // this.products = products.data

        const loggedUser = await this.callApi('get', '/app/logged_user')
        this.loggedUser = loggedUser.data

        // console.log(this.loggedUser);

    }
}
</script>
