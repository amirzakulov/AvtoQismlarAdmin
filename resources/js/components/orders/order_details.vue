<template>
    <OrdersHeader :order-status="orderStatus" />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom" :gutter="8">
                    <Col span="3">
                        <Button :to="{name: 'orders', params: {status: orderStatus}}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Orqaga qaytish </Button>
                    </Col>
                    <Col span="3" offset="6">
                        <Button :disabled="orderStatus == 2 || order.store_id != loggedUser.store_id" type="info" class="text-white" @click="changeOrderStatus()" ><Icon type="md-checkmark-circle" /> Tasdiqlash </Button>
                    </Col>
                    <Col span="2">
                        <Button v-if="cancelBtn" @click="cancelAssignedOrder" type="error" class="text-white"><Icon type="md-remove-circle" /> Bekor qilish </Button>
                    </Col>
                    <Col span="3" offset="7">
                        <Button :disabled="orderStatus != 0 || order.store_id != loggedUser.store_id" @click="addModal=true" type="primary" class="ivu-mr text-right"><Icon type="md-add" />Maxsulot qo'shish</Button>
                    </Col>
                </Row>
                <Divider />

                <Row>
                    <Col span="16">
                        <Row>
                            <Col span="6"><strong>Buyurtma raqami:</strong></Col><Col span="6"><span class="text-primary font-weight-bold">{{order.oid}}</span></Col>
                        </Row>
                        <Row>
                            <Col span="6"><strong>Buyurtmachi:</strong></Col><Col span="6">{{order.fullName}}</Col>
                        </Row>
                        <Row>
                            <Col span="6"><strong>Telefon:</strong></Col><Col span="6">{{order.phone}}</Col>
                        </Row>
                    </Col>
                    <Col span="8" v-if="[1,2].includes(parseInt(loggedUser.role_id))">
                        <Row>
                            <Col span="10" offset="9">Tanlangan do'kon</Col>
                        </Row>
                        <Row>
                            <Col span="10" offset="9" class="text-right">
                                <Select class="text-left" v-model="selectedStoreData.store_id" placeholder="Do'konni tanlang..."
                                :disabled="selectedStoreData.order_status > 0">
                                    <Option v-for="store in stores" :key="store.id" :value="store.id">{{ store.name }}</Option>
                                </Select>
                            </Col>
                            <Col span="4" class="text-right">
                                <Button type="info" class="text-white ml-2" @click="updateStoreId()"
                                :disabled="selectedStoreData.order_status > 0">Yuborish </Button>
                            </Col>
                        </Row>
                    </Col>
                </Row>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <!-- <th width="20px" class="_text_center"></th> -->
                            <th class="_text_center align-bottom" style="min-width: 200px">Maxsulot nomi</th>
                            <th class="_text_center align-bottom">Kod</th>
                            <!-- <th class="_text_center align-bottom">Kategoriya</th> -->
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
                            <th class="text-right align-bottom">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="orderProducts.length">
                                <tr v-for="(product, i) in orderProducts" :key="i">
                                    <td>{{product.name}}</td>
                                    <td>{{product.code}}</td>
                                    <!-- <td>{{product.category_name}}</td> -->
                                    <td>{{product.country}}</td>
                                    <td>{{product.brand}}</td>
                                    <td>{{product.measurement}}</td>
                                    <td>{{product.count == null ? 0:product.count}}</td>
                                    <td><div>${{product.sell_price}} / {{roundPriceUp(product.sell_price * product.dollar_rate)}}</div></td>
                                    <td><div>${{product.discount}}</div></td>
                                    <td><div>${{(product.sell_price * product.count) - product.discount}}</div></td>
                                    <td><div>{{(roundPriceUp(product.sell_price * product.dollar_rate) - roundPriceUp(product.discount * product.dollar_rate)) * product.count}} so'm</div></td>
                                    <td><div>{{product.dollar_rate}}</div></td>
                                    <td>{{product.updated_at}}</td>
                                    <td class="text-right">
                                        <ButtonGroup class="mt-2" v-if="orderStatus != 2">
                                            <Button :disabled="loggedUser.store_id != order.store_id" type="primary" size="small" title="Taxrirlash" @click="showEditModal(product, i)"><Icon type="md-create"></Icon></Button>
                                            <Button :disabled="loggedUser.store_id != order.store_id" type="error" size="small" title="O'chirish" @click="showDeletingModal(product, i)" :loading="product.isDeleting"><Icon type="md-close"></Icon></Button>
                                        </ButtonGroup>
                                    </td>
                                </tr>
                            </template>

                        </tbody>
                        <tfoot>
                        <tr class="blue_thead bordered">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>${{usdTotal}}</th>
                            <th>{{uzsTotal}} so'm</th>
                            <th><strong></strong></th>
                            <th></th>
                            <th></th>
                            
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Add product modal -->
            <Modal
                v-model="addModal"
                title="Maxsulot qo'shish1"
                :mask-closable="false"
                :closable = "false"
                width="768"
            >
                <Form ref="formValidate" :label-width="180">
                    <FormItem label="* Kategoriya" class="ivu-mb">
                        <Select v-model="data.category_id" placeholder="Kategoriyani tanlang..."
                                @on-change="categorySelected(data.category_id)">
                            <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                        </Select>
                        <Text :class="{ 'd-none': !category_id_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                    <FormItem label="* Maxsulot nomi" class="ivu-mb">
                        <Select v-model="data.product_id" placeholder="Maxsulotni tanlang..."
                                @on-change="productSelected(data.product_id)">
                            <Option v-for="product in categoryProducts" :value="product.id" :key="product.id">{{ product.name + " (" + product.code + ", " + product.country + ", " + product.brand + ")" }}</Option>
                        </Select>
                        <Text :class="{ 'd-none': !product_id_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                    <FormItem label="* Miqdor" class="ivu-mb">
                        <Input v-model="data.count" placeholder="Maxsulot Miqdori"></Input>
                        <Text :class="{ 'd-none': !product_count_err.display }" type="danger">{{product_count_err.message}}</Text>
                    </FormItem>
                    <FormItem label="* Narxi" class="ivu-mb">
                        <Input v-model="data.sell_price" prefix="logo-usd" readonly placeholder="Maxsulot narxi"></Input>
                    </FormItem>
                    <FormItem label="* Chegirma" class="ivu-mb">
                        <Input v-model="data.discount" prefix="logo-usd" placeholder="Chegirma"></Input>
                    </FormItem>
                </Form>
                <template #footer>
                    <Button type="error" @click="clearAddForm">Berkitish</Button>
                    <Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
                </template>
            </Modal>

