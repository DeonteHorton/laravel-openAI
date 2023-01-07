<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    'chats': ''
})

const scrollToBottom = () => {
    window.scrollTo({left: 0, top: document.body.scrollHeight, behavior: 'smooth'});
}


const chats = ref([
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: 'a language' },
    { prompt: 'Who are you?', answer: 'The one and only chat gpt' },
    { prompt: 'What is php', answer: '' },

])

onMounted(() => {
    let bottom = document.getElementById('bottom')

    const scroll = () => {
        window.scrollTo( 0, document.body.scrollHeight);
    }

    scroll()
})

</script>

<template>
    <AppLayout title="Chat">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat with me 😁
            </h2>
        </template>

        <div v-for="chat in chats" class="bg-gray-300 p-3 max-w-full overflow-hidden max-h-96 mb-4">
           <div class="bg-gray-100 p-2 border-b-4">
                {{ chat.prompt }}
           </div>
           <div class="bg-gray-50 p-2">
            <div v-if="chat.answer">{{ chat.answer }}</div>
            <p-skeleton v-else width="100%" height="2rem" borderRadius="16px" />
           </div>
        </div>
        <div id="bottom" class="hidden">Scroll to bottom</div>
        <div class="mt-12 flex justify-center">
            <div class="bg-gray-300 opacity-100 py-4 p-input-group p-button-set fixed bottom-0 w-full flex justify-center">
                <p-input-text v-on:focus="scrollToBottom" placeholder="Ask me something 😎" class="w-4/5" />
                <p-button icon="pi pi-send" class="p-button-secondary" />
            </div>
        </div>
    </AppLayout>
</template>
