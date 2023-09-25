<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    <Button @click="addModal=true" class="ivu-mr"><Icon type="md-add" />Махсулот қўшиш</Button>
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <!-- TABLE TITLE -->
                        <thead>
                        <tr class="blue_thead bordered">
                            <th rowspan="2" class="_text_center align-top" width="20px"></th>
                            <th rowspan="2" class="_text_center" style="min-width: 300px">Махсулот Номи</th>
                            <th rowspan="2" class="_text_center">Коди</th>
                            <th rowspan="2" class="_text_center">Kategoriya</th>
                            <th rowspan="2" class="_text_center">Brend</th>
                            <th rowspan="2" class="_text_center">Давлат</th>
                            <th rowspan="2" class="_text_center">Бирлиги</th>
                            <th rowspan="2" class="_text_center">Тўп</th>
                            <th rowspan="2" class="_text_center">Миқдор</th>
                            <th colspan="2" class="_text_center">USD</th>
                            <th colspan="2" class="_text_center">UZS</th>
                            <th rowspan="2" class="_text_center">Минимал</th>
                            <th rowspan="2" class="text-right">Action</th>
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
                            v-for="(product, i) in products" :key="i"
                            v-if="products.length"
                            v-bind:class="{'red_bg' : product.min_count > product.count}"
                        >
                            <td width="20px">
                                <Avatar :src="product.picture" v-if="product.picture" />
                                <Icon type="ios-image" size="24" v-else />
                            </td>
                            <td>{{product.id}} {{product.name}}</td>
                            <td>{{product.code}}</td>
                            <td>
