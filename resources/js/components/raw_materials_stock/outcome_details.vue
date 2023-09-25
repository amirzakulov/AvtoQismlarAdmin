<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{name: 'inout', params: {inout:'inout'}}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Орқога қайтиш </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button @click="addModal=true" class="ivu-mr text-right d-none"><Icon type="md-add" />Махсулот қўшиш</Button>
                    </Col>
                </Row>
                <Divider />
                <Row class="mt-3 mb-1">
                    <Col span="10"><h4>Шартнома: {{contract_name}}</h4></Col>
                    <Col span="2" offset="12" class="text-right">
                        <RadioGroup v-model="currency_id" v-for="currency in currencies" :key="currency.id" type="button" button-style="solid">
                            <Radio border v-if="currency_id == currency.id" :label="currency.id">
                                {{currency.name}}
                            </Radio>
                        </RadioGroup>
                    </Col>
                </Row>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th rowspan="2" width="20px" class="_text_center align-top"></th>
                            <th rowspan="2" class=" _text_center align-bottom" style="min-width: 300px">Махсулот Номи</th>
                            <th rowspan="2" class=" _text_center align-bottom">Коди</th>
                            <th rowspan="2" class=" _text_center align-bottom">Ранги</th>
                            <th rowspan="2" class=" _text_center align-bottom">Давлат</th>
                            <th rowspan="2" class=" _text_center align-bottom">Бирлиги</th>
                            <th rowspan="2" class=" _text_center align-bottom">Тўп</th>
                            <th rowspan="2" class=" _text_center align-bottom">Миқдор</th>
                            <th colspan="2" class=" _text_center align-bottom">USD</th>
                            <th colspan="2" class=" _text_center align-bottom">UZS</th>
                            <th rowspan="2" class=" _text_center align-bottom">Сана</th>
                            <th rowspan="2" class="text-right align-bottom">Action</th>
                        </tr>
                        <tr class="blue_thead bordered">
                            <th class="_text_center">Нарх</th>
                            <th class="_text_center">Жами</th>
                            <th class="_text_center">Нарх</th>
                            <th class="_text_center">Жами</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="(product, i) in contractProducts" :key="i"
                            v-if="contractProducts.length"
                        >
                            <td width="20px">
                                <Avatar :src="product.picture" v-if="product.picture" />
                                <Icon type="ios-image" size="24" v-else />
                            </td>
                            <td>{{product.name}}</td>
                            <td>{{product.code}}</td>
                            <td>{{product.color}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.packageCount == null ? 0:(-1 * product.packageCount)}}</td>
                            <td>{{product.count == null ? 0:(-1 * product.count)}}</td>
                            <td><div v-if="currency_id == 1">{{product.price}}</div></td>
                            <td><div v-if="currency_id == 1">{{product.price * (-1 * product.count)}}</div></td>
                            <td><div v-if="currency_id == 2">{{product.price}}</div></td>
                            <td><div v-if="currency_id == 2">{{product.price * (-1 * product.count)}}</div></td>
                            <td>{{product.updated_at}}</td>
                            <td class="text-right">
                                <Button type="info" size="small" @click="showEditModal(product, i)">edit</Button>
                                <Button type="error" size="small" @click="showDeletingModal(product, i)" :loading="product.isDeleting">delete</Button>
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
                            <th></th>
                            <th><div v-if="currency_id == 1">{{total}}</div></th>
                            <th></th>
                            <th><div v-if="currency_id == 2">{{total}}</div></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>


            <!-- Add product modal -->
    <!-- <Modal
        v-model="addModal"
        title="Махсулот қўшиш"
        :mask-closable="false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Махсулот номи">
                <Select v-model="newProduct.product_id" filterable placeholder="Махсулотни танланг...">
                    <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.country + ")" }}</Option>
                </Select>
                <Text :class="{ 'd-none': !product_id_err }" type="danger">Тўлдириш мажбурий!</Text>
            </FormItem>
            <FormItem label="* Нархи">
                <Input v-model="newProduct.price" placeholder="Махсулот Нархи"></Input>
                <Text :class="{ 'd-none': !product_price_err }" type="danger">Тўлдириш мажбурий!</Text>
            </FormItem>
            <FormItem label="* Тўп">
                <Input v-model="newProduct.packageCount" placeholder="Махсулот Тўп"></Input>
                <Text :class="{ 'd-none': !product_packageCount_err }" type="danger">Тўлдириш мажбурий!</Text>
            </FormItem>
            <FormItem label="* Миқдор" class="mb-0">
                <Input v-model="newProduct.count" placeholder="Махсулот Миқдор"></Input>
                <Text :class="{ 'd-none': !product_count_err }" type="danger">Тўлдириш мажбурий!</Text>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Махсулот...':'Сақлаш'}}</Button>
        </template>
    </Modal> -->

            <!-- Edit product modal -->
            <Modal
                v-model="editModal"
                title="Махсулотни тахрирлаш"
                :mask-closable="false"
                :closable = "false"
                width="768"
            >

                <Form ref="formValidate" :label-width="180">
