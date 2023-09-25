<template>
    <Modal
        v-model="editCardModalData.editCardModal"
        title="Kartani taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="600"
    >

        <Form ref="formValidate" :label-width="180">
            <!-- <input v-maska data-maska="#-#"> -->
            <FormItem label="* Tur" class="ivu-mb">
                <Select v-model="editCardModalData.card.type_id">
                    <template v-for="cardType in editCardModalData.cardTypes" :key="cardType.id">
                        <Option :value="cardType.id">{{cardType.name}}</Option>
                    </template>
                </Select>
                <Text :class="{ 'd-none': !card_type_err }" type="danger" class="m-0 p-0">Тўлдириш мажбурий</Text>
            </FormItem>
            <FormItem label="* Raqam" class="ivu-mb">
                <input autocomplete="off" spellcheck="false" type="text" class="ivu-input ivu-input-default" number="false"
                v-maska data-maska="#### #### #### ####"
                v-model="editCardModalData.card.number" 
                placeholder="#### #### #### ####"
                />
                <Text :class="{ 'd-none': !card_number_err }" type="danger" class="m-0 p-0">Тўлдириш мажбурий</Text>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editCardModalData.editCardModal=false">Berkitish</Button>
            <Button type="primary" @click="editCard" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Karta...':'Saqlash'}}</Button>
        </template>
    </Modal>
</template>

<script>
export default {
    props: ["editCardModalData"],
    data() {
        return {
            isEditing:       false,
            card_type_err:   false,
            card_number_err: false,
            is_invalid:      false,
        }
    },
    methods: {
        async editCard(){
            if(this.editCardModalData.card.type_id == '') {
                this.is_invalid = true
                this.card_type_err = true
            } else {
                this.card_type_err = false
            }

            if(this.editCardModalData.card.number == '') {
                this.is_invalid = true
                this.card_number_err = true
            } else {
                this.card_number_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/edit_card', this.editCardModalData.card)

            if(res.status === 200) {

                this.editCardModalData.cards[this.editCardModalData.cIndex].icon = res.data.icon
                this.editCardModalData.cards[this.editCardModalData.cIndex].name = res.data.name
                this.editCardModalData.cards[this.editCardModalData.cIndex].type_id = res.data.type_id
                this.editCardModalData.cards[this.editCardModalData.cIndex].number  = res.data.number
                this.editCardModalData.cards[this.editCardModalData.cIndex].active  = res.data.active
                
                this.s('Ўзгаришлари мувоффақиятли сақланди!')
                this.editCardModalData.editCardModal = false

            } else {
                if(res.status == 422) {
                    if(res.data.errors.number) {
                        this.err(res.data.errors.number[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
    },


}
</script>
