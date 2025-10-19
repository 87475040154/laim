<template>

    <!-- В этом компоненте - Вывожу весь динамический контент -->
    <main class="container-lg p-0 pb-5 pb-lg-0 mb-5 mb-lg-0">

        <!-- Внутренние страницы -->
        <router-view></router-view>

    </main>

</template>

<script>

export default {
    name: "MainComponent",

    beforeRouteEnter(to, from, next) {
        if (to.path === '/' && Object.keys(to.params).length === 0 && Object.keys(to.query).length === 0) {
            next('/allAds/Kvartira');
        } else {
            next();
        }
    }

}
</script>
