<template>
    <div style="display: flex; justify-content: center; padding: 20px 0; background: #f0f2f5; min-height: 100vh;">
        <!-- контейнер виртуального списка с прокруткой -->
        <div ref="scrollRef" style="width: 100%; max-width: 600px; height: 100vh; overflow-y: auto; padding: 10px;">
            <!-- виртуальная область -->
            <div :style="{ height: `${totalSize}px`, position: 'relative' }">
                <div
                    v-for="virtualRow in virtualRows"
                    :key="virtualRow.index"
                    class="ListItem"
                    :style="{
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: `${virtualRow.size}px`,
            transform: `translateY(${virtualRow.start}px)`,
            display: 'flex',
            padding: '12px',
            boxSizing: 'border-box',
            borderBottom: '1px solid #eee',
            backgroundColor: '#fff',
            borderRadius: '6px',
            marginBottom: '8px',
            boxShadow: '0 1px 4px rgba(0,0,0,0.08)'
          }"
                >
                    <img
                        :src="posts[virtualRow.index].image"
                        style="width: 80px; height: 80px; object-fit: cover; margin-right: 12px; border-radius: 4px"
                    />
                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: center">
                        <strong>{{ posts[virtualRow.index].title }}</strong>
                        <span style="color: #2c3e50; margin-top: 4px">{{ posts[virtualRow.index].price }}</span>
                        <span style="font-size: 12px; color: #7f8c8d; margin-top: 2px">{{ posts[virtualRow.index].date }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useVirtualizer } from '@tanstack/vue-virtual'

const scrollRef = ref<HTMLElement | null>(null)

const posts = Array.from({ length: 2000 }, (_, i) => ({
    id: i + 1,
    image: `https://picsum.photos/seed/${i + 1}/80/80`,
    title: `Сдам 1 ком. квартиру №${i + 1}`,
    price: `${Math.floor(Math.random() * 50000) + 10000} ₽`,
    date: `Дата публикации: ${new Date(Date.now() - i * 3600 * 1000).toLocaleDateString()}`
}))

const rowVirtualizer = useVirtualizer({
    count: posts.length,
    getScrollElement: () => scrollRef.value, // virtualizer слушает контейнер
    estimateSize: () => 150,
    overscan: 30 // рендерим буфер выше и ниже видимой области
})

const virtualRows = computed(() => rowVirtualizer.value.getVirtualItems())
const totalSize = computed(() => rowVirtualizer.value.getTotalSize())
</script>

<style scoped>
.ListItem:hover {
    background-color: #fafafa;
}
</style>
