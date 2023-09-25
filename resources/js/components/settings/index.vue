<template>

    <Form label-position="right" :label-width="180">
        <Row class="ivu-mt" v-for="(setting, index) in settings" :key="setting.id">
            <Col span="12">
                <FormItem :label="`${setting.title}`" label-position="top">
                    <Input v-model="settings[index].value"/>
                </FormItem>
            </Col>
        </Row>
        <FormItem class="ivu-mt" >
            <Button type="primary" @click="saveSettings" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Sozlamalar...':'Saqlash'}}</Button>
        </FormItem>
    </Form>

</template>

<script>
export default {
    name: "settings",
    data() {
        return {
            settings: [],
            isAdding: false,
        }
    },
    methods: {
        async saveSettings(){

            // console.log(this.settings)
            const settingsData = {
                "settings":this.settings
            }

            const res = await this.callApi('post', '/app/update_settings_item', settingsData)
            console.log(res.data)
            if(res.status == 200) {
                this.s('Sozlamalar o\'zgarishlari muvaffaqiyatli saqlandi!')
            }
        }
    },
    async created(){
        const settings = await this.callApi('get', '/app/get_settings')
        this.settings = settings.data
    }
}
</script>

