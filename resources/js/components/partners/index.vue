<template>
    <Row class="ivu-ml">
        <Col span="2">
            <ButtonGroup>
                <Button @click="addPartnerModal=true" type="primary" icon="md-add">Xamkor Qo'shish</Button>
            </ButtonGroup>
        </Col>
    </Row>

    <!-- Xamkolar -->

    <Card v-for="(partner, indexPartner) in partners" :key="indexPartner"
        if="partners.lenght"
        class="ivu-m" :padding="0" :bordered="false" :shadow="false" style="background: none;">
        <!-- Xamkorninig xaqidagi ma'lumotlar -->


        <Row>
            <Col span="12" class="text-left">
                <ButtonGroup>
                    <Button type="warning" icon="md-add" @click="showAddAdModal(partner, indexPartner)">Reklama qo'shish</Button>
                </ButtonGroup>
            </Col>
            <Col span="12" class="text-right">
                <ButtonGroup>
                    <Button type="primary" icon="md-create" @click="showEditPartnerModal(partner, indexPartner)"></Button>
                    <Button type="error" icon="md-close" @click="showDeletePartnerModal(partner, indexPartner)"></Button>
                </ButtonGroup>
            </Col>
        </Row>
        <Row style="background: #57a3f3; padding: 10px 15px 0 15px; color: #fff; ">
            <Col span="5"><strong>Ism</strong></Col>
            <Col span="5"><strong>Telefon</strong></Col>
            <Col span="5"><strong>Manzil</strong></Col>
            <Col span="5"><strong>Tashkilot</strong></Col>
            <Col span="3"><strong>Xamkorlik sanasi</strong></Col>
            <Col span="1"></Col>
        </Row>
        <Row style="background: #57a3f3; padding: 0 15px 10px 15px; color: #fff;">
            <Col span="5">{{partner.fullName}}</Col>
            <Col span="5">{{partner.phone}}</Col>
            <Col span="5">{{partner.address}}</Col>
            <Col span="5">{{partner.company}}</Col>
            <Col span="3">{{partner.created_at}}</Col>
            <Col span="1"></Col>
        </Row>

        <div id="partnerAds" class="ivu-p bg-white">
            <Row :gutter="16">
                <!-- Xamkorninig Reklamalari -->
                <Col v-for="(ad, adIndex) in partner.ads" :key="ad.id"

                    span="6" class="ivu-mt">
                    <Card :padding="0">
                        <div class="text-dark">
                            <img height="200"
                                 :style="imgOpacity[ad.id]"
                                 :src="`/uploads/ads/${ad.picture}`"
                            >
                            <div class="ivu-m">{{ad.title}}</div>
                            <div class="ivu-m">Mobil ilovada Ko'satsinmi?
                                <Space class="float-right">
                                    <Switch v-model="showAdOnMobile[ad.id]" size="large"
                                            @on-change="changeAdStatus(ad.id)"
                                    >
                                        <template #open>
                                            <span>Xa</span>
                                        </template>
                                        <template #close>
                                            <span>Yo'q</span>
                                        </template>
                                    </Switch>
                                </Space>
                            </div>
                            <div class="text-right">
                                <ButtonGroup class="text-right">
                                    <Button type="primary" icon="md-create" @click="showEditAdModal(ad, adIndex, indexPartner)"></Button>
                                    <Button type="error" icon="md-close" @click="showDeleteAdModal(ad, adIndex, indexPartner)"></Button>
                                </ButtonGroup>
                            </div>
                        </div>
                    </Card>
                </Col>
            </Row>
        </div>

    </Card>

    <Divider />

    <!-- Add Partner Modal -->
    <Modal
        v-model="addPartnerModal"
        title="Xamkorni taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Ism" class="ivu-mb">
                <Input v-model="addPartnerData.fullName" placeholder="Ism"></Input>
            </FormItem>
            <FormItem label="* Telefon" class="ivu-mb">
                <Input v-model="addPartnerData.phone" placeholder="Telefon"></Input>
            </FormItem>
            <FormItem label="Manzil" class="ivu-mb">
                <Input v-model="addPartnerData.address" placeholder="Manzil"></Input>
            </FormItem>
            <FormItem label="Tashkilot" class="ivu-mb">
                <Input v-model="addPartnerData.company" placeholder="Tashkilot"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="addPartnerModal=false">Berkitish</Button>
            <Button type="primary" @click="addPartner" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Xamkor...':'Saqlash'}}</Button>
        </template>
    </Modal>

