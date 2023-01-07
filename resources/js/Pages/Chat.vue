<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { useToast } from 'primevue/usetoast'

const props = defineProps({
    'chats': '',
    'response': '',
})

const scrollToBottom = () => {
    window.scrollTo({left: 0, top: document.body.scrollHeight, behavior: 'smooth'});
}

const chats = ref(props.chats);

let form = useForm({'prompt': ''})
const toast = useToast();


const submit = (form) => {

    form.post(route('chat.ask'), {
        preserveScroll: true,
        onStart: () => {
            toast.add({severity:'warn', summary: 'I\'m Thinking', detail:'Let me get back to you on that 🤔!', life: 4000});
            chats.value.push({prompt: form.prompt, answer: ''})
        },
        onSuccess: () => {
            let index = chats.value.length - 1
            chats.value[index].answer = props.response
            form.reset();
        },
        onError: (data) => {
            toast.add({severity:'error', summary: 'Uh Oh', detail:'Seems like you\'ve reached your limit for today 😅', life: 4000});
            let index = chats.value.length - 1
            chats.value[index].answer = data.limit_reached
        }
    })
}


onMounted(() => {
    const scroll = () => {
        window.scrollTo({left: 0, top: document.body.scrollHeight, behavior: 'smooth'});
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

        <div v-for="chat in chats" class="bg-gray-300 p-3 overflow-auto overflow-x-hidden break-words  max-w-full  max-h-auto mb-4">
           <div class="bg-gray-100 p-2 border-b-4">
                {{ chat.prompt }}
           </div>
           <div class="bg-gray-50 p-2">
            <pre class="whitespace-pre-wrap" v-if="chat.answer">{{ chat.answer }}</pre>
            <p-skeleton v-else width="100%" height="2rem" borderRadius="16px" />
           </div>
        </div>
        <div id="bottom" class="hidden">Scroll to bottom</div>
        <div class="mt-12 flex justify-center">
            <div v-if="form.errors.limit_reached">{{ form.errors.limit_reached }}</div>
            <form @submit.prevent="submit(form)" class="bg-gray-300 opacity-100 py-4 p-input-group p-button-set fixed bottom-0 w-full flex justify-center">
                <p-input-text :class="{'p-invalid': form.errors.limit_reached}" :disabled="form.processing"  v-model="form.prompt" v-on:focus="scrollToBottom" placeholder="Ask me something 😎" class="w-4/5" />
                <p-button :loading="form.processing" icon="pi pi-send" :class="['p-button-secondary', {'p-invalid': form.errors.limit_reached}]" type="submit"/>
            </form>
        </div>
    </AppLayout>
</template>
