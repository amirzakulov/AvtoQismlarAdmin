<template>
    <StoreHeader />
    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
        <p class="_title0">
            <Button type="primary" @click="addModal=true" class="text-white"><Icon type="md-add-circle" class="mr-1" />Q'shish</Button>
        </p>

        <Row>
            <Col span="12">
                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="blue_thead bordered">
                            <th class="align-top">#ID</th>
                            <th>Nomi</th>
                            <th>Manzil</th>
                            <th>Telefon</th>
                            <th class="text-right">action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(store, i) in stores" :key="i" v-if="stores.length">
                            <td>{{store.id}}</td>
                            <td>{{store.name}}</td>
                            <td>{{store.address}}</td>
                            <td>{{store.phone}}</td>
                            <td class="text-right">
                                <ButtonGroup>
                                    <Button type="primary" size="small" title="Taxrirlash" @click="showEditModal(store, i)"><Icon type="md-create"></Icon></Button>
                                    <Button :disabled="store.id == 1" type="error" size="small" title="O'chirish" @click="showDeletingModal(store, i)" :loading="store.isDeleting"><Icon type="md-close"></Icon></Button>
                                </ButtonGroup>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </Col>
        </Row>
        
    </div>


    <!-- Add user modal -->
    <Modal
        v-model="addModal"
        title="Do\'kon turi qo'shish"
        :mask-closable = "false"
        :closable = "false"
        width="600"
    >

        <Form ref="formValidate" :label-width="150">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="data.name" placeholder="Nomi"></Input>
                <Text :class="{ 'd-none': !store_name_err }" type="danger">Tanlash majburiy!</Text>
            </FormItem>
            <FormItem label="Manzil" class="ivu-mb">
                <Input v-model="data.address" placeholder="Manzil"></Input>
            </FormItem>
            <FormItem label="Telefon" class="ivu-mb">
                <Input v-model="data.phone" placeholder="Telefon"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Беркитиш</Button>
            <Button type="primary" @click="addStore" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Saqlanmoqda...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit user modal -->
    <Modal
        v-model="editModal"
        title="Foydalanuvchi turini taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="600"
    >

        <Form ref="formValidate" :label-width="150">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editData.name" placeholder="Nomi" />
                <Text :class="{ 'd-none': !store_name_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="Manzil" class="ivu-mb">
                <Input v-model="editData.address" placeholder="Manzil" />
            </FormItem>
            <FormItem label="Telefon" class="ivu-mb">
                <Input v-model="editData.phone" placeholder="Telefon" />
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Беркитиш</Button>
            <Button type="primary" @click="editStore" :disabled="isEditing" :loading="isEditing">{{isAdding ? 'Saqlanmoqda...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Delete tag modal -->
    <Modal v-model="showDeleteModal" width="360">
        <template #header>
            <p style="color:#f60;text-align:center">
                <Icon type="ios-close-circle" size="20" />
                <span>O'chirishni tasdiqlash!</span>
            </p>
        </template>
        <div style="text-align:center">
            <p>Xaqiqatdan ham o'chirasizmi?</p>
        </div>
        <template #footer>
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteStore">ХА</Button>
        </template>
    </Modal>
</template>

<script>
import StoreHeader from './parts/header.vue'
export default {
    name: "Stores",
    components: {
        StoreHeader:StoreHeader
    },
    data(){
        return {
            data: {
                name: '',
                address: '',
                phone: '',
            },
            editData: {
                id: -1,
                name: '',
                address: '',
                phone: '',
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            isEditing : false,
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deletingIndex: -1,
            stores: [],
            store: {},
            store_name_err: false,

        }
    },
    methods : {
        async addStore(){ //Add product

            if(this.data.name.trim() === '')    return this.err('Nomi majburiy.')

            const res = await this.callApi('post', '/app/add_store', this.data)

            if(res.status === 201) {
                this.stores.unshift(res.data);
                this.s('Do\'kon muvoffaqiyatli qo\'shildi!')
                this.addModal = false
                this.data.name = ''
                this.data.address = ''
                this.data.phone = ''
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

        async editStore(){ //edit product
            if(this.editData.name.trim() === '')    return this.err('Nomi majburiy.')

            const res = await this.callApi('post', '/app/edit_store', this.editData)

            if(res.status === 200) {

                this.stores[this.index].name     = res.data.name
                this.stores[this.index].address  = res.data.address
                this.stores[this.index].phone    = res.data.phone

                this.s('Foydalanuvchi turi ma\'lumotlari muvoffaqiyatli saqlandi!')
                this.editModal = false
            } else {
                if(res.status == 422) {
                    if(res.data.errors.name) {
                        this.err(res.data.errors.name[0])
                    }
                    
                    if(res.data.errors.address) {
                        this.err(res.data.errors.address[0])
                    }
                    
                    if(res.data.errors.phone) {
                        this.err(res.data.errors.phone[0])
                    }

                } else {
                    this.swr()
                }
            }
        },

        async showEditModal(store, index) {
            let storeObj = {
                id:         store.id,
                name:       store.name,
                address:    store.address,
                phone:      store.phone,
            }

            this.editData = storeObj
            this.editModal = true
            this.index = index
        },
        editModalClose(){
            this.editModal = false
        },

        async deleteStore() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_store", this.store)
            if(res.status === 200) {
                this.stores.splice(this.deletingIndex,1)
                this.s('Do\'kon o\'chirildi!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(store, i){
            this.store              = store
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){
        const stores = await this.callApi('get', '/app/get_stores')
        this.stores  = stores.data
        console.log(this.stores);

    },
}
</script>
