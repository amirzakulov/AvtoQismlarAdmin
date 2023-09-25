<template>
    <Form label-position="top">
        <Row :gutter="8">
            <Col span="10">
                <FormItem label="Turlar">
                    <Select v-model="addFormData.card.type_id">
                        <template v-for="cardType in cardTypes" :key="cardType.id">
                            <Option :value="cardType.id">
                                <Avatar shape="square" size="small" :src="`/images/${cardType.icon}`" class="ivu-mr" /> 
                                {{cardType.name}}
                            </Option>
                        </template>
                    </Select>
                    <Text :class="{ 'd-none': !card_type_err }" type="danger" class="m-0 p-0">Тўлдириш мажбурий</Text>
                </FormItem>
            </Col>
            <Col span="10">
                <FormItem label="Raqam">
                    <input autocomplete="off" spellcheck="false" 
                        type="text" class="ivu-input ivu-input-default" number="false"
                        v-maska data-maska="#### #### #### ####"
                        v-model="addFormData.card.number" 
                        placeholder="#### #### #### ####"
                    />
                    <Text :class="{ 'd-none': !card_number_err }" type="danger" class="m-0 p-0">Тўлдириш мажбурий</Text>
                </FormItem>
            </Col>
            <Col span="4">
                <FormItem label="&nbsp;&nbsp;" class="text-right">
                    <Button type="primary" style="width:100%" @click="addCard">Қўшиш</Button>
                </FormItem>
            </Col>
        </Row>
    </Form>
</template>
<script>

export default {
    props: ["addFormData", "cardTypes", "cards2"],
    data(){
        return {
        
            isAdding:       false,
            card_type_err:   false,
            card_number_err: false,
            is_invalid:      false,

        }
    },
    methods: {
       async addCard(){
            if(this.addFormData.card.type_id == '') {
                this.is_invalid = true
                this.card_type_err = true
            } else {
                this.card_type_err = false
            }

            if(this.addFormData.card.number == '') {
                this.is_invalid = true
                this.card_number_err = true
            } else {
                this.card_number_err = false
            }

            if(this.is_invalid) {
                this.is_invalid = false
                return this.err("Barcha kataklarni to'ldirish majburiy!")
            }

            const res = await this.callApi('post', '/app/add_card', this.addFormData.card)

            if(res.status === 200) {

                this.cards2.unshift(res.data);

                this.addFormData.card.type_id = ''
                this.addFormData.card.number  = ''

                this.s('Ўзгаришлари мувоффақиятли сақланди!')

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
    created(){
        
    }
}
</script>

