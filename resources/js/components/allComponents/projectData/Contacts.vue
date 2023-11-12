<template>
    <!-- Наши контакты-->

    <v-card max-width="600" style="box-shadow: none; border: 1px solid #dce1e6; border-radius: 6px"
            class="p-1 pb-0 m-2 mx-sm-auto"
    >
        <!-- Заголовок -->
        <h2 class="text-center fw-bold">
            <span v-if="updateDateLocaleStore.lang == 'ru'">Наши контакты</span>
            <span v-if="updateDateLocaleStore.lang == 'kz'">Біздің байланыстарымыз</span>
            <span v-if="updateDateLocaleStore.lang == 'en'">Our contacts</span>
        </h2>

        <div class="p-2">
            <div class="my-2">
                <span v-if="updateDateLocaleStore.lang == 'ru'">Адрес: Казахстан, г.Петропавловск, ул. Нурсултана Назарбаева 122, кб. 203</span>
                <span v-if="updateDateLocaleStore.lang == 'kz'">Мекен-жайы: Қазақстан, Петропавл қ., Нұрсұлтан Назарбаев көш. 122, кб. 203</span>
                <span v-if="updateDateLocaleStore.lang == 'en'">Address: Kazakhstan, Petropavlovsk,  Nursultan Nazarbayev st. 122, kb. 203</span>
            </div>
            <div class="my-2">
                <span v-if="updateDateLocaleStore.lang == 'ru'">Почта: Ismail.Magomedhajiev@gmail.com</span>
                <span v-if="updateDateLocaleStore.lang == 'kz'">Пошта: Ismail.Magomedhajiev@gmail.com</span>
                <span v-if="updateDateLocaleStore.lang == 'en'">Mail: Ismail.Magomedhajiev@gmail.com</span>
            </div>
            <div class="my-2">
                <span v-if="updateDateLocaleStore.lang == 'ru'">Телефон: +7 (747) 504-01-54</span>
                <span v-if="updateDateLocaleStore.lang == 'kz'">Телефон: +7 (747) 504-01-54</span>
                <span v-if="updateDateLocaleStore.lang == 'en'">Phone: +7 (747) 504-01-54</span>
            </div>
        </div>

    </v-card>


</template>

<script>
import {useUpdateDateLocaleStore} from "../../../stores/updateDateLocale";

export default {
    name: "Contacts",

    data(){
        return {
            updateDateLocaleStore: useUpdateDateLocaleStore(),
        }
    }
}
</script>

<style scoped>

</style>
