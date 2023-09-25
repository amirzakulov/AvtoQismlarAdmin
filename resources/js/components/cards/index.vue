<template>
<div class="_1adminOverveiw_table_recent  _border_radious _mar_b30 _p20">

    <Row>
        <Col span="16">
            <h4>Kartalar</h4>
            <Card>
                <CardAddForm
                    :addFormData="addFormData"
                    :cardTypes="cardTypes"
                    :cards2="cards"
                >
                </CardAddForm>
                <Divider />
                
                <div class="_overflow1 _table_div">
                    <table class="_table _table_small_size">
                        <!-- TABLE TITLE -->
                        <thead>
                        <tr class="blue_thead bordered">
                            <th class="_text_center" width="20"></th>
                            <th class="_text_center">Tur</th>
                            <th class="_text_center">Raqam</th>
                            <th class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <template v-if="cards.length">
                                <tr v-for="(card, i) in cards" :key="i">
                                    <td>
                                        <Space class="float-right">
                                            <Switch v-model="cardsStatus[i]" 
                                            @on-change="changeStatus(card, i)"
                                            :true-value="1"
                                            :false-value="0">
                                                <template #open>
                                                    <Icon type="md-checkmark"></Icon>
                                                </template>
                                                <template #close>
                                                    <Icon type="md-close"></Icon>
                                                </template>
                                            </Switch>
                                        </Space>
                                    </td>
                                    <td valign="center">
                                        <Avatar shape="square" size="small" :src="`/images/${card.icon}`" /> 
                                        {{card.type}}
                                    </td>
                                    <td>{{card.number}}</td>
                                    <td class="text-right">
                                        <Button type="primary" size="small" title="Тахрирлаш" @click="showCardEditModal(card, i)"><Icon type="md-create"></Icon></Button>
                                        <Button type="error" size="small" title="Ўчириш" @click="showCardDeletingModal(card, i)"><Icon type="md-close"></Icon></Button>
                                    </td>
                                </tr>
                            </template>

                        </tbody>
                    </table>
                </div>


                <!-- <template v-if="cards.length">
                    <List>
                        <ListItem>
                            
                            <Space class="float-right">
                                <Switch v-model="cards[1]" @on-change="changeStatus">
                                    <template #open>
                                        <Icon type="md-checkmark"></Icon>
                                    </template>
                                    <template #close>
                                        <Icon type="md-close"></Icon>
                                    </template>
                                </Switch>
                            </Space>

                            <Image class="ml-3" :src="`/images/ico_uzcard_64x.png`" fit="contain" width="16px" /> 
                            <strong class="text-dark pl-1">UZCard: </strong> 
                            <span class="text-dark ivu-ml _text">8600 2133 4657 4848</span>

                            <Space class="text-right ml-5">
                                <Icon type="md-create" class="cursor-pointer" />
                                <Icon type="md-close" class="cursor-pointer text-danger" />
                            </Space>
                        </ListItem>

                        <ListItem>
                            
                            <Space class="float-right">
                                <Switch @on-change="changeStatus">
                                    <template #open>
                                        <Icon type="md-checkmark"></Icon>
                                    </template>
                                    <template #close>
                                        <Icon type="md-close"></Icon>
                                    </template>
                                </Switch>
                            </Space>

                            <Image class="ml-3" :src="`/images/ico_humo_64x.png`" fit="contain" width="16px" /> 
                            <strong class="text-dark pl-1">HUMO: </strong> 
                            <span class="text-dark ivu-ml _text">8600 2133 4657 4848</span>

                            <Space class="text-right ml-5">
                                <Icon type="md-create" class="cursor-pointer" />
                                <Icon type="md-close" class="cursor-pointer" />
                            </Space>
                            

                            <ButtonGroup class="text-right ml-5">
                                <Button type="primary" size="small" icon="md-create" @click="showEditAdModal(ad, adIndex, indexPartner)"></Button>
                                <Button type="error" size="small" icon="md-close" @click="showDeleteAdModal(ad, adIndex, indexPartner)"></Button>
                            </ButtonGroup>
                        </ListItem>

                    </List>
                </template>
                <template v-else>
                    Karta qo'shilmagan
                </template> -->
                
            </Card>
        </Col>
        <Col span="4" offset="4">
            
            <h4>Karta turlari</h4>
            <Card>
                <List v-if="cardTypes.length">
                    <ListItem v-for="(type, i) in cardTypes">
                        <Avatar shape="square" size="small" :src="`/images/${type.icon}`" class="ivu-mr" /> 
                        {{type.name}}
                    </ListItem>
                </List>
            </Card>
        </Col>
    </Row>

    <CardEditModal :editCardModalData="editCardModalData"></CardEditModal>
    <CardDeleteModal :deleteCardData="deleteCardData"></CardDeleteModal>

    
</div>


</template>

<script>
import CardAddForm from "./parts/cardAddForm.vue";
import CardEditModal from "./parts/cardEditModal.vue";
import CardDeleteModal from "./parts/cardDeleteModal.vue";
export default {
    name: "cards",
    components: {
        CardAddForm: CardAddForm,
        CardEditModal: CardEditModal,
        CardDeleteModal: CardDeleteModal,
    },
    computed: {
        cardsStatus(){
            let actives = []
            this.cards.forEach((card, index) => {
                actives[index] = parseInt(card.active)
            })

            return actives
        }
    },
    data() {
        return {
            cards: [],
            cardTypes: [],
            cardStatus: [],
            addFormData: {
                card: {
                    type_id: '',
                    number: '',
                    active: 1,
                },
            },
            editCardModalData: {
                editCardModal: false,
                card: {
                    id: '',
                    type_id: '',
                    number: '',
                    active: '',
                    icon: '',
                },

            },
            deleteCardData: {
                deleteCardModal: false,
                card: {
                    id: '',
                    type_id: '',
                    number: '',
                    active: '',
                },
            },
        }
    },
    methods: {
        async changeStatus (card, i) {
            
            card.active = card.active == 1 ? 0:1
            this.cards[i] = card
            const res = await this.callApi("post", "app/edit_card", card)
            
        },
        showCardEditModal(card, i){
            let cardObj = {
                    id: card.id,
                    type_id: card.type_id,
                    number: card.number,
                    active: card.active,
                    icon: card.icon,
            }

            this.editCardModalData = {
                cards: this.cards,
                cardTypes: this.cardTypes,
                card: cardObj,
                editCardModal: true,
                cIndex: i
            }

        },
        showCardDeletingModal(card, i){
            this.deleteCardData = {
                cards: this.cards,
                card: card,
                deleteCardModal: true,
                cIndex: i
            }
        }

    },
    async created(){
        const cards = await this.callApi("get", "app/get_cards")
        this.cards = cards.data

        const cardTypes = await this.callApi("get", "app/get_card_types")
        this.cardTypes = cardTypes.data

    }
}
</script>

