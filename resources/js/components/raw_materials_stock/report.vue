<template>
    <StockHeader />
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">

                <div class="_overflow _table_div">
                    <table class="_table">
                        <thead>
                        <tr class="bordered">
                            <th class="_text_center" colspan="3"></th>
                            <th class="_text_center" colspan="5">Ой боши қолдиқ</th>
                            <th class="_text_center" colspan="5">Кирим</th>
                            <th class="_text_center" colspan="5">Чиқим</th>
                            <th class="_text_center" colspan="5">Ой охири қолдиқ</th>
                            <th>Action</th>
                        </tr>
                        <tr class="blue_thead bordered">
                            <th class="_text_center" style="min-width: 300px">Махсулот Номи</th>
                            <th class="_text_center">Коди</th>
                            <th class="_text_center">Бирлик</th>
                            <th class="_text_center">Тўп</th>
                            <th class="_text_center">Миқдор</th>
                            <th class="_text_center">Нарх (USD)</th>
                            <th class="_text_center">Курс</th>
                            <th class="_text_center">Жами</th>

                            <th class="_text_center">Тўп</th>
                            <th class="_text_center">Миқдор</th>
                            <th class="_text_center">Нарх (USD)</th>
                            <th class="_text_center">Курс</th>
                            <th class="_text_center">Жами</th>

                            <th class="_text_center">Тўп</th>
                            <th class="_text_center">Миқдор</th>
                            <th class="_text_center">Нарх (USD)</th>
                            <th class="_text_center">Курс</th>
                            <th class="_text_center">Жами</th>

                            <th class="_text_center">Тўп</th>
                            <th class="_text_center">Миқдор</th>
                            <th class="_text_center">Нарх (USD)</th>
                            <th class="_text_center">Курс</th>
                            <th class="_text_center">Жами</th>
                            <th class="_text_center"></th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr
                            v-for="(product, i) in products" :key="i"
                            v-if="products.length"
                        >
                            <td>{{product.name}}</td>
                            <td>{{product.code}}</td>
                            <td style="border-right: 1px solid #999;">{{product.measurement}}</td>

                            <td class="_text_center">{{!product.prev_month_packageCount ? 0:product.prev_month_packageCount}}</td>
                            <td class="_text_center">{{!product.prev_month_count ? 0:product.prev_month_count}}</td>
                            <td class="_text_center">{{!product.prev_month_price ? 0:product.prev_month_price}}</td>
                            <td class="_text_center">{{product.prev_month_dollar_rate}}</td>
                            <td style="border-right: 1px solid #999;">{{formatPrice(product.prev_month_count * product.prev_month_price)}}</td>

                            <td class="_text_center">{{ product.inPackageCount }}</td>
                            <td class="_text_center">{{ product.inCount }}</td>
                            <td class="_text_center">{{ product.inPrice }}</td>
                            <td class="_text_center">{{ product.inDollar_rate }}</td>
                            <td style="border-right: 1px solid #999;">{{ formatPrice(product.inCount * product.inPrice ) }}</td>

                            <td class="_text_center">{{ (-1) * product.outPackageCount }}</td>
                            <td class="_text_center">{{ (-1) * product.outCount }}</td>
                            <td class="_text_center">{{ product.outPrice }}</td>
                            <td class="_text_center">{{ product.outDollar_rate }}</td>
                            <td style="border-right: 1px solid #999;">{{ formatPrice((-1) * product.outCount * product.outPrice ) }}</td>

                            <td class="_text_center">{{!product.packageCount ? 0:product.packageCount}}</td>
                            <td class="_text_center">{{!product.count ? 0:product.count}}</td>
                            <td class="_text_center">{{!product.price ? 0:product.price}}</td>
                            <td class="_text_center">{{product.dollar_rate}}</td>
                            <td style="border-right: 1px solid #999;">{{formatPrice(product.count * product.price)}}</td>

                            <td class="_text_center">
                                <Button type="info" size="small" class="d-none" @click="showEditModal(product, i)">edit</Button>
                                <Button type="error" size="small" class="d-none" @click="showDeletingModal(product, i)" :loading="product.isDeleting">delete</Button>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>

                        <tr class="blue_thead bordered">
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center">{{prevMonthTotal}}</th>

                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center">{{inTotal}}</th>

                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center">{{(-1) * outTotal}}</th>

                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center"></th>
                            <th class="_text_center">{{lastMonthTotal}}</th>
                            <th class="_text_center"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Add product modal -->
            <Modal
                v-model="addModal"
                title="Махсулот қўшиш"
                :mask-closable="false"
                :closable = "false"
            >
                <Input v-model="data.productName" placeholder="Махсулот номини киритинг" />
                <template #footer>
                    <Button type="default" size="small" @click="addModal=false">Беркитиш</Button>
                    <Button type="primary" size="small" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Махсулот...':'Махсулот қўшиш'}}</Button>
                </template>
            </Modal>

            <!-- Edit tag modal -->
            <Modal
                v-model="editModal"
                title="Махсулотни тахрирлаш"
                :mask-closable="false"
                :closable = "false"
                :width = "768"
            >

                <Form ref="formValidate" :label-width="110">
                    <FormItem label="Номи" prop="name">
                        <Input v-model="editData.productName" placeholder="Махсулот номи"></Input>
                    </FormItem>
                    <FormItem label="Коди" prop="code">
                        <Input v-model="editData.productCode" placeholder="Махсулот коди"></Input>
                    </FormItem>
                    <FormItem label="Ранги" prop="color">
                        <Input v-model="editData.productColor" placeholder="Махсулот ранги"></Input>
                    </FormItem>
                    <FormItem label="Давлати" prop="country">
                        <Input v-model="editData.productCountry" placeholder="Махсулот келтирилган давлат"></Input>
                    </FormItem>

                    <FormItem label="Қўшимча маълумотлар" prop="description">
                        <Input v-model="editData.productDesc" type="textarea" :autosize="{minRows: 2,maxRows: 5}" placeholder="Қўшимча маълумотлар..."></Input>
                    </FormItem>
                </Form>

                <template #footer>
                    <Button type="default" size="small" @click="editModal=false">Close</Button>
                    <Button type="primary" size="small" @click="editProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing...':'Edit tag'}}</Button>
                </template>
            </Modal>

            <!-- Delete tag modal -->
            <Modal v-model="showDeleteModal" width="360">
                <template #header>
                    <p style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                </template>
                <div style="text-align:center">
                    <p>Are you sure that you want to delete tag?</p>
                </div>
                <template #footer>
                    <Button type="error" size="large" long :loading="isDeleting" :disabled="isDeleting" @click="deleteTag">Delete</Button>
                </template>
            </Modal>

