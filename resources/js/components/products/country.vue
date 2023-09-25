<template>
    <ProductsHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    <Button @click="addModal=true" type="primary" class="ivu-mr"><Icon type="md-add" />Davlat q'shish</Button>
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th>Nomi</th>
                            <th>Nomi rus</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="countries.length">
                                <tr v-for="(country, i) in countries" :key="i">
                                    <td>{{country.name}}</td>
                                    <td>{{country.name_ru}}</td>
                                    <td class="text-right">
                                        <ButtonGroup>
                                            <Button type="primary" title="Taxrirlash" @click="showEditModal(country, i)"><Icon type="md-create"></Icon></Button>
                                            <Button type="error" title="O'chirish" @click="showDeletingModal(country, i)" :loading="country.isDeleting"><Icon type="md-close"></Icon></Button>
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
        title="Davlat Qo'shish"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="data.name" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Nomi Rus" class="ivu-mb">
                <Input v-model="data.name_ru" placeholder="Nomi Rus"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addCountry" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Brend...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit product modal -->
    <Modal
        v-model="editModal"
        title="Davlatni taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editData.name" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="* Nomi Rus" class="ivu-mb">
                <Input v-model="editData.name_ru" placeholder="Nomi Rus"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editCountry()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Maxsulot...':'Saqlash'}}</Button>
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCountry">ХА</Button>
        </template>
    </Modal>
</template>

<script>
import ProductsHeader from './parts/header.vue'
export default {
    name: "country",
    components: {
        ProductsHeader:ProductsHeader
    },
    data(){
        return {
            data: {
                id: 0,
                name: '',
                name_ru: '',
            },
            addModal : false,
            editModal: false,
            isAdding : false,
            countries: [],
            country: {},
            editData: {
                id: 0,
                name: '',
                name_ru: '',
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deletingIndex: -1,
        }
    },
    methods : {
        async addCountry(){ //Add country
            if(this.data.name === '')      return this.err('Nomi majburiy.')
            if(this.data.name_ru === '')      return this.err('Nomi Rus majburiy.')

            const res = await this.callApi('post', '/app/add_country', this.data)

            if(res.status === 201) {
                this.countries.unshift(res.data);
                this.s('Davlat muvoffaqiyatli qo\'shildi!')
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

        async editCountry(){ //edit product
            if(this.editData.name === '')     return this.err("Nomi majburiy.")
            if(this.editData.name_ru === '')     return this.err("Nomi Rus majburiy.")

            const res = await this.callApi('post', '/app/edit_country', this.editData)

            if(res.status === 200) {
                this.countries[this.index].name      = res.data.name
                this.countries[this.index].name_ru   = res.data.name_ru

                this.s("Davlat o'zgarishlari muvaffaqiyatli saqlandi!")
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

        async showEditModal(country, index) {
            let productObj = {
                id:     country.id,
                name:   country.name,
                name_ru:   country.name_ru,
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
        },
        editModalClose(){
            this.editModal = false
        },

        async deleteCountry() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_country", this.country)

            console.log(res)

            if(res.status === 200) {
                this.countries.splice(this.deletingIndex,1)
                this.s("Davlar muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(country, i) {
            console.log(country)
            this.country            = country
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){
        const countries = await this.callApi('get', '/app/get_countries')

        if(countries.status == 200) {
            this.countries = countries.data
        } else if(countries.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }
    },
}
</script>

<style scoped>

</style>
