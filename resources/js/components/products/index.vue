<template>
    <ProductsHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <Row :gutter="8" border="bottom" class="mb-3">
                    <Col span="3">
                        <Button @click="addModal=true" class="ivu-mr"><Icon type="md-add" />Махсулот қўшиш</Button>
                    </Col>
                    <Col span="2" offset="7"></Col>
                    <Col span="8">
                        <Input v-model="keywordName" placeholder="Махсулот номи..." />
                    </Col>
                    <Col span="4">
                        <Input v-model="keywordCode" placeholder="Махсулот коди..." />
                    </Col>
                </Row>


                <div class="_overflow _table_div">
                    <Table size="small" :columns="columns" :data="filteredProducts" :loading="loading" border>
                        <template #picture="{ row }">
                            <Image v-if="row.picture" :src="`/uploads/products/${row.picture}`"
                                   class="cursor-pointer"
                                   @click="showMultiple(row)"
                                   fit="contain" width="50px" height="50px"
                            />
                            <Icon type="ios-image" size="24" v-else />

                            <vue-easy-lightbox
                                escDisabled
                                moveDisabled
                                :visible="visible"
                                :imgs="imageUrl"
                                :index="imageIndex"
                                @hide="handleHide"
                            ></vue-easy-lightbox>
                        </template>
                        <template #guarantee="{row}">
                            {{row.guarantee == 1 ? "Bor":"Yo'q"}}
                        </template>
