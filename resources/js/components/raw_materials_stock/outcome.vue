<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <Row border="bottom">
                    <Col span="3">
                        <Button :to="{ name: 'inout', params: {inout:'inout'}}" type="success" class="text-white"><Icon type="ios-arrow-back" /> Орқога қайтиш </Button>
                    </Col>
                    <Col span="3" offset="18">
                        <Button type="primary" :long="true" @click="addProducts('incomeProducts')">Сақлаш</Button>
                    </Col>
                </Row>
                <Divider />
                <h3><Icon type="md-cloud-upload"  class="text-danger" /> Чиқим</h3>
                <br>
                <Form ref="incomeProducts" label-position="left">
                    <p class="_title0">
                        <h4>Асосий маълумотлар</h4>
                    </p>
                    <Row style="border: 1px solid #ccc; margin-bottom: 50px; padding: 10px; background-color: #eeeff3;">
                        <Col span="6" style="padding-right:10px;">
                            <FormItem label="* Кимга">
                                <Select v-model="contract.employee_id" placeholder="Етказиб берувчини танланг...">
                                    <Option v-for="employee in employees" :value="employee.id" :key="employee.id">{{ employee.name }}</Option>
                                </Select>
                                <Text :class="{ 'd-none': !employee_id_err }" type="danger">Тўлдириш мажбурий!</Text>
                            </FormItem>
                        </Col>
                        <Col span="6" style="padding-right:10px">
                            <FormItem label="* Қайси бўлимга">
                                <Select v-model="contract.to_department_id" placeholder="Қабул қилувчини танланг...">
                                    <template v-for="department in departments" :key="department.id">
                                        <Option v-if="department.id != 2" :value="department.id">{{ department.name }}</Option>
                                    </template>
                                </Select>
                                <Text :class="{ 'd-none': !to_department_id_err }" type="danger">Тўлдириш мажбурий!</Text>
                            </FormItem>
                        </Col>
                    </Row>

                    <p class="_title0">
                        <h4>Махсулотлар</h4>
                    </p>

                    <template v-for="(productField, index) in outcomeProducts">
                        <input type="hidden" v-model="outcomeProducts[index].currency">
                        <Row style="border: 1px solid #ccc; margin-bottom: 5px; padding: 10px; background-color: #eeeff3;">
                            <Col span="6" style="padding-right:10px;">
                                <FormItem label="* Махсулот номи" :key="index">
                                    <Select v-model="outcomeProducts[index].raw_material_id" @on-change="productChanged(outcomeProducts[index].raw_material_id, index)" filterable placeholder="Махсулотни танланг...">
                                        <Option v-for="product in raw_materials" :value="product.raw_material_id" :key="product.id">{{ product.name + " (" + product.country + ", " + product.created_at + " )" }}</Option>
                                    </Select>
                                    <Text :class="{ 'd-none': !raw_material_id_err[index].display }" type="danger">{{raw_material_id_err[index].message}}</Text>
                                </FormItem>
                            </Col>
                            <Col span="6" style="padding-right:10px">
                                <FormItem label="* Тўп">
                                    <Input v-model="outcomeProducts[index].packageCount" placeholder="Махсулот Тўп"
                                   @on-change="packageCountChanged(index)"></Input>
                                    <Paragraph :class="{ 'd-none': !selectedProduct[index].packageCount }" class="m-0 p-0">Омборда: {{selectedProduct[index].packageCount}}</Paragraph>
                                    <Text :class="{ 'd-none': !product_packageCount_err[index].display }" type="danger" class="m-0 p-0">{{product_packageCount_err[index].message}}</Text>
                                </FormItem>
                            </Col>
                            <Col span="5" style="padding-right:10px">
                                <FormItem label="* Миқдор" class="mb-0">
                                    <Input v-model="outcomeProducts[index].count" placeholder="Махсулот Миқдор"
                                    @on-change="countChanged(index)"></Input>
                                    <Paragraph :class="{ 'd-none': !selectedProduct[index].count }" class="m-0 p-0">Омборда: {{selectedProduct[index].count}}</Paragraph>
                                    <Text :class="{ 'd-none': !product_count_err[index].display }" type="danger" class="m-0 p-0">{{product_count_err[index].message}}</Text>
                                </FormItem>
                            </Col>
                            <Col span="1" style="padding-top:40px; padding-left: 8px;">
                                <a href="javascript:void(0);">
                                    <Icon type="md-remove-circle" size="20" color="#dc3545" @click="handleRemove(index)" />
                                </a>
                            </Col>
                        </Row>
                    </template>

                    <FormItem>
                        <Row>
                            <Col span="3" offset="21">
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
            contract: {
                employee_id: '',
                to_department_id: '',
            },
            employees: [
                {name: 'Адхамжон', id: 3},
                {name: 'Каримжон', id: 4},
            ],
            index: 0,
            outcomeProducts: [
                {
                    raw_material_id: '',
                    packageCount: '',
                    count: '',
                    currency: 0
                }
            ],
            raw_materials: [],

            to_department_id_err:   false,
            employee_id_err:   false,

            raw_material_id_err:      [{display:false, message: "Тўлдириш мажбурий!"}],
            product_packageCount_err: [{display:false, message: "Тўлдириш мажбурий!"}],
            product_count_err:        [{display:false, message: "Тўлдириш мажбурий!"}],
            is_invalid:               false,
            departments:              [],
            selectedProduct:          [
                                            {
                                                count: 0,
                                                packageCount: 0
                                            }
                                      ],
        }
    },
    methods: {
        async addProducts () {

            if(this.contract.employee_id == '') {
                this.is_invalid         = true
                this.employee_id_err    = true
            } else {
                this.employee_id_err    = false
            }

            if(this.contract.to_department_id == '') {
                this.is_invalid     = true
                this.to_department_id_err  = true
            } else {
                this.to_department_id_err  = false
            }

            this.outcomeProducts.forEach((row, index) => {

                //check raw_material_id
                if(row.raw_material_id == '') {
                    this.is_invalid = true
                    this.raw_material_id_err[index].display = true
                } else {
                    this.raw_material_id_err[index].display = false
                }

                //check product packageCount
                if(row.packageCount == '') {
                    this.is_invalid = true
                    this.product_packageCount_err[index].display = true
                } else {
                    this.product_packageCount_err[index].display = false
                }


                //check product packegeCount
                if(row.count == '') {
                    this.is_invalid = true
                    this.product_count_err[index].display = true
                } else {
                    this.product_count_err[index].display = false
                }

            });

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err('Барча катакларни тўлдириш мажбурий!')
            }

            let outcomeDetails = {
                contract: this.contract,
                products: this.outcomeProducts,
            }

            const res = await this.callApi('post', '/app/add_outcome_products', outcomeDetails)

            if(res) {
                this.$router.push('inout')
            }

        },
        handleAdd () {
            this.index++;
            this.outcomeProducts.push(
                {
                    raw_material_id: '',
                    packageCount: '',
                    count:''
                }
            );

            this.selectedProduct.push(
                {
                    count: 0,
                    packageCount: 0
                }
            );
            this.raw_material_id_err.push({display:false, message: "Тўлдириш мажбурий!"})
            this.product_packageCount_err.push({display:false, message: "Тўлдириш мажбурий!"})
            this.product_count_err.push({display:false, message: "Тўлдириш мажбурий!"})
        },
        handleRemove(index) {
            if(Object.keys(this.outcomeProducts).length > 1){
                this.outcomeProducts.splice(index, 1);
                this.selectedProduct.splice(index, 1);
            } else {
                return this.err('Қаторни ўчираолмайсиз!')
            }
        },

        async productChanged(raw_material_id, rowIndex) {
            const raw_material = await this.callApi('get', '/app/get_raw_material/'+raw_material_id)
            this.selectedProduct[rowIndex].count = raw_material.data.count
            this.selectedProduct[rowIndex].packageCount = raw_material.data.packageCount

        },

        async countChanged(rowIndex){
            let count       = this.outcomeProducts[rowIndex].count
            let stockCount  = this.selectedProduct[rowIndex].count
            if(count > stockCount) {
                this.product_count_err[rowIndex].display = true
                this.product_count_err[rowIndex].message = "Omborda buncha maxsulot yuq"
            } else {
                this.product_count_err[rowIndex].display = false
                this.product_count_err[rowIndex].message = ""
            }
        },

        async packageCountChanged(rowIndex){
            let count       = this.outcomeProducts[rowIndex].packageCount
            let stockCount  = this.selectedProduct[rowIndex].packageCount
            if(count > stockCount) {
                this.product_packageCount_err[rowIndex].display = true
                this.product_packageCount_err[rowIndex].message = "Omborda buncha maxsulot yuq"
            } else {
                this.product_packageCount_err[rowIndex].display = false
                this.product_packageCount_err[rowIndex].message = ""
            }
        }
    },

    async created(){
        const raw_materials = await this.callApi('get', '/app/get_raw_materials')
        const departments = await this.callApi('get', '/app/get_departments')
        const currencies = await this.callApi('get', '/app/get_currencies')

        this.departments    = departments.data;
        this.currencies     = currencies.data;

        if(raw_materials.status == 200) {
            this.raw_materials = raw_materials.data

        } else {
            this.swr()
        }
    }
}
</script>
