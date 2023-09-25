<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
                <p class="_title0">
                    <Button :to="{ name: 'rmIncomeAdd'}"><Icon type="md-add-circle" color="#2d8cf0" class="mr-1" />Кирим қўшиш</Button>
<!--                    <Button :to="{ name: 'rmoutcome'}" class="ivu-ml"><Icon type="md-remove-circle" color="#f5222d" class="mr-1" />Чиқим</Button>-->
                </p>

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr>
                            <th>Хужжат №</th>
                            <th>Қабул қилди (ФИШ)</th>
                            <th>Қаердан</th>
                            <th>Қаерга</th>
                            <th>Жами</th>
                            <th>Киро хақи (сўм)</th>
                            <th>Сана</th>
                            <th width="300px" class="text-right">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(contract, i) in contracts" :key="contract.id"
                            v-if="contracts.length"
                        >
                            <td>
                                <Icon v-if="contract.inout == 1" type="md-arrow-down" color="#0099ff" />
                                <Icon v-else type="md-arrow-up" color="#ff0000" />
                                {{contract.name}}
                            </td>
                            <td>{{contract.fullName}}</td>
                            <td>{{contract.department_from}}</td>
                            <td>{{contract.department_to}}</td>
                            <td>{{contract.currency_id == 1 ? "$":""}}
                                {{ contract.total }}
                                {{contract.currency_id == 2 ? "сўм":""}} </td>
                            <td>{{ contract.transportation_cost }} </td>
                            <td>
                                {{ new Date(contract.created_at).toLocaleString() }}
                            </td>
                            <td class="text-right">
                                <Button v-if="contract.inout == 1" type="success" :to="{name:'rmincomeDetails', params: {id:contract.id}}" class="ivu-mr text-white"><Icon type="ios-eye" size="20" />view</Button>
                                <Button v-else type="success" :to="{name:'rmoutcomeDetails', params: {id:contract.id}}" class="ivu-mr text-white"><Icon type="ios-eye" size="20" />view</Button>

                                <Button :disabled="!contract.editable" type="error" @click="showDeletingModal(contract, i)" :loading="contract.isDeleting"><Icon type="md-close" /> delete</Button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

    <!-- Delete tag modal -->
    <Modal v-model="showDeleteModal" width="360">
        <template #header>
            <p style="color:#f60; text-align:center">
                <Icon type="ios-close-circle" size="20" />
                <span>Ўчиришни тасдиқлаш?</span>
            </p>
        </template>
        <div style="text-align:center">
            <p>Хақиқатдан хам ўчирасизми?</p>
        </div>
        <template #footer>
            <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteContract">ХА</Button>
        </template>
    </Modal>

</template>

<script>
import StockHeader from './rmstock_header.vue'
export default {
    name: "rmIncom",
    components: {
        StockHeader:StockHeader
    },
    data(){
        return {
            contracts: [],
            singleContract: {},
            showDeleteModal:            false,
            isDeleting:                 false,
            deletingIndex:              -1,
            activeCategory:             'rawMaterials'


        }
    },
    methods: {
        async deleteContract() {
            this.isDeleting = true
            const res = await this.callApi("post", "/app/delete_contract", this.singleContract)
            console.log(res)
            if(res.status === 200) {
                this.contracts.splice(this.deletingIndex,1)
                this.s('Махсулот мувоффақиятли ўчирилди!')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },


        async showDeletingModal(contract, i){
            this.singleContract     = contract
            this.deletingIndex      = i
            this.showDeleteModal    = true
        },
    },

    async created(){

        const res = await this.callApi('get', '/app/get_inout')
        if(res.status == 200) {
            this.contracts = res.data

        } else {
            this.swr()
        }
    }
}
</script>

