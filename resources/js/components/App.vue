<template>
    <div>
        <div v-if="isLoggedIn">
            <!--========== ADMIN SIDE MENU one ========-->
            <div class="_1side_menu" >
                <div class="_1side_menu_logo">
                    <h3 style="text-align:center; color: #fff;">Avto Qismlar</h3>
                </div>

                <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
                <div class="_1side_menu_content">
                    <!--~~~ MENU LIST ~~~~~~-->
                    <div class="_1side_menu_list">
                        <ul class="_1side_menu_list_ul">

                            <template v-for="(permission, i) in permissions" :key="i" v-if="permissions.length">
                                <li v-if="!permission.subPage && permission.read">
                                    <router-link :to="permission.name" custom v-slot="{ href, navigate, isActive, isExactActive }">
                                        <a :class="{ 'router-link-exact-active sidebar-link': subIsActiveFirst(permission.name) }" :href="href" @click="navigate">
                                            <Icon :type="permission.icon" size="24" />
                                            <div style="font-size: 12px;">{{ permission.resourceName }}</div>
                                        </a>
                                    </router-link>
                                </li>
                            </template>

                        </ul>
                    </div>
                </div>
            </div>
            <!--========== ADMIN SIDE MENU ========-->

            <!--========= HEADER ==========-->
            <div class="header">
                <div class="_2menu _box_shadow pr-0">
                    <div class="_2menu_logo1 w-100">
                        <ul class="open_button">
                            <!-- <li><Icon type="ios-list" size="20" /></li> -->
                            <li class="text-center">
                                Do'kon: {{ this.loggedUser.storeName }}
                            </li>
                            <li class="float-right text-center">
                                <a href="/logout" class="pb-5">
                                    <Icon type="ios-log-out" size="20" color="#ff0000" />
                                    <div>Chiqish</div>
                                </a>
                            </li>
                            <li class="float-right text-center">
                                <Icon type="ios-person" size="20" />
                                <div>{{ this.user.fullName }}</div>
                            </li>
                        </ul>
                    </div>


                </div>

            </div>
        </div>
        <div class="content pl-0 pr-0">
            <div class="container-fluid pl-0 pr-0">
                <router-view :key="$route.path"></router-view>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ['user', 'permissions'],
    data(){
        return {
            isLoggedIn: false,
            loggedUser: {},
        }
    },
    methods: {
        subIsActiveFirst(input) {

            const pathArray = window.location.pathname.split("/");
            const urlSegment1 = pathArray[1]
            if(input == "/" && urlSegment1 != "") {
                input = "/home"
            }

            const paths = Array.isArray(input) ? input : [input]
            return paths.some(path => {
                return this.$route.path.indexOf(path) === 0
            })
        },

    },
    async created(){

        if(this.user) {
            this.isLoggedIn = true
            const loggedUser = await this.callApi('get', '/app/logged_user')
            this.loggedUser = loggedUser.data

        } else {
            this.isLoggedIn = false
        }
    }
}
</script>
