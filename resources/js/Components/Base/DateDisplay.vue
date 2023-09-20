<script setup>
import { ref, onMounted, nextTick } from 'vue';
import moment from 'moment';

const props = defineProps({
    'date': ''
})

const date = ref(moment(props.date).format('MMMM Do YYYY, h:mm:ss a'));

</script>

<template>
    <time>
        {{ date }}
    </time>
</template>
