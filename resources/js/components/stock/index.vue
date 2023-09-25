<template>
    <ProductsHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th rowspan="2" class="_text_center align-top" width="20px"></th>
                            <th rowspan="2" class="_text_center" style="min-width: 300px">Nomi</th>
                            <th rowspan="2" class="_text_center">Kodi</th>
                            <th rowspan="2" class="_text_center">Kategoriya</th>
                            <th rowspan="2" class="_text_center">Brend</th>
                            <th rowspan="2" class="_text_center">Davlat</th>
                            <th rowspan="2" class="_text_center">Birligi</th>
                            <th rowspan="2" class="_text_center">Miqdor</th>
                            <th colspan="2" class="_text_center">USD</th>
                            <th rowspan="2" class="_text_center">Chegirma</th>
                            <th rowspan="2" class="_text_center">Минимал</th>
                            <th rowspan="2" class="text-right">Action</th>
                        </tr>
                        <tr class="blue_thead bordered">
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
                            <td width="20px">{{product.id}}
                                <Avatar :src="product.picture" v-if="product.picture" />
                                <Icon type="ios-image" size="24" v-else />
                            </td>
                            <td>{{product.name}}</td>
                            <td>{{product.code}}</td>
                            <td></td>
                            <td>{{product.brand}}</td>
                            <td>{{product.country}}</td>
                            <td>{{product.measurement}}</td>
                            <td>{{product.count == null ? 0:product.count}}</td>
                            <td><strong>{{product.price}}</strong></td>
                            <td><div>{{product.price * product.count}}</div></td>
                            <td>{{product.discount}}</td>
                            <td>{{product.min_count}}</td>
                            <td class="text-right">
                                <ButtonGroup>
                                    <Button type="info" title="Narxni taxrirlash" @click="showEditPriceModal(product,i)"><Icon type="logo-usd"></Icon></Button>
                                </ButtonGroup>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    <!-- Edit product price modal -->
    <Modal v-model="editPriceModal" title="Maxsulotni Narxini taxrirlash" :mask-closable = "false" :closable = "false" width="768">

        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="editPriceData.product_id">

            <FormItem label="* Narx" class="ivu-mb">
                <Input v-model="editPriceData.price" placeholder="Narx"></Input>
            </FormItem>
            <FormItem label="* Chegirma" class="ivu-mb">
                <Input v-model="editPriceData.discount" placeholder="Chegirma"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editPriceModal=false">Berkitish</Button>
            <Button type="primary" @click="editProductPrice()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
        </template>
    </Modal>