<!-- Edit Partner Modal -->
    <Modal
        v-model="editPartnerModal"
        title="Xamkorni taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Ism" class="ivu-mb">
                <Input v-model="editPartnerData.fullName" placeholder="Ism"></Input>
            </FormItem>
            <FormItem label="* Telefon" class="ivu-mb">
                <Input v-model="editPartnerData.phone" placeholder="Telefon"></Input>
            </FormItem>
            <FormItem label="Manzil" class="ivu-mb">
                <Input v-model="editPartnerData.address" placeholder="Manzil"></Input>
            </FormItem>
            <FormItem label="Tashkilov" class="ivu-mb">
                <Input v-model="editPartnerData.company" placeholder="Tashkilot"></Input>
            </FormItem>
        </Form>
        <template #footer>
            <Button type="error" @click="editPartnerModal=false">Berkitish</Button>
            <Button type="primary" @click="editPartner()" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Xamkor...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Delete tag modal -->
    <Modal v-model="deletePartnerModal" width="360">
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deletePartner">ХА</Button>
        </template>
    </Modal>

<!--    Advertisement -->
    <!-- Add Ad Modal -->
    <Modal
        v-model="addAdModal"
        title="Reklama qo'shish"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <input type="hidden" v-model="addAdData.partner_id">
            <input type="hidden" v-model="addAdData.active">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="addAdData.title" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="Tartib" class="ivu-mb">
                <Input v-model="addAdData.sort" placeholder="Tartib"></Input>
            </FormItem>
            <FormItem label="Rasm">
                <Upload type="drag" action="/app/ad_picture_upload"
                        ref="upload"
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
            <div v-if="addAdData.picture" style="margin-left: 180px;">
                <Image :src="`/uploads/ads/${addAdData.picture}`" fit="cover" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="addAdModal=false">Berkitish</Button>
            <Button type="primary" @click="addAd" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Reklama...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Edit Ad Modal -->
    <Modal
        v-model="editAdModal"
        title="Reklamani taxrirlash"
        :mask-closable = "false"
        :closable = "false"
        width="768"
    >

        <Form ref="formValidate" :label-width="180">
            <FormItem label="* Nomi" class="ivu-mb">
                <Input v-model="editAdData.title" placeholder="Nomi"></Input>
            </FormItem>
            <FormItem label="Tartib" class="ivu-mb">
                <Input v-model="editAdData.sort" placeholder="Tartib"></Input>
            </FormItem>
            <FormItem label="Rasm">
                <Upload type="drag" action="/app/ad_picture_upload"
                        ref="upload"
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
            <div v-if="editAdData.picture" style="margin-left: 180px;">
                <Image :src="`/uploads/ads/${editAdData.picture}`" fit="cover" width="100px" height="100px" alt="cover" />
            </div>
        </Form>
        <template #footer>
            <Button type="error" @click="editAdModal=false">Berkitish</Button>
            <Button type="primary" @click="editAd" :disabled="isEditing" :loading="isEditing">{{isEditing ? 'Reklama...':'Saqlash'}}</Button>
        </template>
    </Modal>

    <!-- Delete Ad modal -->
    <Modal v-model="deleteAdModal" width="360">
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
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteAd">ХА</Button>
        </template>
    </Modal>
</template>

