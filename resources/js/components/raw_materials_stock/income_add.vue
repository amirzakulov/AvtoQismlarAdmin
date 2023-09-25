<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{ name: 'rmIncome'}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Орқога қайтиш </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button type="primary" :long="true" @click="addProducts('incomeProducts')">Сақлаш</Button>
                    </Col>
                </Row>
                <Divider />
                <h3><Icon type="md-cloud-download"  class="text-primary" /> Кирим</h3>
                <br>
                <Form ref="incomeProducts" label-position="left">
<!--                    <p class="_title0">-->
<!--                        <h4>Асосий маълумотлар</h4>-->
<!--                    </p>-->
<!--                    <Row style="border: 1px solid #ccc; margin-bottom: 50px; padding: 10px; background-color: #eeeff3;">-->
<!--                        <Col span="6" style="padding-right:10px;">-->
<!--                            <FormItem label="* Кимдан">-->
<!--                                <Select v-model="contract.client_id" placeholder="Етказиб берувчини танланг...">-->
<!--                                    <Option v-for="client in clients" :value="client.id" :key="client.id">{{ client.name }}</Option>-->
<!--                                </Select>-->
<!--                                <Text :class="{ 'd-none': !client_id_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                            </FormItem>-->
<!--                        </Col>-->
<!--                        <Col span="6" style="padding-right:10px">-->
<!--                            <FormItem label="* Қайси бўлимдан">-->
<!--                                <Select v-model="contract.from_department_id" placeholder="Етказиб берувчини танланг...">-->
<!--                                    <Option v-for="department in departments" :value="department.id" :key="department.id">{{ department.name }}</Option>-->
<!--                                </Select>-->
<!--                                <Text :class="{ 'd-none': !from_department_id_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                            </FormItem>-->
<!--                        </Col>-->
<!--                        <Col span="6" style="padding-right:10px">-->
<!--                            <FormItem label="* Қайси бўлимга">-->
<!--                                <Select v-model="contract.to_department_id" placeholder="Қабул қилувчини танланг...">-->
<!--                                    <Option v-for="department in departments" :value="department.id" :key="department.id">{{ department.name }}</Option>-->
<!--                                </Select>-->
<!--                                <Text :class="{ 'd-none': !to_department_id_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                            </FormItem>-->
<!--                        </Col>-->
<!--                        <Col span="6" style="padding-right:10px">-->
<!--                            <FormItem label="* Киро хақи (сум)">-->
<!--                                <Input v-model="contract.transportation_cost" placeholder="Киро хақи"></Input>-->
<!--                                <Text :class="{ 'd-none': !transportation_cost_err }" type="danger">Тўлдириш мажбурий!</Text>-->
<!--                            </FormItem>-->
<!--                        </Col>-->
<!--                    </Row>-->

                    <p class="_title0">
                        <h4>Махсулотлар</h4>
                    </p>
