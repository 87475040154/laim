<template>
    <!-- После покупки подписки пользователь попадает на эту страницу-->
    <div>

        <!-- Если платеж прощел - покажем этот блок -->
        <div v-if="$route.path == '/bueAdsResult/success'" class="m-auto alert alert-success" style="width: 100%; max-width: 800px">

            <div v-if="updateDateLocaleStore.lang == 'kz'">
                <h4>Құттықтаймыз!</h4>
                <div>Сіз сәтті жарнама сатып алдыңыз.</div>
                <div>Жақын арада жарнама сіздің хабарландыруға қосылады.</div>
                <p>Егер сізде сұрақтар немесе мәселелер туындаса, біздің қолдауымызға хабарласыңыз.</p>
                <v-btn @click="$router.replace({name:'userAds', params: {author_id: authStore.user.id}})">Менің хабарландырулар</v-btn>

            </div>

            <div v-else>
                <h4> Поздравляем!</h4>
                <div> Вы успешно приобрели рекламу для вашего объявления.</div>
                <div> В ближайшее время реклама будет добавлена к вашему объявлению. </div>
                <p>Если у вас возникли вопросы или проблемы, пожалуйста, свяжитесь с нашей поддержкой.</p>
                <v-btn @click="$router.replace({name:'userAds', params: {author_id: authStore.user.id}})">Мои объявления</v-btn>
            </div>

        </div>


        <!-- Если платеж не прощел - покажем этот блок -->
        <div v-if="$route.path == '/bueAdsResult/error'" class="m-auto alert alert-danger" style="width: 100%; max-width: 800px">

            <div v-if="updateDateLocaleStore.lang == 'kz'">
                <h4>Өкінішке орай, төлем кезінде қате пайда болды</h4>
                <p>Төлем сәтті аяқталмады. Мәліметтеріңізді тексеріп, әрекетті қайталаңыз.</p>
                <p>Егер сізде сұрақтар немесе мәселелер туындаса, біздің қолдауымызға хабарласыңыз.</p>
                <v-btn @click="$router.replace({name:'userAds', params: {author_id: authStore.user.id}})">Жарнаманы тағы бір рет сатып алыңыз</v-btn>
            </div>

            <div v-else>
                <h4>К сожалению, произошла ошибка во время оплаты</h4>
                <p>Платеж не был завершен успешно. Пожалуйста, проверьте свои данные и попробуйте еще раз.</p>
                <p>Если у вас возникли вопросы или проблемы, пожалуйста, свяжитесь с нашей поддержкой.</p>
                <v-btn @click="$router.replace({name:'userAds', params: {author_id: authStore.user.id}})">Оформить покупку рекламы еще раз</v-btn>
            </div>

        </div>

    </div>
</template>

<script>

//Store авторизации
import { useAuthStore } from "../../../../stores/auth";
import { useUpdateDateLocaleStore } from "../../../../stores/updateDateLocale";

export default {
    name: "Index",

    data(){
        return {
            authStore: useAuthStore(),
            updateDateLocaleStore: useUpdateDateLocaleStore()
        }
    },




}
</script>

<style scoped>

</style>
