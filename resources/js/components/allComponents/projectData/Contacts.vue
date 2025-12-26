<template>
    <!-- Наши контакты-->

    <v-card max-width="600" style="box-shadow: none; border: 1px solid #dce1e6; border-radius: 6px"
            class="p-1 pb-0 m-2 mx-sm-auto"
    >
        <!-- Заголовок -->
        <h2 class="text-center fw-bold">
            <span v-if="langStore.lang == 'ru'">Наши контакты</span>
            <span v-if="langStore.lang == 'kz'">Біздің байланыстарымыз</span>
        </h2>

        <div class="p-2">
            <div class="my-2">
                <span v-if="langStore.lang == 'ru'">Адрес: Казахстан, г.Петропавловск, ул. Нурсултана Назарбаева 122, кб. 203</span>
                <span v-if="langStore.lang == 'kz'">Мекен-жайы: Қазақстан, Петропавл қ., Нұрсұлтан Назарбаев көш. 122, кб. 203</span>
            </div>
            <div class="my-2">
                <span v-if="langStore.lang == 'ru'">Почта: Ismail.Magomedhajiev@gmail.com</span>
                <span v-if="langStore.lang == 'kz'">Пошта: Ismail.Magomedhajiev@gmail.com</span>
            </div>
            <div class="my-2">
                <span v-if="langStore.lang == 'ru'">Телефон: +7 (747) 504-01-54</span>
                <span v-if="langStore.lang == 'kz'">Телефон: +7 (747) 504-01-54</span>
            </div>
        </div>

    </v-card>


</template>

<script>
import {useLangStore} from "../../../stores/lang";

export default {
    name: "Contacts",

    data(){
        return {
            langStore: useLangStore(),
        }
    }
}
</script>

<style scoped>

</style>
<!--54-->