<!--                                <Tooltip placement="top">-->
<!--                                    Damas...-->
<!--                                    <template #content v-for="(catName, catId) in getProductCategories(2, true)"  :key="catId">-->
<!--                                        <p>sfsdf sdf</p>-->
<!--                                        <p>sfsdf sdf</p>-->
<!--                                        <div>-->
<!--                                            <p>sfsdf sdf</p>-->
<!--                                        </div>-->
<!--                                    </template>-->
<!--                                </Tooltip>-->
                            </td>
                            <td>{{product.brand}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.packageCount == null ? 0:product.packageCount}}</td>
                            <td>{{product.count == null ? 0:product.count}}</td>
                            <td><div v-if="product.currency_id == 1">{{Number((product.latest_price).toFixed(3))}}</div></td>
                            <td><div v-if="product.currency_id == 1">{{Number((product.latest_price * product.count).toFixed(3))}}</div></td>
                            <td><div v-if="product.currency_id == 2">{{Number((product.latest_price).toFixed(3))}}</div></td>
                            <td><div v-if="product.currency_id == 2">{{Number((product.latest_price * product.count).toFixed(3))}}</div></td>
                            <td>{{product.min_count}}</td>
                            <td class="text-right">
                                <Button type="info" size="small" @click="showEditModal(product, i)">
                                    <Icon type="md-create"></Icon>
                                </Button>
                                <Button type="error" size="small" @click="showDeletingModal(product, i)" :loading="product.isDeleting">
                                    <Icon type="md-close"></Icon>
                                </Button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add product modal -->
            <Modal
                v-model="addModal"
                title="Махсулот қўшиш"
                :mask-closable = "false"
                :closable = "false"
                width="768"
            >

                <Form ref="formValidate" :label-width="180">
                    <FormItem label="* Nomi" prop="productName" class="ivu-mb">
                        <Input v-model="data.name" placeholder="Nomi"></Input>
                    </FormItem>
                    <FormItem label="* Nomi rus" prop="productNameRu" class="ivu-mb">
                        <Input v-model="data.name_ru" placeholder="Nomi rus"></Input>
                    </FormItem>
                    <FormItem label="* Kategoriya" prop="productCategory" class="ivu-mb">
                        <Select v-model="data.category_id" placeholder="Kategoriya" multiple>
                            <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="* Birlik" prop="productMesurement" class="ivu-mb">
                        <Select v-model="data.measurement" placeholder="Birlik">
                            <Option v-for="measurement in measurements" :value="measurement.value" :key="measurement.value">{{ measurement.label }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="Kod" prop="productCode" class="ivu-mb">
                        <Input v-model="data.code" placeholder="Maxsulot kodi"></Input>
                    </FormItem>
                    <FormItem label="Davlat" prop="productCountry" class="ivu-mb">
                        <Select v-model="data.country_id" placeholder="Davlat">
                            <Option v-for="country in countries" :value="country.id" :key="country.id">{{ country.name }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="Brend" prop="productBrand" class="ivu-mb">
                        <Select v-model="data.brand_id" placeholder="Brend">
                            <Option v-for="brand in brands" :value="brand.id" :key="brand.id">{{ brand.name }}</Option>
                        </Select>
                    </FormItem>
                    <FormItem label="* Minimal miqdor" prop="productMinCount" class="ivu-mb">
                        <Input v-model="data.min_count" placeholder="Minimal miqdor"></Input>
                    </FormItem>
                    <FormItem label="Қўшимча маълумотлар" prop="productDescription">
                        <Input v-model="data.description" type="textarea" :autosize="{minRows: 3,maxRows: 5}" placeholder="Қўшимча маълумотлар..."></Input>
                    </FormItem>
                </Form>
                <template #footer>
                    <Button type="error" @click="addModal=false">Беркитиш</Button>
                    <Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Махсулот...':'Сақлаш'}}</Button>
                </template>
            </Modal>

            <!-- Edit tag modal -->
    <Modal
        v-model="editModal"
        title="Махсулотни тахрирлаш"
        :mask-closable="false"
        :closable = "false"
        :width = "768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" prop="name" class="ivu-mb">
                <Input v-model="editData.name" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Nomi rus" prop="name_ru" class="ivu-mb">
                <Input v-model="editData.name_ru" placeholder="Nomi rus"></Input>
            </FormItem>
            <FormItem label="* Kategoriya" prop="productCategory" class="ivu-mb">
                <Select v-model="editData.category_id" placeholder="Kategoriya" multiple>
                    <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Birlik" prop="measurement" class="ivu-mb">
                <Select v-model="editData.measurement" placeholder="Birlik">
                    <Option v-for="measurement in measurements" :value="measurement.value" :key="measurement.value" v-bind:value="measurement.value">{{ measurement.label }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Minimal miqdor" prop="min_count" class="ivu-mb">
                <Input v-model="editData.min_count" model-value="" placeholder="Minimal miqdor"></Input>
            </FormItem>
            <FormItem label="Kod" prop="code" class="ivu-mb">
                <Input v-model="editData.code" placeholder="Kod"></Input>
            </FormItem>
            <FormItem label="Davlat" prop="productCountry" class="ivu-mb">
                <Select v-model="editData.country_id" placeholder="Davlat">
                    <Option v-for="country in countries" :value="country.id" :key="country.id" v-bind:value="country.id">{{ country.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="Brend" prop="productBrand" class="ivu-mb">
                <Select v-model="editData.brand_id" placeholder="Brend">
                    <Option v-for="brand in brands" :value="brand.id" :key="brand.id" v-bind:value="brand.id">{{ brand.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="Qo'shimcha ma'lumotlar" prop="description" class="ivu-mb">
                <Input v-model="editData.description" type="textarea" :autosize="{minRows: 3,maxRows: 5}" placeholder="Qo'shimcha ma'lumotlar..."></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="default" size="small" @click="editModalClose()">Close</Button>
            <Button type="primary" size="small" @click="editProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Сақлаш...':'Сақлаш'}}</Button>
        </template>
    </Modal>

    <!-- Delete tag modal -->
    <Modal v-model="showDeleteModal" width="360">
        <template #header>
            <p style="color:#f60;text-align:center">
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
    name: "rawMaterials",
    components: {
        StockHeader:StockHeader
    },
    data(){
        return {
            data: {
                name: '',
                name_ru: '',
                code: '',
                category_id: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                currency_type: 0,
                min_count: 0
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            products: [],
            counter : 1,
            editData: {
                name: '',
                name_ru: '',
                code: '',
                category_id: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                currency_type: 0,
                min_count: 0
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            product: {},
            deletingIndex: -1,
            avatarImage: '',
            measurements: [
                {
                    value: 'м',
                    label: 'Метр'
                },
                {
                    value: 'дона',
                    label: 'Дона'
                },
                {
                    value: 'кг',
                    label: 'Килограмм'
                },
            ],
            categories: [],
            countries: [],
            brands: [],
            productCategoriesName: [],
        }
    },
    computed: {
        // getCategoryNames(product_id){
        //     let categoryNames = this.getProductCategories(product_id, true)
        //     return categoryNames
        // }
    },
    methods : {
        async addProduct(){ //Add product

            if(this.data.name.trim() == '')     return this.err('Maxsulot nomi majburiy.')
            if(this.data.name_ru.trim() == '')  return this.err('Maxsulot ru nomi majburiy.')
            if(this.data.category_id == '')      return this.err('Kategoriya majburiy.')
            if(this.data.country_id == '')      return this.err('Davlat majburiy.')
            if(this.data.brand_id == '')        return this.err('Brend majburiy.')
            if(this.data.measurement == '')     return this.err("O'lchov birligi majburiy.")


            const res = await this.callApi('post', '/app/add_product', this.data)

            if(res.status === 201) {
                this.products.unshift(res.data);
                this.s('Махсулот мувоффақиятли қўшилди!')
                this.addModal = false
                this.data.name = ''
                this.data.name_ru = ''
                this.data.code = ''
                this.data.country_id = ''
                this.data.brand_id = ''
                this.data.measurement = ''
                this.data.picture = ''
                this.data.description = ''
                this.data.min_count = 0
                this.data.category_id = []
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

        async editProduct(){ //edit product
            if(this.editData.name.trim() === '')     return this.err('Махсулот номи мажбурий.')
            if(this.editData.name_ru.trim() === '')  return this.err('Махсулот номи мажбурий.')
            if(this.editData.category_id === '')      return this.err('Kategoriya majburiy.')
            if(this.editData.country_id === '')      return this.err('Davlat majburiy.')
            if(this.editData.brand_id === '')        return this.err('Brend majburiy.')
            if(this.editData.measurement === '')     return this.err("O'lchov birligi majburiy.")

            const res = await this.callApi('post', '/app/edit_product', this.editData)

            if(res.status === 200) {
                this.products[this.index].name       = res.data.name
                this.products[this.index].name_ru    = res.data.name_ru
                this.products[this.index].code       = res.data.code
                this.products[this.index].category_id = res.data.category_id
                this.products[this.index].country_id = res.data.country_id
                this.products[this.index].brand_id    = res.data.brand_id
                this.products[this.index].measurement = res.data.measurement
                this.products[this.index].min_count   = res.data.min_count

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

        async showEditModal(product, index) {
            const categories = await this.callApi('get', '/app/get_product_categories/' + product.id +"/"+0)
            let productCategories = []
            categories.data.forEach((value, index) => {
                productCategories.push(value);
            })

            let productObj = {
                id:             product.id,
                name:           product.name,
                name_ru:        product.name_ru,
                code:           product.code,
                category_id:    productCategories,
                country_id:     product.country_id,
                brand_id:       product.brand_id,
                measurement:    product.measurement,
                picture:        product.picture,
                description:    product.description,
                min_count:      product.min_count
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
            productCategories = []
        },
        editModalClose(){
            // this.productCategories = []
            this.editModal = false
        },

        async deleteProduct() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_product", this.product)
            if(res.status === 200) {
                this.products.splice(this.deletingIndex,1)
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
        getCountryName(country_id){
            var country_name = '';
            this.countries.forEach((country, ind) => {
                if(country.id == country_id) {
                    country_name = country.name
                    return false;
                }
            })

            return country_name
        },
        async getProductCategories(product_id, names){
            const categories = await this.callApi('get', '/app/get_product_categories/'+product_id+'/'+names)
            this.productCategoriesName  = categories.data
        },
        async getCountries(){
            const countries = await this.callApi('get', '/app/get_countries')
            this.countries = countries.data
        },
        async getBrands(){
            const brands = await this.callApi('get', '/app/get_brands')
            this.brands = brands.data
        },
        async getCategories(){
            const categories = await this.callApi('get', '/app/get_categories')
            this.categories = categories.data
        }
    },

    async created(){
        const products = await this.callApi('get', '/app/get_products')

        if(products.status == 200) {
            this.products = products.data
        } else if(products.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }

        this.getCountries()
        this.getBrands()
        this.getCategories()

    },
}
</script>

<style scoped>

</style>