<script>
export default {
    name: "index",
    data(){
        return {
        // {opacity: ad.active ? 1:0.4}
            imgOpacity: [
                {opacity: 0.4}
            ],

            switch: true,

            token: '',
            isAdding: false,
            isEditing: false,
            isDeleting: false,
            partners: [],
            showAdOnMobile: [],
            addPartnerModal: false,
            addPartnerData: {
                fullName:    '',
                phone:       '',
                address:     '',
                company:     '',
            },
            editPartnerModal: false,
            editPartnerData: {
                id:         -1,
                fullName:    '',
                phone:       '',
                address:     '',
                company:     '',
            },
            partner: {},
            indexPartner: -1,
            deletingPartnerIndex: -1,
            deletePartnerModal: false,

            //Advertisement
            ad: {},
            addAdModal: false,
            addAdData: {
                title:      '',
                partner_id: -1,
                sort:       '',
                picture:    '',
                active:     1,
            },
            indexAd: -1,

            editAdModal: false,
            editAdData: {
                id:         '',
                title:      '',
                sort:       '',
                picture:    '',
                active:     -1,
            },

            deleteAdModal: false,
            deletingAdIndex: -1,

        }
    },
    methods: {

        async addPartner() {
            if (this.addPartnerData.fullName === '') return this.err("Ism majburiy.")
            if (this.addPartnerData.phone === '')    return this.err("Telefon raqam majburiy.")

            const res = await this.callApi('post', '/app/add_partner', this.addPartnerData)

            if(res.status === 201) {

                this.partners.unshift(res.data);
                this.s('Xamkor muvoffaqiyatli qo\'shildi!')
                this.addPartnerModal = false

                this.addPartnerData.fullName  = ''
                this.addPartnerData.phone     = ''
                this.addPartnerData.address   = ''
                this.addPartnerData.company   = ''

            } else {
                if(res.status == 422) {
                    if(res.data.errors.fullName) {
                        this.err(res.data.errors.fullName[0])
                    }
                } else {
                    this.swr()
                }
            }

        },
        async editPartner(){ //edit product
            if(this.editPartnerData.fullName === '')     return this.err("Ism majburiy.")
            if(this.editPartnerData.phone === '')     return this.err("Telefon raqam majburiy.")

            const res = await this.callApi('post', '/app/edit_partner', this.editPartnerData)

            if(res.status === 200) {

                this.partners[this.indexPartner].fullName  = res.data.fullName
                this.partners[this.indexPartner].phone     = res.data.phone
                this.partners[this.indexPartner].address   = res.data.address
                this.partners[this.indexPartner].company   = res.data.company

                this.s('Махсулот ўзгаришлари мувоффақиятли сақланди!')
                this.editPartnerModal = false
            } else {
                if(res.status == 422) {
                    if(res.editPartnerData.errors.fullName) {
                        this.err(res.editData.errors.fullName[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        async showEditPartnerModal(partner, index) {
            let productObj = {
                id:          partner.id,
                fullName:    partner.fullName,
                phone:       partner.phone,
                address:     partner.address,
                company:     partner.company,
            }

            this.editPartnerData = productObj
            this.editPartnerModal = true
            this.indexPartner = index
        },
        editModalClose(){
            this.editModal = false
        },
        async deletePartner() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_partner", this.partner)
            if(res.status === 200) {
                this.partners.splice(this.deletingPartnerIndex,1)
                this.s("Xamkor muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.deletePartnerModal = false
        },
        showDeletePartnerModal(partner, i){
            this.partner                = partner
            this.deletingPartnerIndex   = i
            this.deletePartnerModal = true
        },
        async addAd(){
            if (this.addAdData.title === '') return this.err("Nomi majburiy.")

            const res = await this.callApi('post', '/app/add_partner_ad', this.addAdData)

            if(res.status === 201) {

                this.partners[this.indexPartner]["ads"].unshift(res.data);
                this.s('Reklama muvoffaqiyatli qo\'shildi!')
                this.addAdModal = false

                this.showAdOnMobile[res.data.id] = true
                // this.imgOpacity[ad.id] = {opacity: 1}

                this.addAdData.title        = ''
                this.addAdData.partner_id   = -1
                this.addAdData.sort         = ''
                this.addAdData.picture      = ''

            } else {
                if(res.status == 422) {
                    if(res.data.errors.title) {
                        this.err(res.data.errors.title[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        showAddAdModal(partner, index){
            this.addAdData.partner_id = partner.id
            this.addAdData.active = 1
            this.indexPartner = index
            this.addAdModal = true
        },
        async editAd(){
            if (this.editAdData.title === '') return this.err("Nomi majburiy.")

            const res = await this.callApi('post', '/app/edit_partner_ad', this.editAdData)
            if(res.status === 200) {

                this.partners[this.indexPartner]["ads"][this.indexAd].title         = res.data.title
                this.partners[this.indexPartner]["ads"][this.indexAd].sort          = res.data.sort
                this.partners[this.indexPartner]["ads"][this.indexAd].picture       = res.data.picture
                this.partners[this.indexPartner]["ads"][this.indexAd].active        = res.data.active

                this.s("Reklama o'zgarishlari saqlandi!")
                this.editAdModal = false
            } else {
                if(res.status == 422) {
                    if(res.editAdModal.errors.title) {
                        this.err(res.editAdModal.errors.title[0])
                    }
                } else {
                    this.swr()
                }
            }
        },
        showEditAdModal(ad, adIndex, partnerIndex){
            let productObj = {
                id:         ad.id,
                title:      ad.title,
                partner_id: ad.partner_id,
                sort:       ad.sort,
                picture:    ad.picture,
                active:     ad.active,
            }

            this.editAdData     = productObj
            this.editAdModal    = true
            this.indexAd        = adIndex
            this.indexPartner   = partnerIndex
        },
        async deleteAd() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_ad", this.ad)
            if(res.status === 200) {
                this.partners[this.deletingPartnerIndex]["ads"].splice(this.deletingAdIndex, 1)
                this.s("Reklama muvaffaqiyatli o'chirildi!")
            } else {
                this.swr();
            }

            this.isDeleting     = false
            this.deleteAdModal  = false
        },
        showDeleteAdModal(ad, i, j){
            this.ad                     = ad
            this.deletingAdIndex        = i
            this.deletingPartnerIndex   = j
            this.deleteAdModal          = true
        },
        handleSuccess (res, file) {
            this.addAdData.picture = res
        },
        handleSuccessEdit (res, file) {
            this.editAdData.picture = res
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
        async changeAdStatus(adId){

            let status = {id:adId}
            const res = await this.callApi("post", "app/change_ad_status", status)

            if(res.data){
                this.imgOpacity[adId] = {opacity: 1}
                this.s("Reklama aktivlashtirildi")
            } else {
                this.imgOpacity[adId] = {opacity: 0.4}
                this.s("Reklama to'xtatildi")
            }
        },

        fillPartnersAds(partners){
            partners.forEach((partner, key1) => {
                partner["ads"].forEach((ad, key2) => {
                    this.showAdOnMobile[ad.id] = false

                    if(parseInt(ad.active) === 1) {
                        this.showAdOnMobile[ad.id] = true
                        this.imgOpacity[ad.id] = {opacity: 1}

                    } else {
                        this.imgOpacity[ad.id] = {opacity: 0.4}
                    }

                })
            })
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken
        const partners = await this.callApi("get", "/app/get_partners_ads")

        if(partners.status == 200) {
            this.partners = partners.data
        } else if(partners.status == 403) {
            window.location = 'http://localhost/logout'
        } else {
            this.swr()
        }



        this.fillPartnersAds(this.partners)
    }
}
</script>
