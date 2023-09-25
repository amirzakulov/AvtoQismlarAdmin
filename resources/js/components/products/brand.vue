<template>
    <ProductsHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    <Button @click="addModal=true" type="primary" class="ivu-mr"><Icon type="md-add" />Brend q'shish</Button>
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th>Nomi</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(brand, i) in brands" :key="i" v-if="brands.length">
                            <td>{{brand.name}}</td>
                            <td class="text-right">
                                <ButtonGroup>
                                    <Button type="primary" title="Taxrirlash" @click="showEditModal(brand, i)"><Icon type="md-create"></Icon></Button>
                                    <Button type="error" title="O'chirish" @click="showDeletingModal(brand, i)" :loading="brand.isDeleting"><Icon type="md-close"></Icon></Button>
                                </ButtonGroup>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>


    <!-- Add product modal -->
    <Modal
        v-model="addModal"
        title="Brend Qo'shish"
        :mask-closable = "false"
        :closable = "false"
        width="768">

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="data.name" placeholder="Nomi"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addBrand" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Brend...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit product modal -->
    <Modal
        v-model="editModal"
        title="Brend taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editData.name" placeholder="Nomi"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editBrand()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteBrand">ХА</Button>
        </template>
    </Modal>
</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "brand",
    components: {
        ProductsHeader:ProductsHeader
    },
    data(){
        return {
            data: {
                id: 0,
                name: '',
            },
            addModal : false,
            editModal: false,
            isAdding : false,
            brands: [],
            brand: {},
            editData: {
                id: 0,
                name: '',
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deletingIndex: -1,
        }
    },
    methods : {
        async addBrand(){ //Add brand
            if(this.data.name === '')      return this.err('Nomi majburiy.')

            const res = await this.callApi('post', '/app/add_brand', this.data)

            if(res.status === 201) {
                this.brands.unshift(res.data);
                this.s('Maxsulot muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.data.name = ''
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

        async editBrand(){ //edit product
            if(this.editData.name === '')     return this.err("Nomi majburiy.")

            const res = await this.callApi('post', '/app/edit_brand', this.editData)

            if(res.status === 200) {
                this.brands[this.index].name      = res.data.name

                this.s("Brend o'zgarishlari muvaffaqiyatli saqlandi!")
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

        async showEditModal(brand, index) {
            let productObj = {
                id:     brand.id,
                name:   brand.name,
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
        },
        editModalClose(){
            this.editModal = false
        },

        async deleteBrand() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_brand", this.brand)

            if(res.status === 200) {
                this.brands.splice(this.deletingIndex,1)
                this.s("Brend muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(brand, i) {
            this.brand              = brand
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){
        const brands = await this.callApi('get', '/app/get_brands')

        if(brands.status == 200) {
            this.brands = brands.data
        } else if(brands.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }
    },
}
</script>

<style scoped>

</style>
