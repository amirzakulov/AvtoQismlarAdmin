<template>
    <div class="row pl-2 pr-4 ml-0 mr-0">
        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_one">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">$ 48 000</p>

                    <p class="_1adminOverveiw_card_left_title">Хом ашё</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="ios-paper" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">29</p>

                    <p class="_1adminOverveiw_card_left_title">Хом ашё тури</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="ios-paper-outline" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3 d-none">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">29</p>

                    <p class="_1adminOverveiw_card_left_title">Features News</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="md-copy" />
                </div>
            </div>
        </div>

        <div class="col-md-3 col-md-3 d-none">
            <div class="_1adminOverveiw_card _box_shadow _border_radious _mar_b30 _1adminOverveiw_bg_two">
                <div class="_1adminOverveiw_card_left">
                    <p class="_1adminOverveiw_card_left_num">29</p>

                    <p class="_1adminOverveiw_card_left_title">Card News</p>
                </div>
                <div class="_1adminOverveiw_card_right">
                    <Icon type="md-list-box" />
                </div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs ml-4">
        <li class="nav-item">
            <router-link :to="{ name: 'rawMaterials' }" class="nav-link"
                         :class="{ 'router-link-exact-active sidebar-link': subIsActive('/rawMaterials') }"
            >Махсулотлар</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'rmIncome'}"
                         :class="{ 'router-link-exact-active': subIsActive('/raw-materials/income') }"
                         class="nav-link">Кирим</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'rmOutcome'}"
                         :class="{ 'router-link-exact-active': subIsActive('/raw-materials/outcome') }"
                         class="nav-link">Сотилганлар</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'report' }" class="nav-link">Омбор</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    name: "rmstock_header",
    data() {
        return {
            activeCategory: 0,
        }
    },
    methods: {
        subIsActive(input) {
            const paths = Array.isArray(input) ? input : [input]
            return paths.some(path => {
                return this.$route.path.indexOf(path) === 0 // current path starts with this path string
            })
        }
    },

}
</script>