<!--                    Пул бирлиги-->
                    <FormItem class="ivu-mb">
                        <RadioGroup v-model="currency_id" type="button" button-style="solid">
                            <Radio border v-for="currency in currencies" :label="currency.id" :key="currency.id">
                                {{currency.name}}
                            </Radio>
                        </RadioGroup>
                        <br>
                        <Text :class="{ 'd-none': !currency_id_err }" type="danger">Пул бирлигини танланг!</Text>
                    </FormItem>
                    <template v-for="(productField, index) in incomeProducts">
                        <Row style="border: 1px solid #ccc; margin-bottom: 5px; padding: 10px; background-color: #eeeff3;">
                            <Col span="12" style="padding-right:10px;">
                                <FormItem label="* Махсулот номи" :key="index">
                                    <Select v-model="incomeProducts[index].product_id" filterable placeholder="Махсулотни танланг...">
                                        <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.country + ")" }}</Option>
                                    </Select>
                                    <Text :class="{ 'd-none': !product_id_err[index] }" type="danger">Тўлдириш мажбурий!</Text>
                                </FormItem>
                            </Col>
                            <Col span="6" style="padding-right:10px">
                                <FormItem label="* Нархи">
                                    <Input
                                        v-model="incomeProducts[index].price"
                                        placeholder="Махсулот Нархи"></Input>
                                    <Text :class="{ 'd-none': !product_price_err[index] }" type="danger">Тўлдириш мажбурий!</Text>
                                </FormItem>
                            </Col>
                            <Col span="5" style="padding-right:10px">
                                <FormItem label="* Миқдор" class="mb-0">
                                    <Input v-model="incomeProducts[index].count" placeholder="Махсулот Миқдор"></Input>
                                    <Text :class="{ 'd-none': !product_count_err[index] }" type="danger">Тўлдириш мажбурий!</Text>
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
import StockHeader from './rmstock_header.vue'
export default {
    name: "income",
    components: {
        StockHeader: StockHeader
    },
    data(){
        return {
            // contract: {
            //     client_id: '',
            //     from_department_id: '',
            //     to_department_id: '',
            //     transportation_cost: ''
            // },
            currency_id: '',
            // clients: [
            //     {name: 'Bahromjon', id: 1},
            //     {name: 'Bahodirjon', id: 2},
            // ],
            index: 0,
            incomeProducts: [
                {
                    product_id: '',
                    price: '',
                    count: ''
                }
            ],
            products: [],

            // client_id_err:          false,
            // from_department_id_err: false,
            // to_department_id_err:   false,
            // transportation_cost_err:false,
            currency_id_err:   false,

            product_id_err:         [false],
            product_price_err:      [false],
            product_count_err:      [false],
            is_invalid:             false,
            departments:            [],
            currencies: [],
        }
    },
    methods: {
        async addProducts () {

            //check product_id
            // if(this.contract.client_id == '') {
            //     this.is_invalid     = true
            //     this.client_id_err  = true
            // } else {
            //     this.client_id_err  = false
            // }

            // if(this.contract.from_department_id == '') {
            //     this.is_invalid     = true
            //     this.from_department_id_err  = true
            // } else {
            //     this.from_department_id_err  = false
            // }
            //
            // if(this.contract.to_department_id == '') {
            //     this.is_invalid     = true
            //     this.to_department_id_err  = true
            // } else {
            //     this.to_department_id_err  = false
            // }
            //
            // if(this.contract.transportation_cost == '') {
            //     this.is_invalid     = true
            //     this.transportation_cost_err  = true
            // } else {
            //     this.transportation_cost_err  = false
            // }

            if(this.currency_id == '') {
                this.is_invalid     = true
                this.currency_id_err  = true
            } else {
                this.currency_id_err  = false
            }


            this.incomeProducts.forEach((row, index) => {

                //check product_id
                if(row.product_id == '') {
                    this.is_invalid = true
                    this.product_id_err[index] = true
                } else {
                    this.product_id_err[index] = false
                }

                //check product price
                if(row.price == '') {
                    this.is_invalid = true
                    this.product_price_err[index] = true
                } else {
                    this.product_price_err[index] = false
                }

                //check product Count
                if(row.count == '') {
                    this.is_invalid = true
                    this.product_count_err[index] = true
                    // return this.err((index + 1) + ' Қатордаги махсулот миқдои мажбурий.')
                } else {
                    this.product_count_err[index] = false
                }

            });

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            let incomeDetails = {
                // contract: this.contract,
                products: this.incomeProducts,
                currency_id: this.currency_id
            }

            const res = await this.callApi('post', '/app/add_income_products', incomeDetails)

            if(res) {
                this.$router.push('inout')
            }

        },
        handleAdd () {
            this.index++;
            this.incomeProducts.push(
                {
                    product_id: '',
                    price: '',
                    count:''
                }
            );
        },
        handleRemove(index) {

            if(Object.keys(this.incomeProducts).length > 1){
                this.incomeProducts.splice(index, 1);
            } else {
                return this.err('Қаторни ўчираолмайсиз!')
            }
        }
    },

    async created(){
        const res = await this.callApi('get', '/app/get_products')
        const departments = await this.callApi('get', '/app/get_departments')
        const currencies = await this.callApi('get', '/app/get_currencies')

        this.departments = departments.data;
        this.currencies = currencies.data;

        if(res.status == 200) {
            this.products = res.data

        } else {
            this.swr()
        }
    }
}
</script>
