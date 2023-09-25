<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{name: 'income'}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Орқога қайтиш </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button @click="addModal=true" class="ivu-mr text-right"><Icon type="md-add" />Махсулот қўшиш</Button>
                    </Col>
                </Row>
                <Divider />
                <Row class="mt-3 mb-1">
                    <Col span="10"><h4>Шартнома: {{contract.name}}{{contract.id}}</h4></Col>
                </Row>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th rowspan="2" width="20px" class="_text_center"></th>
                            <th rowspan="2" class="_text_center align-bottom" style="min-width: 300px">Maxsulot nomi</th>
                            <th rowspan="2" class="_text_center align-bottom">Kod</th>
                            <th rowspan="2" class="_text_center align-bottom">Davlat</th>
                            <th rowspan="2" class="_text_center align-bottom">Brend</th>
                            <th rowspan="2" class="_text_center align-bottom">Birligi</th>
                            <th rowspan="2" class="_text_center align-bottom">Miqdor</th>
                            <th colspan="2" class="_text_center">USD</th>
                            <th rowspan="2" class="_text_center align-bottom">Sana</th>
                            <th rowspan="2" class="text-right align-bottom">Action</th>
                        </tr>
                        <tr class="blue_thead bordered">
                            <th class="_text_center">Narx</th>
                            <th class="_text_center">Jami</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(product, i) in incomeProducts" :key="i"
                            v-if="incomeProducts.length"
                        >
                            <td width="20px">
                                <Avatar :src="product.picture" v-if="product.picture" />
                                <Icon type="ios-image" size="24" v-else />
                            </td>
                            <td>{{product.name}}</td>
                            <td>{{product.code}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.brand}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.count == null ? 0:product.count}}</td>
                            <td><div>{{product.price}}</div></td>
                            <td><div>{{product.price * product.count}}</div></td>
                            <td>{{product.updated_at}}</td>
                            <td class="text-right">
                                <Button :disabled="!product.editable" type="info" size="small" @click="showEditModal(product, i)">edit</Button>
                                <Button :disabled="!product.editable" type="error" size="small" @click="showDeletingModal(product, i)" :loading="product.isDeleting">delete</Button>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr class="blue_thead bordered">
                            <th width="20px" class="_text_center align-top"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th><strong>{{total}}</strong></th>
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
                title="Maxsulot qo'shish"
                :mask-closable="false"
                :closable = "false"
                width="768"
            >

                <Form ref="formValidate" :label-width="180">
                    <FormItem label="* Maxsulot nomi" class="ivu-mb">
                        <Select v-model="data.product_id" placeholder="Maxsulotni tanlang...">
                            <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.country + ", " + product.brand + ")" }}</Option>
                        </Select>
                        <Text :class="{ 'd-none': !product_id_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                    <FormItem label="* Narxi" class="ivu-mb">
                        <Input v-model="data.price" placeholder="Maxsulot narxi"></Input>
                        <Text :class="{ 'd-none': !product_price_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                    <FormItem label="* Miqdor" class="mb-0">
                        <Input v-model="data.count" placeholder="Maxsulot Miqdori"></Input>
                        <Text :class="{ 'd-none': !product_count_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                </Form>
                <template #footer>
                    <Button type="error" @click="addModal=false">Berkitish</Button>
                    <Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
                </template>
            </Modal>

            <!-- Edit product modal -->
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
                    <FormItem label="* Narxi" class="ivu-mb">
                        <Input v-model="editData.price" placeholder="Maxsulot Narxi"></Input>
                        <Text :class="{ 'd-none': !product_price_err }" type="danger">To'ldirish majburiy!</Text>
                    </FormItem>
                    <FormItem label="* Миқдор" class="mb-0">
                        <Input v-model="editData.count" placeholder="Maxsulot Miqdori"></Input>
                        <Text :class="{ 'd-none': !product_count_err }" type="danger">To'ldirish majburiy!</Text>
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
import StockHeader from './parts/header.vue'
export default {
    name: "incomeDetails",
    components: {
        StockHeader:StockHeader
    },
    data(){
        return {
            addModal: false,
            editModal: false,
            isAdding : false,
            isEditing : false,
            incomeProducts: [],
            products: [],
            contract: {},
            data: {
                contract_id: 0,
                product_id: 0,
                price: 0,
                count: 0
            },
            editData: {
                name: "",
                id: '',
                product_id: 0,
                price: 0,
                count: 0
            },

            product_id_err:             false,
            product_price_err:          false,
            product_count_err:          false,
            is_invalid:                 false,
            rowIndex: '',

            showDeleteModal:            false,
            isDeleting:                 false,
            deletingIndex:              -1,
            product: {},

            total: 0,

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

            //check product_id
            if(this.data.product_id == '') {
                this.is_invalid     = true
                this.product_id_err = true
            } else {
                this.product_id_err = false
            }

            //check product price
            if(this.data.price == '') {
                this.is_invalid = true
                this.product_price_err = true
            } else {
                this.product_price_err = false
            }

            //check product count
            if(this.data.count == '') {
                this.is_invalid = true
                this.product_count_err = true
            } else {
                this.product_count_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            this.data.contract_id     = this.contract_id

            const res = await this.callApi('post', '/app/add_income_product', this.data)

            if(res.status === 200) {
                const product = await this.callApi('get', '/app/get_product/'+this.data.product_id)
                let productDetails = {
                    'editable': 1,
                    'name': product.data.name,
                    'code': product.data.code,
                    'country': product.data.country,
                    'brand': product.data.brand,
                    'measurement': product.data.measurement,
                    'count': res.data.count,
                    'price': res.data.price,
                    'updated_at': res.data.updated_at,
                }


                this.incomeProducts.unshift(productDetails);
                this.total = this.sumProducts(this.incomeProducts)

                this.s('Махсулот мувоффақиятли қўшилди!')
                this.addModal = false
                this.data.product_id = ''
                this.data.count = ''
                this.data.price = ''

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
        async editProduct () {
            //check product price
            if(this.editData.price == '') {
                this.is_invalid = true
                this.product_price_err = true
            } else {
                this.product_price_err = false
            }

            //check product packegeCount
            if(this.editData.count == '') {
                this.is_invalid = true
                this.product_count_err = true
            } else {
                this.product_count_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/edit_income_product', this.editData)

            if(res.status === 200) {

                this.incomeProducts[this.rowIndex].count        = res.data.count
                this.incomeProducts[this.rowIndex].price        = res.data.price

                this.total = this.sumProducts(this.incomeProducts)

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

                'id':       product.id,
                'product_id': product.product_id,
                'name':     "Maxsulot nomi: " + product.name,
                'count':    product.count,
                'price':    product.price,

            }

            this.editData = productObj
            this.editModal = true
            this.rowIndex = rowIndex
        },

        async deleteProduct() {
            this.isDeleting = true
            this.incomeProducts.splice(this.deletingIndex,1)

            // const res = await this.callApi("post", "/app/delete_income_product", this.product)
            // if(res.status === 200) {
            //     this.incomeProducts.splice(this.deletingIndex,1)
            //     console.log(this.incomeProducts)
            //     this.total = this.sumProducts(this.incomeProducts)
            //     this.s('Махсулот мувоффақиятли ўчирилди!')
            // } else {
            //     this.swr();
            // }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(product, i){
            this.product            = product
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
        sumProducts(incomeProducts, update = true) {
            let total = 0
            incomeProducts.forEach((product, index) => {
                total = total + (product.count * product.price)
            })

            let contractData = {
                    "id":this.contract_id,
                    "contract":{"total":total}
                }

            if(update) this.callApi('post', '/app/update_income_set', contractData)

            return total
        }
    },
    async created(){

        this.contract_id    = this.$route.params.contract_id

        const contract      = await this.callApi('get', '/app/get_income_set/' + this.contract_id)
        this.contract       = contract.data

        const incomeProducts    = await this.callApi('get', '/app/get_income_products/' + this.contract_id)
        const products          = await this.callApi('get', '/app/get_products/')

        if(incomeProducts.status == 200) {
            this.incomeProducts = incomeProducts.data
            this.total = this.sumProducts(this.incomeProducts, false)
        } else {
            this.swr()
        }

        if(products.status == 200) {
            this.products = products.data
        } else {
            this.swr()
        }


    }


}
</script>

<style scoped>

</style>