<!--                        <template #categories="{row}">-->
<!--                            {{categoryToString(row.categories)}}-->
<!--                        </template>-->
                        <template #action="{row, index}">
                            <ButtonGroup>
                                <Button type="info" size="small" title="Rasmlar"  @click="showProductImagesModal(row, index)"><Icon type="md-images" /></Button>
                                <Button type="warning" size="small" title="Kategoriyalarni taxrirlash" @click="showEditCategoryModal(row, index)"><Icon type="logo-buffer"></Icon></Button>
                                <Button type="success" size="small" title="Kopiya" @click="duplicateModal(row, index)"><Icon type="md-copy"></Icon></Button>
                            </ButtonGroup>
                            <br />
                            <ButtonGroup class="mt-2">
                                <Button type="primary" size="small" title="Taxrirlash" @click="showEditModal(row, index)"><Icon type="md-create"></Icon></Button>
                                <Button type="error" size="small" title="O'chirish" @click="showDeletingModal(row, index)" :loading="row.isDeleting"><Icon type="md-close"></Icon></Button>
                            </ButtonGroup>
                        </template>
                    </Table>
                    <table class="_table d-none">
                        <!-- TABLE TITLE -->
                        <thead>
                        <tr class="blue_thead bordered">
                            <th class="_text_center align-top" style="width: 20px;"></th>
                            <th class="_text_center">ID</th>
                            <th class="_text_center" style="width: 100px; word-wrap: break-word;">Nomi</th>
                            <th class="_text_center" style="width: 100px; word-wrap: break-word;">Nomi Rus</th>
                            <th class="_text_center">Kodi</th>
                            <th class="_text_center">Brend</th>
                            <th class="_text_center">Davlat</th>
                            <th class="_text_center">Birligi</th>
                            <th class="_text_center">Tan Narx</th>
                            <th class="_text_center">Sotuv Narx</th>
                            <th class="_text_center">Chegirma</th>
                            <th class="_text_center">Kafolati bor</th>
                            <th class="_text_center">Kategoriya</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="filteredProducts.length">
                                <tr v-for="(product, i) in filteredProducts" :key="i">
                                    <td width="20px" :title="product.picture">
                                        <Image v-if="product.picture" :src="`/uploads/products/${product.picture}`"
                                        class="cursor-pointer"
                                        @click="showMultiple(product)"
                                        fit="contain" width="50px" height="50px"
                                        />
                                        <Icon type="ios-image" size="24" v-else />

                                        <vue-easy-lightbox
                                            escDisabled
                                            moveDisabled
                                            :visible="visible"
                                            :imgs="imageUrl"
                                            :index="imageIndex"
                                            @hide="handleHide"
                                        ></vue-easy-lightbox>
                                    </td>
                                    <td>{{product.id}}</td>
                                    <td style="width: 100px; word-wrap: break-word;">{{product.name}}</td>
                                    <td style="width: 100px; word-wrap: break-word;">{{product.name_ru}}</td>
                                    <td>{{product.code}}</td>
                                    <td>{{product.brand}}</td>
                                    <td>{{product.country}}</td>
                                    <td>{{product.measurement}}</td>
                                    <td><div>{{product.price}}</div></td>
                                    <td><div>{{product.sell_price}}</div></td>
                                    <td>{{product.discount}}</td>
                                    <td>{{product.guarantee == 1 ? "Bor":"Yo'q"}}</td>
                                    <td class="small" v-html="categoryToString(product.categories)"></td>
                                    <td class="text-right">
                                        <ButtonGroup>
                                            <Button type="info" size="small" title="Rasmlar"  @click="showProductImagesModal(product, i)"><Icon type="md-images" /></Button>
                                            <Button type="warning" size="small" title="Kategoriyalarni taxrirlash" @click="showEditCategoryModal(product, i)"><Icon type="logo-buffer"></Icon></Button>
                                            <Button type="success" size="small" title="Kopiya" @click="duplicateModal(product)"><Icon type="md-copy"></Icon></Button>
                                        </ButtonGroup>
                                        <br />
                                        <ButtonGroup class="mt-2">
                                            <Button type="primary" size="small" title="Taxrirlash" @click="showEditModal(product, i)"><Icon type="md-create"></Icon></Button>
                                            <Button type="error" size="small" title="O'chirish" @click="showDeletingModal(product, i)" :loading="product.isDeleting"><Icon type="md-close"></Icon></Button>
                                        </ButtonGroup>
                                    </td>
                                </tr>
                            </template>
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
            <FormItem label="Kafolati bor">
                <Checkbox v-model="data.guarantee">Kafolati bor</Checkbox>
            </FormItem>
            <FormItem label="* Maxsulot" class="ivu-mb">
                <Select v-model="data.product_type_id"
                        @on-change="productTypeChanged(data.product_type_id)"
                        placeholder="Maxsulotni tanlang" filterable>
                    <Option value="0" key="0">-- Maxsulotni tanlang --</Option>
                    <Option v-for="ptype in product_types" :value="ptype.id" :key="ptype.id">{{ ptype.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="data.name"
                       :disabled="nameDisabled"
                       placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Nomi rus" class="ivu-mb">
                <Input v-model="data.name_ru"
                       :disabled="nameDisabled"
                       placeholder="Nomi rus"></Input>
            </FormItem>
            <FormItem label="* Birlik" class="ivu-mb">
                <Select v-model="data.measurement" placeholder="Birlik">
                    <Option v-for="measurement in measurements" :value="measurement.value" :key="measurement.value">{{ measurement.label }}</Option>
                </Select>
            </FormItem>
            <FormItem label="Kod" class="ivu-mb">
                <Input v-model="data.code" placeholder="Maxsulot kodi"></Input>
            </FormItem>
            <FormItem label="* Kategoriya" class="ivu-mb">
                <Select v-model="data.categories" placeholder="Kategoriya" multiple>
                    <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Davlat" class="ivu-mb">
                <Select v-model="data.country_id" placeholder="Davlat">
                    <Option v-for="country in countries" :value="country.id" :key="country.id">{{ country.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Brend" class="ivu-mb">
                <Select v-model="data.brand_id" placeholder="Brend">
                    <Option v-for="brand in brands" :value="brand.id" :key="brand.id">{{ brand.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Tan Narx" class="ivu-mb">
                <Input v-model="data.price" placeholder="Tan Narx"></Input>
            </FormItem>
            <FormItem label="* Sotuv Narx" class="ivu-mb">
                <Input v-model="data.sell_price" placeholder="Sotuv Narx"></Input>
            </FormItem>
            <FormItem label="Chegirma" class="ivu-mb">
                <Input v-model="data.discount" placeholder="Chegirma"></Input>
            </FormItem>
            <FormItem label="Qo'shimcha ma'lumot" prop="productDescription" class="ivu-mb">
                <Input v-model="data.description" type="textarea" :autosize="{minRows: 3,maxRows: 5}" placeholder="Қўшимча маълумотлар..."></Input>
            </FormItem>
            <FormItem label="Rasm" prop="picture">
                <Upload type="drag" action="/app/product_image_upload"
                        ref="upload"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With': 'XMLHttpRequest'}"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :max-size="2048"
                >
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Rasm qo'yish uchun bu erga bosing</p>
                    </div>
                </Upload>
            </FormItem>
            <div v-if="data.picture" style="margin-left: 180px;">
                <Image :src="`/uploads/products/${data.picture}`" fit="cover" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Махсулот...':'Сақлаш'}}</Button>
        </template>
    </Modal>

    <!-- Edit product modal -->
    <Modal
        v-model="editModal"
        title="Maxsulotni taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="editData.product_type_id">
            <FormItem label="Kafolati bor">
                <Checkbox v-model="editData.guarantee">Kafolati bor</Checkbox>
            </FormItem>
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editData.name"
                       :disabled="nameDisabled"
                       placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Nomi rus" class="ivu-mb">
                <Input v-model="editData.name_ru"
                       :disabled="nameDisabled"
                       placeholder="Nomi rus"></Input>
            </FormItem>
            <FormItem label="* Birlik" class="ivu-mb">
                <Select v-model="editData.measurement" placeholder="Birlik">
                    <Option v-for="measurement in measurements" :value="measurement.value" :key="measurement.value">{{ measurement.label }}</Option>
                </Select>
            </FormItem>
            <FormItem label="Kod" class="ivu-mb">
                <Input v-model="editData.code" placeholder="Maxsulot kodi"></Input>
            </FormItem>
            <FormItem label="* Davlat" class="ivu-mb">
                <Select v-model="editData.country_id" placeholder="Davlat">
                    <Option v-for="country in countries" :value="country.id" :key="country.id">{{ country.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="* Brend" class="ivu-mb">
                <Select v-model="editData.brand_id" placeholder="Brend">
                    <Option v-for="brand in brands" :value="brand.id" :key="brand.id">{{ brand.name }}</Option>
                </Select>
            </FormItem>
            <FormItem label="Tan Narx" class="ivu-mb">
                <Input v-model="editData.price" placeholder="Tan narx"></Input>
            </FormItem>
            <FormItem label="Sotiv Narx" class="ivu-mb">
                <Input v-model="editData.sell_price" placeholder="Sotuv Narx"></Input>
            </FormItem>
            <FormItem label="Chegirma" class="ivu-mb">
                <Input v-model="editData.discount" placeholder="Chegirma"></Input>
            </FormItem>
            <FormItem label="Qo'shimcha ma'lumotlar" class="ivu-mb">
                <Input v-model="editData.description" type="textarea" :autosize="{minRows: 3,maxRows: 5}" placeholder="Qo'shimcha ma'lumotlar..."></Input>
            </FormItem>
            <FormItem label="Rasm">
                <Upload type="drag" action="/app/product_image_upload"
                        ref="upload"
                        :headers="{'x-csrf-token' : token, 'X-Requested-With': 'XMLHttpRequest'}"
                        :format="['jpg','jpeg','png']"
                        :on-format-error="handleFormatError"
                        :on-success="handleSuccessEdit"
                        :on-error="handleError"
                        :max-size="2048"
                >
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Rasm qo'yish uchun bu erga bosing</p>
                    </div>
                </Upload>
            </FormItem>
            <div v-if="editData.picture" style="margin-left: 180px;">
                <Image :src="`/uploads/products/${editData.picture}`" fit="cover" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editProduct()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit product category modal -->
    <Modal v-model="editCategoryModal" title="Maxsulotni taxrirlash" :mask-closable = "false" :closable = "false" width="768">

        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="editCategoryData.product_id">

            <FormItem label="* Kategoriya" class="ivu-mb">
                <Select v-model="editCategoryData.categories" placeholder="Kategoriya" multiple>
                    <Option v-for="category in categories" :value="category.id" :key="category.id">{{ category.name }}</Option>
                </Select>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editCategoryModal=false">Berkitish</Button>
            <Button type="primary" @click="editProductCategory()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
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

    <!-- show product images -->
    <Modal
        v-model="addProductImagesModal"
        title="Махсулот расмларини қўшиш"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate">
            <div class="demo-upload-list" v-for="(image,i) in imagesData" :key="i">
                <template v-if="image.picture != ''">
                    <Image :src="`/uploads/products/${image.picture}`" fit="contain" width="100%" height="100%" />
                    <div class="demo-upload-list-cover">
                        <!-- <Icon type="ios-eye-outline" @click="handleView(image.picture)"></Icon> -->
                        <Icon type="ios-trash-outline" @click="handleRemove(image, i)"></Icon>
                    </div>
                </template>
                <template v-else>
                    <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                </template>
            </div>
            <Upload
                type="drag"
                action="/app/product_images_upload"
                ref="upload"
                multiple
                :show-upload-list="false"
                :headers="{'x-csrf-token' : token, 'X-Requested-With': 'XMLHttpRequest'}"
                :format="['jpg','jpeg','png']"
                :on-success="handleSuccessImages"
                :on-format-error="handleFormatError"
                :on-error="handleError"
                :max-size="2048"
                style="display: inline-block;width:58px;"
                >
                <div style="width: 58px;height:58px;line-height: 58px;">
                    <Icon type="ios-camera" size="20"></Icon>
                </div>
            </Upload>
            <!-- <ImagePreview v-model="visible" :preview-list="['/uploads/products/'+ imgName]" /> -->
        </Form>
        <template #footer>
            <Button type="error" @click="addProductImagesModal=false">Беркитиш</Button>
            <Button type="primary" @click="addProductImages" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Махсулот...':'Сақлаш'}}</Button>
        </template>
    </Modal>
</template>

<script>
import ProductsHeader from './parts/header.vue'
import VueEasyLightbox from 'vue-easy-lightbox'
import { resolveComponent } from 'vue';

export default {
    name: "products",
    components: {
        ProductsHeader:ProductsHeader,
        VueEasyLightbox: VueEasyLightbox
    },
    computed: {
        filteredProducts(){
            let productsTmp = this.products

            if (this.keywordName != '' && this.keywordName) {
                productsTmp = productsTmp.filter((product) => {
                return product.name.toLowerCase().includes(this.keywordName.toLowerCase())
                })
            }

            if (this.keywordCode != '' && this.keywordCode) {
                productsTmp = productsTmp.filter((product) => {
                return product.code.toLowerCase().includes(this.keywordCode.toLowerCase())
                })
            }

            return productsTmp

        }
    },
    data(){
        return {
            loading: true,
            columns: [
                {
                    type: 'expand',
                    width: 50,
                    render: (h, { row: { price, categories } }) => {

                        return h('div', 'Tan narxi: '+price + '; ' + 'Kategoriya: '+this.categoryToString(categories))
                    }
                },
                {
                    title: "Rasm",
                    slot: 'picture',
                },
                {
                    title: "Nomi",
                    key: 'name',
                    resizable: true,
                    width: 180,
                    sortable: true,
                },
                {
                    title: "Nomi Rus",
                    key: 'name_ru',
                    resizable: true,
                    width: 180,
                    sortable: true,
                },
                {
                    title: "Kodi",
                    key: 'code',
                    sortable: true
                },
                {
                    title: "Brend",
                    key: 'brand'
                },
                {
                    title: "Davlat",
                    key: 'country'
                },
                {
                    title: "Birligi",
                    key: 'measurement'
                },
                // {
                //     title: "Tan Narx",
                //     key: 'price'
                // },
                {
                    title: "Sotuv Narx",
                    key: 'sell_price'
                },
                {
                    title: "Chegirma",
                    key: 'discount'
                },
                {
                    title: "Kafolat",
                    slot: 'guarantee'
                },
                // {
                //     title: "Kategoriya",
                //     slot: 'categories'
                // },
                {
                    title: "action",
                    slot: 'action',
                    width: 150,
                    align: 'right',
                },

            ],



            keywordName: '',
            keywordCode: '',
            visible: false,
            imageIndex: 0,
            imgName: '',
            data: {
                product_type_id: '',
                name: '',
                name_ru: '',
                code: '',
                categories: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                min_count: 0,
                price: 0,
                sell_price: 0,
                discount: 0,
                guarantee: 0,
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
                categories: [],
                country_id: '',
                brand_id: '',
                measurement: '',
                picture: '',
                price: 0,
                guarantee: false,
                sell_price: 0,
                discount: 0,
            },
            editCategoryData: {
                product_id: 0,
                categories: [],
            },
            editPriceData: {
                product_id: 0,
                price: 0,
                sell_price: 0,
                discount: 0,
            },
            imagesData: [],
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
            token: '',

            addProductImagesModal: false,
            imageUrl: [],


        }
    },
    methods : {
        async addProduct(){ //Add product

            if(this.data.product_type_id === '') {
                if(this.data.name.trim() === '')     return this.err('Maxsulot nomi majburiy.')
                if(this.data.name_ru.trim() === '')  return this.err('Maxsulot ru nomi majburiy.')
            }



            if(this.data.categories.length == 0 || this.data.categories.length === '')      return this.err('Kategoriya majburiy.')
            if(this.data.measurement === '')     return this.err("O'lchov birligi majburiy.")
            if(this.data.country_id === '')      return this.err('Davlat majburiy.')
            if(this.data.brand_id === '')        return this.err('Brend majburiy.')
            if(this.data.price === '')        return this.err('Narx majburiy.')
            if(this.data.sell_price === '')        return this.err('Sotuv narx majburiy.')

            const res = await this.callApi('post', '/app/add_product', this.data)

            if(res.status === 200) {

                this.products.unshift(res.data);
                this.s('Maxsulot muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.$refs.upload.clearFiles()

                this.data.product_type_id = ''
                this.data.name = ''
                this.data.name_ru = ''
                this.data.code = ''
                this.data.country_id = ''
                this.data.brand_id = ''
                this.data.measurement = ''
                this.data.picture = ''
                this.data.description = ''
                this.data.categories = []
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

            if(this.editData.measurement === '')     return this.err("O'lchov birligi majburiy.")
            if(this.editData.country_id === '')      return this.err('Davlat majburiy.')
            if(this.editData.brand_id === '')        return this.err('Brend majburiy.')
            if(this.editData.price === '')           return this.err('Narx majburiy.')
            if(this.editData.sell_price === '')      return this.err('Sotuv narx majburiy.')

            const res = await this.callApi('post', '/app/edit_product', this.editData)

            if(res.status === 200) {

                this.filteredProducts[this.index]          = res.data
                this.s('Махсулот ўзгаришлари мувоффақиятли сақланди!')
                this.editModal = false
                this.$refs.upload.clearFiles()
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
                name:               product.name,
                name_ru:            product.name_ru,
                code:               product.code,
                product_type_id:    product.product_type_id,
                country_id:         product.country_id,
                brand_id:           product.brand_id,
                measurement:        product.measurement,
                price:              product.price,
                sell_price:         product.sell_price,
                discount:           product.discount,
                guarantee:          product.guarantee == 1 ? true:false,
                picture:            product.picture,
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

            if(this.editCategoryData.categories.length == 0)      return this.err('Kategoriya majburiy.')

            const res = await this.callApi('post', '/app/edit_product_categories', this.editCategoryData)

            if(res.status === 200) {
                this.products[this.index].categories = res.data

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
                product_id:    product.id,
                categories:    productCategories,
            }

            this.editCategoryData = productObj
            this.editCategoryModal = true
            this.index = index
            productCategories = []
        },
        editCategoryModalClose(){
            this.editCategoryModal = false
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
        async getProductCategories(product_id){
            // this.productCategoriesName = []
            const product_categories = await this.callApi('get', '/app/get_product_categories/'+product_id)

            if(Object.keys(product_categories.data).length > 0) {
                product_categories.data.forEach((category, index) => {
                    this.productCategoriesName.push(category.category_name);
                })
            }

            this.productCategoriesName

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
        },

        // handleView (name) {
        //     this.imgName = name;
        //     this.visible = true;
        // },
        async handleRemove (image, i) {

            this.imagesData.splice(this.imagesData.indexOf(image), 1)
            const res = await this.callApi('post', '/app/delete_image', image)
        },
        handleSuccessImages (res, file) {
            this.imagesData.push({"picture":res})
        },
        handleSuccess (res, file) {
            this.data.picture = res
        },
        handleSuccessEdit (res, file) {
            this.editData.picture = res
        },
        handleError (res, file) {
            this.$Notice.warning({
                title: "Tanlangan faylning formati noto'g'ri",
                desc: 'Tanlangan faylning formati ' + file.name + ' noto\'g\'ri, iltimos jpg or png tanlang.'
            });
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: "Tanlangan faylning formati noto'g'ri",
                desc: 'Tanlangan faylning formati ' + file.name + ' noto\'g\'ri, iltimos jpg or png tanlang.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Fayl o\'lchami juda katta',
                desc: 'Fayl  ' + file.name + ' juda katta, rasm 2M dan kichik bo\'lishi kerak.'
            });
        },

        async showProductImagesModal(product, i){

            this.product = product
            const product_images = await this.callApi('get', '/app/get_product_images/'+this.product.id)

            this.imagesData = product_images.data

            this.addProductImagesModal = true
        },

        async addProductImages(){

            const images = {
                product_id: this.product.id,
                images: this.imagesData
            }

            const res = await this.callApi('post', '/app/add_product_images', images)
            if(res.status == 201) {
                this.$refs.upload.clearFiles()
                this.addProductImagesModal = false
            }
        },
        categoryToString(categories){

            let names = ''
            if(Object.keys(categories).length > 0) {
                categories.forEach((category, index) => {
                    names += category.category_name + ' '
                })
            }

            return names
        },
        async showPicturePreview(product){
            this.imageUrl = []
            const product_images = await this.callApi('get', '/app/get_product_images/'+product.id)

            this.imagesData = product_images.data

            if(this.imagesData.length > 0) {

                this.imagesData.forEach((image, inx) => {
                    this.imageUrl[inx] = 'uploads/products/'+image.picture
                })
            } else {
                this.imageUrl[0] = 'uploads/products/'+product.picture
            }

            this.$ImagePreview.show({
                    previewList: this.imageUrl,

                });
        },

        async showMultiple(product) {
            this.imageUrl = []
            const product_images = await this.callApi('get', '/app/get_product_images/'+product.id)

            this.imagesData = product_images.data

            if(this.imagesData.length > 0) {

                this.imagesData.forEach((image, inx) => {
                    this.imageUrl[inx] = 'uploads/products/'+image.picture
                })
            } else {
                this.imageUrl[0] = 'uploads/products/'+product.picture
            }
            this.imageIndex = 1 // index of imgList
            this.show()
        },
        show() {
            this.visible = true
        },
        handleHide() {
            this.visible = false
        },
        duplicateModal(product, i) {

            let productCategories = []
            product.categories.forEach((value, index) => {
                productCategories.push(value.category_id);
            })

            let productObj = {
                name:               product.name,
                name_ru:            product.name_ru,
                code:               product.code,
                categories:         productCategories,
                product_type_id:    product.product_type_id,
                country_id:         product.country_id,
                brand_id:           product.brand_id,
                measurement:        product.measurement,
                price:              product.price,
                sell_price:         product.sell_price,
                discount:           product.discount,
                guarantee:          product.guarantee == 1 ? true:false,
                picture:            product.picture,
                description:        product.description,
            }

            this.data           = productObj
            this.nameDisabled   = true
            this.addModal       = true
        }

    },

    async created(){
        this.token = window.Laravel.csrfToken
        const products = await this.callApi('get', '/app/get_products')

        if(products.status == 200) {
            this.products = products.data

            this.loading = false
        } else if(products.status == 403) {
            window.location = 'http://localhost/logout'
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

<style>
    .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