</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "products",
    components: {
        ProductsHeader:ProductsHeader
    },
    data(){
        return {
            data: {
                product_type_id: '',
                name: '',
                name_ru: '',
                code: '',
                category_id: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                min_count: 0,
                price: 0,
                discount: 0,
            },
            addModal : false,
            editModal : false,
            editCategoryModal : false,
            editPriceModal : false,
            isAdding : false,
            products: [],
            editData: {
                id: 0,
                product_type_id: '',
                name: '',
                name_ru: '',
                code: '',
                category_id: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                min_count: 0,
                price: 0,
                discount: 0,
            },
            editCategoryData: {
                product_id: 0,
                category_id: [],
            },
            editPriceData: {
                product_id: 0,
                price: 0,
                discount: 0,
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            product: {},
            deletingIndex: -1,
            avatarImage: '',
            measurements: [
                {
                    value: 'm',
                    label: 'Metr'
                },
                {
                    value: 'dona',
                    label: 'Dona'
                },
                {
                    value: 'kg',
                    label: 'Kilogramm'
                },
            ],
            categories: [],
            product_types: [],
            countries: [],
            brands: [],
            productCategoriesName: [],
            nameDisabled: false,
        }
    },
    methods : {
        async addProduct(){ //Add product

            if(this.data.product_type_id === '') {
                if(this.data.name.trim() === '')     return this.err('Maxsulot nomi majburiy.')
                if(this.data.name_ru.trim() === '')  return this.err('Maxsulot ru nomi majburiy.')
            }

            if(this.data.category_id === '')      return this.err('Kategoriya majburiy.')
            if(this.data.measurement === '')     return this.err("O'lchov birligi majburiy.")
            if(this.data.country_id === '')      return this.err('Davlat majburiy.')
            if(this.data.brand_id === '')        return this.err('Brend majburiy.')


            const res = await this.callApi('post', '/app/add_product', this.data)

            if(res.status === 200) {
                this.products.unshift(res.data);
                this.s('Maxsulot muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.data.product_type_id = ''
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
            if(this.editData.product_type_id === '') {
                if(this.editData.name.trim() === '')     return this.err('Maxsulot nomi majburiy.')
                if(this.editData.name_ru.trim() === '')  return this.err('Maxsulot ru nomi majburiy.')
            }

            // if(this.editData.category_id === '')      return this.err('Kategoriya majburiy.')
            if(this.editData.measurement === '')     return this.err("O'lchov birligi majburiy.")
            if(this.editData.country_id === '')      return this.err('Davlat majburiy.')
            if(this.editData.brand_id === '')        return this.err('Brend majburiy.')



            const res = await this.callApi('post', '/app/edit_product', this.editData)
            console.log(res)
            if(res.status === 200) {
                this.products[this.index].name       = res.data.name
                this.products[this.index].name_ru    = res.data.name_ru
                this.products[this.index].code       = res.data.code
                // this.products[this.index].category_id = res.data.category_id
                this.products[this.index].country_id = res.data.country_id
                this.products[this.index].country    = res.data.country
                this.products[this.index].brand_id    = res.data.brand_id
                this.products[this.index].brand       = res.data.brand
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
            let productObj = {
                id:                 product.id,
                product_type_id:    product.product_type_id,
                name:               product.name,
                name_ru:            product.name_ru,
                code:               product.code,
                country_id:         product.country_id,
                brand_id:           product.brand_id,
                measurement:        product.measurement,
                picture:            product.picture,
                min_count:          product.min_count,
                description:        product.description,
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
        },
        editModalClose(){
            this.editModal = false
        },

        async editProductCategory(){ //edit product
            if(this.editCategoryData.category_id === '')      return this.err('Kategoriya majburiy.')

            const res = await this.callApi('post', '/app/edit_product_categories', this.editCategoryData)

            if(res.status === 200) {
                this.products[this.index].category_id = res.data.category_id

                this.s('Maxsulot o\'zgarishlari muvaffaqiyatli saqlandi!')
                this.editCategoryModal = false
            } else {
                if(res.status == 422) {
                    if(res.editCategoryData.errors.name) {
                        this.err(res.editCategoryData.errors.name[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        async showEditCategoryModal(product, index) {
            const categories = await this.callApi('get', '/app/get_product_categories/' + product.id +"/"+0)
            let productCategories = []
            categories.data.forEach((value, index) => {
                productCategories.push(value);
            })

            let productObj = {
                product_id:     product.id,
                category_id:    productCategories,
            }

            this.editCategoryData = productObj
            this.editCategoryModal = true
            this.index = index
            productCategories = []
        },
        editCategoryModalClose(){
            this.editCategoryModal = false
        },


        async editProductPrice(){ //edit product
            if(this.editPriceData.price === '')      return this.err('Narx majburiy.')

            const res = await this.callApi('post', '/app/edit_product_price', this.editPriceData)

            if(res.status === 200) {
                this.products[this.index].price     = res.data.price
                this.products[this.index].discount  = res.data.discount

                this.s('Maxsulot o\'zgarishlari muvaffaqiyatli saqlandi!')
                this.editPriceModal = false
            } else {
                if(res.status == 422) {
                    if(res.editPriceData.errors.name) {
                        this.err(res.editPriceData.errors.name[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        async showEditPriceModal(product, index) {

            let productObj = {
                product_id: product.id,
                price:      product.price,
                discount:   product.discount,
            }

            this.editPriceData = productObj
            this.editPriceModal = true
            this.index = index
        },
        editPriceModalClose(){
            this.editPriceModal = false
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
        async getProductTypes(){
            const product_types = await this.callApi('get', '/app/get_product_types')
            this.product_types = product_types.data
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
        },
        productTypeChanged(productTypeId){
            if(productTypeId > 0) {
                this.nameDisabled = true
                this.data.name = ''
                this.data.name_ru = ''
            } else {
                this.nameDisabled = false
            }

        }
    },

    async created(){
        const products = await this.callApi('get', '/app/get_products')
        console.log(products.data)
        if(products.status == 200) {
            this.products = products.data

        } else {
            this.swr()
        }

        this.getProductTypes()
        this.getCountries()
        this.getBrands()
        this.getCategories()

    },
}
</script>

<style scoped>

</style>
