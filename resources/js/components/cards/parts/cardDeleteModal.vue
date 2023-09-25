<template>
    <Modal v-model="deleteCardData.deleteCardModal" width="360">
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteCard">ХА</Button>
        </template>
    </Modal>
</template>

<script>
export default {
    props: ["deleteCardData"],
    data(){
        return {
        
            isDeleting:       false,

        }
    },
    methods: {
       async deleteCard(){
            
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_card", this.deleteCardData.card)
            if(res.status === 200) {
                this.deleteCardData.cards.splice(this.deleteCardData.cIndex, 1)
                this.s("Karta muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting     = false
            this.deleteCardData.deleteCardModal  = false

        },
    },
    created(){
        
    }
}
</script>

