<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{ name: 'income'}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Орқога қайтиш </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button type="primary" :long="true" @click="addProducts('incomeProducts')">Сақлаш</Button>
                    </Col>
                </Row>
                <Divider />
                <h3><Icon type="md-cloud-download"  class="text-primary" /> Кирим</h3>
                <br>
                <Form ref="incomeProducts" label-position="left">

                    <p class="_title0">
                        <h4>Махсулотлар</h4>
                    </p>
                    <template v-for="(productField, index) in incomeProducts">
                        <Row style="border: 1px solid #ccc; margin-bottom: 5px; padding: 10px; background-color: #eeeff3;">
                            <Col span="12" style="padding-right:10px;">
                                <FormItem label="* Махсулот номи" :key="index">
                                    <Select v-model="incomeProducts[index].product_id" filterable placeholder="Махсулотни танланг...">
                                        <Option v-for="product in products" :value="product.id" :key="product.id">{{ product.name + " (" + product.code + ", " + product.country + ", " + product.brand + ")" }}</Option>
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
import StockHeader from './parts/header.vue'
export default {
    name: "incomeAdd",
    components: {
        StockHeader: StockHeader
    },
    data(){
        return {
            index: 0,
            incomeProducts: [
                {
                    product_id: '',
                    price: 0,
                    count: 0
                }
            ],
            products: [],
            currency_id_err:   false,

            product_id_err:         [false],
            product_price_err:      [false],
            product_count_err:      [false],
            is_invalid:             false,
        }
    },
    methods: {
        async addProducts () {

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
                } else {
                    this.product_count_err[index] = false
                }
            });

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Barcha kataklarni to\'ldirish majburiy!')
            }

            let incomeDetails = {
                products: this.incomeProducts,
            }

            const res = await this.callApi('post', '/app/add_income_products', incomeDetails)

            if(res) {
                this.$router.push('/stock/income')
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
                return this.err('Qatorni o\'chiraolmaysiz!')
            }
        }
    },

    async created(){
        const res = await this.callApi('get', '/app/get_products')

        if(res.status == 200) {
            this.products = res.data

        } else {
            this.swr()
        }
    }
}
</script>