</template>

<script>
import StockHeader from './rmstock_header.vue'
export default {
    name: "report",
    components: {
        StockHeader:StockHeader
    },
    data(){
        return {
            data: {
                productName: '',
                productCode: '',
                productColor: '',
                productCountry: '',
                productDesc: '',
                productPicture: '',
            },
            addModal : false,
            editModal : false,
            isAdding : false,
            products: [],
            counter : 1,
            editData: {
                productName: '',
                productCode: '',
                productColor: '',
                productCountry: '',
                productDesc: '',
                productPicture: '',
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            deletingIndex: -1,
            avatarImage: '',

            prevMonthTotal: 0,
            inTotal: 0,
            outTotal: 0,
            lastMonthTotal:0,

        }
    },

    methods : {
        async addProduct(){ //Add tag
            if(this.data.productName.trim() == '') return this.err('Махсулот номи мажбурий.')
            const res = await this.callApi('post', 'app/get_products', this.data)
            if(res.status === 201) {
                this.products.unshift(res.data);
                this.s('Махсулот мувоффақиятли қўшилди!')
                this.addModal = false
                this.data.productName = ''
            } else {
                if(res.status == 422) {
                    if(res.data.errors.productName) {
                        this.err(res.data.errors.productName[0])
                    }
                } else {
                    this.swr()
                }
            }
        },

        async editProduct(){ //edit product
            if(this.editData.productName.trim() == '') return this.err('Махсулот номи мажбурий.')
            const res = await this.callApi('post', 'app/edit_product', this.editData)
            if(res.status === 200) {
                this.products[this.index].productName = this.editData.productName
                this.s('Tag has been edited successfully!')
                this.editModal = false
            } else {
                if(res.status == 422) {
                    if(res.editData.errors.productName) {
                        this.err(res.editData.errors.productName[0])
                    }
                } else {
                    this.swr()
                }
            }
        },

        showEditModal(product, index) {
            let productObj = {
                id: product.id,
                productName: product.productName
            }

            this.editData = productObj
            this.editModal = true
            this.index = index
        },

        async deleteTag() {
            this.isDeleting = true
            const res = await this.callApi("post", "app/delete_tag", this.deleteItem)
            if(res.status === 200) {
                this.tags.splice(this.deletingIndex,1)
                this.s('Tag has been deleted successfully')
            } else {
                this.swr();
            }

            this.isDeleting = false
            this.showDeleteModal = false
        },

        async showDeletingModal(tag, i){
            this.deleteItem = tag
            this.deletingIndex = i
            this.showDeleteModal = true
        },
    },

    async created(){
        // const res = await this.callApi('get', '/app/get_products')
        const dateObj = new Date();
        const today = dateObj.toLocaleDateString('ru-RU', { timeZone: 'UTC' });

        const res = await this.callApi('get', '/app/get_stock/' + today)

        if(res.status == 200) {

            this.products = Object.values( res.data )

            let prevMonthTotal = 0;
            let lastMonthTotal = 0;
            let inTotal = 0;
            let outTotal = 0;
            this.products.forEach(function (product, index) {

                if(product.prev_month_count && product.prev_month_price) {
                    prevMonthTotal += (product.prev_month_count * product.prev_month_price)
                }

                if(product.count && product.price) {
                    lastMonthTotal += product.count * product.price
                }

                if(product.inCount && product.inPrice) {
                    inTotal += product.inCount * product.inPrice
                }

                if(product.outCount && product.outPrice) {
                    outTotal += product.outCount * product.outPrice
                }



            })

            this.prevMonthTotal = prevMonthTotal
            this.lastMonthTotal = lastMonthTotal
            this.inTotal = inTotal
            this.outTotal = outTotal

        } else {
            this.swr()
        }

    }
}
</script>
