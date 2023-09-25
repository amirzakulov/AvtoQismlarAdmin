<template>
    <ul class="nav nav-tabs ml-4">
        <li class="nav-item">
            <router-link :to="{ name: 'orders', params: {status: ''} }" class="nav-link"
                         :class="{ 'router-link-exact-active': orderStatus == 0 }">Yangilar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'orders', params: {status: 1} }"
                         :class="{ 'router-link-exact-active': orderStatus == 1 }"
                         class="nav-link">Tasdiqlanganlar</router-link>
        </li>
        <li class="nav-item">
            <router-link :to="{ name: 'orders', params: {status: 2} }"
                         :class="{ 'router-link-exact-active': orderStatus == 2 }"
                         class="nav-link">Bajarilganlar</router-link>
        </li>
    </ul>
</template>

<script>
export default {
    name: "orderHeader",
    props: ['orderStatus'],
}
</script>

