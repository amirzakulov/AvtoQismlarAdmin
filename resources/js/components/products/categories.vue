<template>
    <ProductsHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    <Button type="primary" @click="addModal=true" class="ivu-mr"><Icon type="md-add" />Kategoriya q'shish</Button>
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th class="_text_center align-top" width="20px"></th>
                            <th></th>
                            <th>Nomi</th>
                            <th>Tartib</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="categories.length">
                                <tr v-for="(category, i) in categories" :key="i">
                                    <td>{{category.id}}</td>
                                    <td width="20px">
                                        <Image v-if="category.picture" :src="`/uploads/categories/${category.picture}`" fit="contain" width="50px" height="50px" />
                                    </td>
                                    <td>{{category.name}}</td>
                                    <td>{{category.sort}}</td>
                                    <td class="text-right">
                                        <ButtonGroup>
                                            <Button type="primary" title="Taxrirlash" @click="showEditModal(category, i)"><Icon type="md-create"></Icon></Button>
                                            <Button type="error" title="O'chirish" @click="showDeletingModal(category, i)" :loading="category.isDeleting"><Icon type="md-close"></Icon></Button>
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
        title="Kategoriya Qo'shish"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="data.name" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="Tartib" class="ivu-mb">
                <Input v-model="data.sort" placeholder="Tartib"></Input>
            </FormItem>
            <FormItem label="Rasm" >
                <Upload type="drag" action="/app/category_image_upload"
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
            <div v-if="editData.picture" style="margin-left: 180px;">
                <Image :src="`/uploads/categories/${editData.picture}`" fit="contain" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Kategoriya...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit product modal -->
    <Modal
        v-model="editModal"
        title="Kategoriyani taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editData.name" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Tartib" class="ivu-mb">
                <Input v-model="editData.sort" placeholder="Tartib"></Input>
            </FormItem>
            <FormItem label="Rasm" >
                <Upload type="drag" action="/app/category_image_upload"
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
                <Image :src="`/uploads/categories/${editData.picture}`" fit="contain" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editCategory()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCategory">ХА</Button>
        </template>
    </Modal>
</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "categories",
    components: {
        ProductsHeader:ProductsHeader
    },
    data(){
        return {
            data: {
                name: '',
                picture: '',
                sort: '',
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            categories: [],
            category: {},
            editData: {
                id: 0,
                name: '',
                picture: '',
                sort: '',
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deletingIndex: -1,
            token: '',
        }
    },
    methods : {
        async addCategory(){ //Add category
            if(this.data.name === '')      return this.err('Nomi majburiy.')

            const res = await this.callApi('post', '/app/add_category', this.data)

            if(res.status === 201) {
                this.categories.unshift(res.data);
                this.s('Maxsulot muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.data.name = ''
                this.data.sort = ''
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

        async editCategory(){ //edit product
            if(this.editData.name === '')     return this.err("Nomi majburiy.")

            const res = await this.callApi('post', '/app/edit_category', this.editData)

            if(res.status === 200) {
                this.categories[this.index].name      = res.data.name
                this.categories[this.index].picture   = res.data.picture
                this.categories[this.index].sort      = res.data.sort

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

        async showEditModal(category, index) {
            let productObj = {
                id:     category.id,
                name:   category.name,
                picture:category.picture,
                sort:   category.sort,
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
        },
        editModalClose(){
            this.editModal = false
        },

        async deleteCategory() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_category", this.category)
            if(res.status === 200) {
                this.categories.splice(this.deletingIndex,1)
                this.s('Kategoriya muvaffaqiyatli o\'chirildi!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(category, i){
            this.category            = category
            this.deletingIndex      = i
            this.showDeleteModal    = true
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
    },

    async created(){
        this.token = window.Laravel.csrfToken
        const categories = await this.callApi('get', '/app/get_categories')

        if(categories.status == 200) {
            this.categories = categories.data
        } else if(categories.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }
    },
}
</script>

<style scoped>

</style>