<!--                    <FormItem label="* Махсулот номи">-->
<!--                        <Select v-model="oldProduct.product_id" filterable placeholder="Махсулотни танланг...">-->
<!--                            <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.country + ")" }}</Option>-->
<!--                        </Select>-->
<!--                        <Text :class="{ 'd-none': !product_id_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                    </FormItem>-->
<!--                    <FormItem label="* Нархи">-->
<!--                        <Input v-model="oldProduct.price" placeholder="Махсулот Нархи"></Input>-->
<!--                        <Text :class="{ 'd-none': !product_price_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                    </FormItem>-->
                    <FormItem label="Махсулот номи" class="ivu-mb">
                        <Input v-model="oldProduct.name" disabled></Input>
                    </FormItem>
                    <FormItem label="* Тўп" class="ivu-mb">
                        <Input v-model="oldProduct.packageCount" placeholder="Махсулот Тўпи"></Input>
                        <Text :class="{ 'd-none': !product_packageCount_err }" type="danger">Тўлдириш мажбурий!</Text>
                    </FormItem>
                    <FormItem label="* Миқдор" class="mb-0">
                        <Input v-model="oldProduct.count" placeholder="Махсулот Миқдор"></Input>
                        <Text :class="{ 'd-none': !product_count_err }" type="danger">Тўлдириш мажбурий!</Text>
                    </FormItem>
                </Form>
                <template #footer>
                    <Button type="error" @click="editModal=false">Беркитиш</Button>
                    <Button type="primary" @click="editProduct" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Махсулот...':'Сақлаш'}}</Button>
                </template>
            </Modal>

            <!-- Delete tag modal -->
            <Modal v-model="showDeleteModal" width="360">
                <template #header>
                    <p style="color:#f60; text-align:center">
                        <Icon type="ios-close-circle" size="20" />
                        <span>Ўчиришни тасдиқлаш?</span>
                    </p>
                </template>
                <div style="text-align:center">
                    <p>Хақиқатдан хам ўчирасизми?</p>
                </div>
                <template #footer>
                    <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteProduct">ХА</Button>
                </template>
            </Modal>

</template>