<!--            // Edit product modal -->
            <Modal
                v-model="editModal"
                :title="editData.name"
                :mask-closable="false"
                :closable = "false"
                width="768"
            >

                <Form ref="formValidate" :label-width="180">

                    <input type="hidden" v-model="editData.id">
                    <input type="hidden" v-model="editData.product_id">
                    <FormItem label="* Miqdor" class="ivu-mb">
                        <Input v-model="editData.count" placeholder="Maxsulot Miqdori"></Input>
                        <Text :class="{ 'd-none': !product_count_err.display }" type="danger">{{product_count_err.message}}</Text>
                    </FormItem>
                    <FormItem label="* Chegirma" class="ivu-mb">
                        <Input v-model="editData.discount" prefix="logo-usd" placeholder="Chegirma"></Input>
                    </FormItem>
                    <FormItem label="* Narxi" class="ivu-mb">
                        <Input v-model="editData.sell_price" prefix="logo-usd" readonly placeholder="Maxsulot narxi"></Input>
                    </FormItem>
                </Form>
                <template #footer>
                    <Button type="error" @click="editModal=false">Berkitish</Button>
                    <Button type="primary" @click="editProduct" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Maxsulot...':'Saqlash'}}</Button>
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
                    <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteProduct">ХА</Button>
                </template>
            </Modal>

</template>

