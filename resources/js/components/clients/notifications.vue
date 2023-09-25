<template>
    <ClientHeader />
    <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
        <p class="_title0">
            <Button type="primary" @click="addModal=true" class="text-white"><Icon type="md-add-circle" class="mr-1" />Xabar yuborish</Button>
        </p>

        <div class="_overflow _table_div">
            <table class="_table">
                <thead>
                <tr class="blue_thead bordered">
                    <th class="_text_center" width="200">Mavzu</th>
                    <th class="text-right">Xabar</th>
                    <th class="text-right" width="200">Sana</th>
                    <th class="text-right" width="50"></th>
                </tr>
                </thead>
                <tbody>
                    <template v-if="messages.length">
                    <tr v-for="(message, i) in messages" :key="i">
                        <td>{{message.title}}</td>
                        <td>{{message.message}}</td>
                        <td>{{message.created_at}}</td>
                        <td class="text-right">
                            <ButtonGroup>
                                <Button type="error" title="O'chirish" @click="showDeletingModal(message, i)" :loading="message.isDeleting"><Icon type="md-close"></Icon></Button>
                            </ButtonGroup>
                        </td>
                    </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <Modal
        v-model="addModal"
        title="Xabar qo'shish"
        :mask-closable="false"
        :closable = "false"
        width="768"
    >
        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Mavzu" class="ivu-mb">
                <Input v-model="addData.title" prefix="md-create" placeholder="Mavzu"></Input>
                <Text :class="{ 'd-none': !title_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
            <FormItem label="* Xabar" class="ivu-mb">
                <Input v-model="addData.message" type="textarea" :rows="4" prefix="md-call" placeholder="Xabar"></Input>
                <Text :class="{ 'd-none': !message_err }" type="danger">To'ldirish majburiy!</Text>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addModal=false">Berkitish</Button>
            <Button type="primary" @click="addMessage" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Xabar...':'Yuborish'}}</Button>
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteMessage">ХА</Button>
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
            addModal : false,
            isAdding : false,
            isEditing : false,
            addData: {
                title: '',
                message: '',
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deletingIndex: -1,

            message: {},
            messages: [],

            title_err: false,
            message_err: false,
        }
    },
    methods : {

        async addMessage () {
            if(this.addData.title == '') {
                this.is_invalid = true
                this.title_err = true
            } else {
                this.title_err = false
            }

            if(this.addData.message == '') {
                this.is_invalid = true
                this.message_err = true
            } else {
                this.message_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/send_fcm_message', this.addData)

            if(res.status == 201) {

                this.messages.unshift(res.data);

                this.addData.title     = ''
                this.addData.message   = ''

                this.addModal = false
                this.s("Xabar muvaffaqiyatli yuborildi!")
            }
        },

        async deleteMessage() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_message", this.message)

            if(res.status === 200) {
                this.messages.splice(this.deletingIndex,1)
                this.s('Xaridor muvaffaqiyatli o\'chirildi!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(client, i){
            this.message            = client
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){
        const messages = await this.callApi('get', '/app/get_messages')

        if(messages.status == 200) {
            this.messages = messages.data
        } else if(messages.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }
    },
}
</script>

<style scoped>

</style>