<script>
import StockHeader from './rmstock_header.vue'
export default {
    name: "rmincomeDetails",
    components: {
        StockHeader:StockHeader
    },
    data(){
        return {
            addModal: false,
            editModal: false,
            isAdding : false,
            isEditing : false,
            contractProducts: [],
            products: [],
            currency_id: '',
            currencies: [],
            contract_id: '',
            contract_name: '',
            newProduct: {
                product_id: '',
                price: '',
                packageCount: '',
                count: ''
            },
            oldProduct: {
                id: '',
                raw_material_id: '',
                product_id: '',
                name: '',
                packageCount: '',
                count: '',
                price: 0
            },

            product_id_err:             false,
            product_price_err:          false,
            product_packageCount_err:   false,
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
        /*async addProduct () {

            //check product_id
            if(this.newProduct.product_id == '') {
                this.is_invalid     = true
                this.product_id_err = true
            } else {
                this.product_id_err = false
            }

            //check product price
            if(this.newProduct.price == '') {
                this.is_invalid = true
                this.product_price_err = true
            } else {
                this.product_price_err = false
            }

            //check product packageCount
            if(this.newProduct.packageCount == '') {
                this.is_invalid = true
                this.product_packageCount_err = true
            } else {
                this.product_packageCount_err = false
            }


            //check product packegeCount
            if(this.newProduct.count == '') {
                this.is_invalid = true
                this.product_count_err = true
            } else {
                this.product_count_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            this.newProduct.contract_id     = this.contract_id
            this.newProduct.contract_name   = this.contract_name
            this.newProduct.currency_id     = this.currency_id
            this.newProduct.inout           = 1

            const res = await this.callApi('post', '/app/add_income_product', this.newProduct)

            if(res.status === 200) {
                const product = await this.callApi('get', '/app/get_product/'+this.newProduct.product_id)
                let productDetails = {
                    'name': product.data.name,
                    'code': product.data.code,
                    'color': product.data.color,
                    'country': product.data.country,
                    'measurement': product.data.measurement,
                    'packageCount': res.data.packageCount,
                    'count': res.data.count,
                    'price': res.data.price,
                    'currency_id': res.data.currency_id,
                    'updated_at': res.data.updated_at,
                }

                this.contractProducts.unshift(productDetails);
                this.total = this.sumProducts(this.contractProducts)

                this.s('Махсулот мувоффақиятли қўшилди!')
                this.addModal = false
                this.newProduct.product_id = ''
                this.newProduct.count = ''
                this.newProduct.price = ''
                this.newProduct.packageCount = ''

            } else {
                if(res.status == 422) {
                    if(res.data.errors.name) {
                        this.err(res.data.errors.name[0])
                    }
                } else {
                    this.swr()
                }
            }
        },*/
        async editProduct () {
            //check product packageCount
            if(this.oldProduct.packageCount == '') {
                this.is_invalid = true
                this.product_packageCount_err = true
            } else {
                this.product_packageCount_err = false
            }

            //check product packegeCount
            if(this.oldProduct.count == '') {
                this.is_invalid = true
                this.product_count_err = true
            } else {
                this.product_count_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            const res = await this.callApi('post', '/app/edit_outcome_product', this.oldProduct)

            if(res.status === 200) {

                this.contractProducts[this.rowIndex].name         = res.data.name
                this.contractProducts[this.rowIndex].packageCount = (-1) * res.data.packageCount
                this.contractProducts[this.rowIndex].count        = (-1) * res.data.count
                this.contractProducts[this.rowIndex].price        = res.data.price
                this.contractProducts[this.rowIndex].updated_at   = res.data.updated_at

                this.total = this.sumProducts(this.contractProducts)

                this.s('Махсулот ўзгаришлари мувоффақиятли сақланди!')
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
                'id':               product.id,
                'raw_material_id':  product.raw_material_id,
                'product_id':       product.product_id,
                'name':             product.name + " ("+product.country+")",
                'packageCount':     (-1) * product.packageCount,
                'count':            (-1) * product.count,
                'price':            product.price
            }

            this.oldProduct = productObj
            this.editModal = true
            this.rowIndex = rowIndex
        },

        async deleteProduct() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_outcome_product", this.product)
            if(res.status === 200) {
                this.contractProducts.splice(this.deletingIndex,1)
                this.total = this.sumProducts(this.contractProducts)
                this.s('Махсулот мувоффақиятли ўчирилди!')
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

        sumProducts(outcomeProducts, update = true){
            let total = 0
            outcomeProducts.forEach((product, index) => {
                total = total + (product.count * product.price)
            })

            total = total < 0 ? (-1 * total) : total

            if(update) this.callApi('post', '/app/update_contract_total/' + this.contract_id, {total: total})

            return total
        }
    },
    async created(){

        this.contract_id        = this.$route.params.id
        const contract          = await this.callApi('get', '/app/get_contract/' + this.contract_id)
        const contractProducts  = await this.callApi('get', '/app/get_contract_products/' + this.contract_id)

        const products          = await this.callApi('get', '/app/get_products/')
        const currencies        = await this.callApi('get', '/app/get_currencies')

        this.contract_name      = contract.data.name
        this.currency_id        = contract.data.currency_id
        this.currencies         = currencies.data;

        if(contractProducts.status == 200) {

            this.contractProducts = contractProducts.data
            this.total = this.sumProducts(this.contractProducts, false)

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
