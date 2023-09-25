<template>
    <ClientHeader />
    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
<!--                <p class="_title0">-->
<!--                    <Button type="primary" @click="addModel=true" class="text-white"><Icon type="md-add-circle" class="mr-1" />Xaridor q'shish</Button>-->
<!--                </p>-->

        <div class="_overflow _table_div">
            <table class="_table">
                <thead>
                <tr class="blue_thead bordered">
                    <th class="_text_center align-top"></th>
                    <th class="_text_center">Ism</th>
                    <th class="text-right">Telefon</th>
                    <th class="text-right">Manzil</th>
                    <th class="text-right">action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(client, o) in clients" :key="o" v-if="clients.length">
                    <td>{{client.id}}</td>
                    <td>{{client.fullName}}</td>
                    <td>{{client.phone}}</td>
                    <td>{{client.address}}</td>
                    <td class="text-right" width="200">
                        <ButtonGroup>
<!--                                    <Button type="primary" title="Ko'rish" class="text-white" :to="{name:'orderDetails', params: {order_id:client.id}}"><Icon type="md-eye"></Icon></Button>-->
                            <Button type="primary" title="Taxrirlash" @click="showEditModal(client, o)"><Icon type="md-create"></Icon></Button>
                            <Button type="error" title="O'chirish" @click="showDeletingModal(client, o)" :loading="client.isDeleting"><Icon type="md-close"></Icon></Button>
                        </ButtonGroup>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <Modal
        v-model="editModal"
        title="Xaridorning ma'lumovlarini taxrirlash"
        :mask-closable="false"
        :closable = "false"
        width="768"
    >
        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="editData.id">
            <FormItem label="* Xaridorning ismi" class="ivu-mb">
                <Input v-model="editData.fullName" prefix="md-person" placeholder="Xaridorning ismi"></Input>
                <Text :class="{ 'd-none': !client_name_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="* Xaridorning telefon raqami" class="ivu-mb">
                <Input v-model="editData.phone" prefix="md-call" placeholder="Xaridorning telefon raqami"></Input>
                <Text :class="{ 'd-none': !client_phone_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="* Xaridorning manzili" class="ivu-mb">
                <Input v-model="editData.address" prefix="md-pin" placeholder="Xaridorning manzili"></Input>
                <Text :class="{ 'd-none': !client_address_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editModal=false">Berkitish</Button>
            <Button type="primary" @click="editClient" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Xaridor...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Delete tag modal -->
    <Modal v-model="showDeleteModal" width="360">
        <template #header>
            <p style="color:#f60; text-align:center">
                <Icon type="ios-close-circle" size="20" />
                <span>O'chirishni tasdiqlash?</span>
            </p>
        </template>
        <div style="text-align:center">
            <p>Xaqiqatdan ham o'chirasizmi?</p>
        </div>
        <template #footer>
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteClient">ХА</Button>
        </template>
    </Modal>

</template>

<script>
import ClientHeader from './parts/header.vue'
export default {
    name: "clients",
    components: {
        ClientHeader:ClientHeader
    },
    data(){
        return {
            editModal : false,
            isAdding : false,
            isEditing : false,
            editData: {
                id: 0,
                fullName: '',
                phone: '',
                address: ''
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            product: {},
            deletingIndex: -1,

            clients: [],
            client: {},

            client_name_err: false,
            client_phone_err: false,
            client_address_err: false,
        }
    },
    methods : {

        async editClient () {
            if(this.editData.fullName == '') {
                this.is_invalid = true
                this.client_name_err = true
            } else {
                this.client_name_err = false
            }

            if(this.editData.phone == '') {
                this.is_invalid = true
                this.client_phone_err = true
            } else {
                this.client_phone_err = false
            }

            if(this.editData.address == '') {
                this.is_invalid = true
                this.client_address_err = true
            } else {
                this.client_address_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/edit_client', this.editData)
            if(res.status == 200) {
                this.clients[this.index].fullName = res.data.fullName
                this.clients[this.index].phone    = res.data.phone
                this.clients[this.index].address  = res.data.address

                this.s("Xaridorning ma'lumovlarini muvaffaqiyatli saqlandi!")
                this.editModal = false
            }
        },
        async showEditModal(client, i){
            let productObj = {
                'id':       client.id,
                'fullName': client.fullName,
                'phone':    client.phone,
                'address':  client.address,
            }

            this.editData = productObj
            this.editModal = true
            this.index = i
        },

        async deleteClient() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_client", this.client)

            if(res.status === 200) {
                this.clients.splice(this.deletingIndex,1)
                this.s('Xaridor muvaffaqiyatli o\'chirildi!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(client, i){
            this.client            = client
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){
        const clients = await this.callApi('get', '/app/get_clients')
        if(clients.status == 200) {
            this.clients = clients.data
        } else if(clients.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }
    },
}
</script>

<style scoped>

</style>