<script>
import OrdersHeader from './parts/header.vue'
export default {
    name: "Order Details",
    components: {
        OrdersHeader:OrdersHeader
    },
    computed: {
        usdTotal(){
            let total = 0;
            this.orderProducts.forEach((item, i) => {
                total += (item.sell_price * item.count) - item.discount;
            });
            return total.toFixed(2);
        },
        uzsTotal(){
            let total = 0;
            this.orderProducts.forEach((item, i) => {
                total += (this.roundPriceUp(item.sell_price * item.dollar_rate) - this.roundPriceUp(item.discount * item.dollar_rate)) * item.count;
            });

            return total;
        },
        
    },
    data(){
        return {
            dollar_rate: 11000,
            order_id: 0,
            order: {},
            addModal: false,
            editModal: false,
            isAdding : false,
            isEditing : false,
            orderProducts: [],
            data: {
                name:       "",
                order_id:   0,
                category_id: 0,
                product_id: 0,
                sell_price: 0,
                price:      0,
                count:      0,
                discount:   0,
                store_id:   0
            },
            editData: {
                name:       "",
                id:         0,
                order_id:   0,
                product_id: 0,
                sell_price: 0,
                price:      0,
                count:      0,
                discount:   0,
                store_id:   0,
            },

            product_id_err:             false,
            product_count_err:          [{display:false, message: "To'ldirish majburiy!"}],
            category_id_err:            false,
            is_invalid:                 false,
            rowIndex: -1,

            showDeleteModal:            false,
            isDeleting:                 false,
            deletingIndex:              -1,
            products: [],
            product: {},
            categories: [],
            category: {},
            selectedProductCount: -1,
            total: 0,
            orderStatus: 0,
            categoryProducts: [],
            stores: [],
            selectedStoreData: {
                order_id: 0,
                store_id: 0,
                order_status: 0,
            },
            loggedUser: {},
            cancelBtn: false,

        }
    },
    methods : {

        subIsActive(input) {
            const paths = Array.isArray(input) ? input : [input]
            return paths.some(path => {
                return this.$route.path.indexOf(path) === 0 // current path starts with this path string
            })
        },

        async addProduct () {

            if(this.data.category_id == '') {
                this.is_invalid     = true
                this.category_id_err = true
            } else {
                this.category_id_err = false
            }

            //check product_id
            if(this.data.product_id == '') {
                this.is_invalid     = true
                this.product_id_err = true
            } else {
                this.product_id_err = false
            }

            //check product count
            if(this.data.count < 1) {
                this.is_invalid = true
                this.product_count_err.display = true
            } else {
                this.product_count_err.display = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            this.data.order_id = this.order_id

            const res = await this.callApi('post', '/app/add_order_product', this.data)

            if(res.status === 200) {
                let productDetails = {
                    'name': res.data.name,
                    'category_name': res.data.category_name,
                    'code': res.data.code,
                    'country': res.data.country,
                    'brand': res.data.brand,
                    'measurement': res.data.measurement,
                    'count': res.data.count,
                    'sell_price': res.data.sell_price,
                    'price': res.data.price,
                    'discount': res.data.discount,
                    'dollar_rate': res.data.dollar_rate,
                    'created_at': res.data.created_at,
                    'updated_at': res.data.updated_at,
                }

                this.orderProducts.unshift(productDetails);
                this.s('Maxsulot muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.data.order_id = 0
                this.data.product_id = 0
                this.data.count = 0
                this.data.sell_price = 0
                this.data.price = 0
                this.data.discount = 0

            } else {
                if(res.status == 422) {
                    if(res.data.errors.name) {
                        this.err(res.data.errors.name[0])
                    }
                } else {
                    this.swr()
                }
            }
        },

        clearAddForm(){
            this.addModal = false
            this.product_count_err.display = false
            this.selectedProductCount = -1

            this.data.order_id = 0
            this.data.product_id = 0
            this.data.price = 0
            this.data.count = 0
        },

        async editProduct () {
            //check product price
            if(this.editData.discount < 0) {
                this.is_invalid = true
                this.product_discount_err = true
            } else {
                this.product_discount_err = false
            }

            //check product Count
            if(this.editData.count < 1) {
                this.is_invalid = true
                this.product_count_err.display = true
            } else {
                this.product_count_err.display = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'dirish majburiy!")
            }

            const res = await this.callApi('post', '/app/edit_order_product', this.editData)

            if(res.status === 200) {

                this.orderProducts[this.rowIndex].count        = res.data.count
                this.orderProducts[this.rowIndex].discount     = res.data.discount

                this.total = this.sumProducts(this.orderProducts)

                this.s("Maxsulot o'zgarishlari muvaffaqiyatli saqlandi!")
                this.editModal = false

            } else {
                if(res.status == 422) {
                    if(res.editData.errors.name) {
                        this.err(res.editData.errors.name[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        showEditModal(product, rowIndex) {

            let productObj = {

                'id':           product.id,
                'order_id':     product.order_id,
                'product_id':   product.product_id,
                'name':         "Maxsulot nomi: " + product.name,
                'sell_price':   product.sell_price,
                'price':        product.price,
                'count':        product.count,
                'discount':     product.discount,
            }

            this.editData = productObj
            this.editModal = true
            this.rowIndex = rowIndex
            this.productSelected(product.product_id)
        },

        async deleteProduct() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_order_product", this.product)

            if(res.status === 200) {
                this.orderProducts.splice(this.deletingIndex,1)
                this.total = this.sumProducts(this.orderProducts)
                this.s('Maxsulot muvaffaqiyatli o\'chirildi!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(product, i){
            this.product            = product
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
        async changeOrderStatus() {
            let status = 1
            if(this.orderStatus == 1) {
                status = 2;
                this.orderStatus = status
            }
            let data = {
                "order_id": this.order.id,
                "arr" : {"status":status}
            }

            const orderStatus2 = await this.callApi('post', '/app/update_order', data)
            if(orderStatus2.status == 200) {
                this.$router.push('/orders/'+this.orderStatus)
            }
        },
        async categorySelected(category_id) {
            console.log(category_id);
            const categoryProducts = await this.callApi('get', '/app/get_category_products/'+category_id)
            
            this.categoryProducts = categoryProducts.data
        },
        async productSelected(product_id) {
            const product = await this.callApi('get', '/app/get_product/' + product_id)
            this.selectedProductCount = product.data.count
            this.data.sell_price    = product.data.sell_price
            this.data.price         = product.data.price

        },

        sumProducts(products) {
            let total = 0
            products.forEach((product, index) => {
                total = total + (product.count * product.sell_price)
            })

            return total
        },
        async updateStoreId(){
            this.selectedStoreData.order_id = this.order_id
            
            const res = await this.callApi('post', '/app/assign_to_store', this.selectedStoreData);
            
            if(res.data) {
                this.s('Buyurtma do\'konga muvaffaqiyatli biriktirildi!')
                this.$router.push('/orders/' +this.orderStatus)
            }
        },
        async cancelAssignedOrder(){
            console.log("sdfsd");
            let cancelData = {'order_id': this.order.id, 'store_id': 1}
            const res = await this.callApi('post', '/app/assign_to_store', cancelData);
            
            if(res.data) {
                this.s('Buyurtma rad etildi!')
                this.$router.push('/orders/' +this.orderStatus)
            }
        }
    },
    async created(){
        this.order_id  = this.$route.params.order_id
        
        const order   = await this.callApi('get', '/app/get_order/' + this.order_id)
        if(order.status == 200) {
            this.order  = order.data
            this.selectedStoreData.store_id = this.order.store_id
            this.selectedStoreData.order_status = this.order.status
            
        } else if(order.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }


        this.orderStatus = this.order.status == 0 ? '' : this.order.status

        const orderProducts = await this.callApi('get', '/app/get_order_products/' + this.order_id)
        this.orderProducts  = orderProducts.data

        const categories = await this.callApi('get', '/app/get_categories/')
        this.categories = categories.data

        const stores = await this.callApi('get', '/app/get_stores')
        this.stores  = stores.data

        const loggedUser = await this.callApi('get', '/app/logged_user')
        this.loggedUser = loggedUser.data

        this.cancelBtn = this.loggedUser.store_id == 1 || this.orderStatus != 0 ? false:true

        // console.log("store_id", this.loggedUser.store_id);
        console.log("cvxv", this.loggedUser);


    }


}
</script>

<style scoped>

</style>
