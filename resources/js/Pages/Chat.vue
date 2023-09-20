<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref, onMounted, nextTick } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'primevue/usetoast'

const props = defineProps({
    'chats': '',
    'ai_models': '',
    'response': '',
})

const scrollToBottom = () => {
    window.scrollTo({left: 0, top: document.body.scrollHeight, behavior: 'smooth'});
}

const chats = ref(props.chats);
const input = ref(null);
const ai_models = ref([]);

for (const key in props.ai_models) {
    ai_models.value.push({'title': props.ai_models[key]['title'], 'data': props.ai_models[key], 'model': props.ai_models[key]['model']});
}

let form = useForm({'prompt': '', 'model': 'text-davinci-001', 'title': 'Davinic Text Bot'});
const toast = useToast();


const submit = (form) => {
    scrollToBottom()
    form.post(route('chat.ask'), {
        preserveScroll: true,
        onStart: () => {
            toast.add({severity:'warn', summary: 'I\'m Thinking', detail:'Let me get back to you on that 🤔!', life: 4000});
            chats.value.push({prompt: form.prompt, answer: '', model: form.model})
        },
        onSuccess: () => {
            typeWriter()
            form.reset();
            nextTick(() => {
                input.value.$el.focus()
            });

        },
        onError: (data) => {
            toast.add({severity:'error', summary: 'Uh Oh', detail:'Seems like you\'ve reached your limit for today 😅', life: 4000});
            let index = chats.value.length - 1
            chats.value[index].answer = data.limit_reached
            nextTick(() => {
                input.value.$el.focus()
            });

        }
    })
}


onMounted(() => {
    nextTick(() => {
        input.value.$el.focus()
        scrollToBottom()
    });
})

window.onload = () => {
    scrollToBottom()
}

const typeWriterIndex = ref(0);

function typeWriter() {
    let index = chats.value.length - 1
    if (typeWriterIndex.value < props.response.length) {
        chats.value[index].answer += props.response.charAt(typeWriterIndex.value);

        typeWriterIndex.value++;
        setTimeout(typeWriter, 25);
    } else {
        typeWriterIndex.value = 0
    }
    scrollToBottom()

}


</script>

<template>
    <AppLayout title="Chat">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat with me 😁
            </h2>
        </template>

        <div v-for="(chat, index) in chats" class="bg-gray-300 p-2 overflow-auto overflow-x-hidden break-words max-w-full  max-h-auto mb-4">
           <pre class="bg-gray-100 italic p-2 whitespace-pre-wrap response" v-html="chat.prompt + chat.answer"></pre>
        </div>
        <div id="bottom" class="hidden">Scroll to bottom</div>
        <div class="mt-12 flex justify-center">
            <div v-if="form.errors.limit_reached">{{ form.errors.limit_reached }}</div>
            <form @submit.prevent="submit(form)" class="bg-gray-300 opacity-100 py-4 p-input-group p-button-set fixed bottom-0 w-full flex justify-center">
                <p-dropdown class="md:w-auto w-1/5" placeholder="Select an AI model" v-model="form.model" :options="ai_models" optionLabel="title" optionValue="model">
                    <template #option="{ option }">
                        <div v-tooltip.left="option.data?.description">{{ option.data.title }}</div>
                    </template>
                </p-dropdown>
                <p-input-text ref="input" :class="{'p-invalid': form.errors.limit_reached}" :disabled="form.processing"  v-model="form.prompt" v-on:focus="scrollToBottom" placeholder="Ask me something 😎" class="md:w-2/5 w-2/5" />
                <p-button :loading="form.processing" icon="pi pi-send" :class="['p-button-secondary', {'p-invalid': form.errors.limit_reached}]" type="submit"/>
            </form>
        </div>
    </AppLayout>
</template>
