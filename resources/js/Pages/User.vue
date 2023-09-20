<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DateDisplay from '@/Components/Base/DateDisplay.vue';
import { Inertia } from '@inertiajs/inertia';
import { ref, onMounted, computed } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { debounce } from 'lodash'

import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'
import InputError from '../Components/InputError.vue'

const props = defineProps({
    'items': '',
    'roles': '',
})

const headers = [
    { text: 'Name', field: 'name' },
    { text: 'Email', field: 'email' },
    { text: 'Daily Chat Limit', field: 'chat_limit' },
    { text: 'Total Chats', field: 'ai_chats_count' },
    { text: 'Role', field: 'role.display_name' },
]

const pagination = computed( () => ({
    total: props.items?.meta.total,
    currentPage: props.items?.meta.current_page,
    perPage: props.items?.meta.per_page
}));

const toast = useToast();
const confirm = useConfirm();

const openDialog = ref(false)
const formFields = {
    id: null,
    name: '',
    email: '',
    chat_limit: 0,
    role_id: '',
    password: '',
}

const form = ref(useForm(formFields))

const submit = (form) => {
    if (form.id) {
        form.put(route('user.update', form.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({severity: 'success', detail: 'Success' , summary: 'User Updated Successfully!', life: 300})
                openDialog.value = false
            }
        })
    } else {
        form.post(route('user.create'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add({severity:'success', detail: 'Success', summary: 'User Saved Successfully!', life: 300})
                openDialog.value = false
            }
        })
    }
}

const editUser = (user) => {
    openDialog.value = true
    form.value = useForm({
        ...user,
        password: ''
    })
}

const deleteUser = ( event, item ) => {
    let form = useForm(item)
    confirm.require({
        target: event.currentTarget,
        message: `Confirming this will delete user ${item.name}`,
        icon: 'pi pi-trash',
        acceptLabel: "Delete",
        rejectLabel: "Cancel",
        acceptClass: "p-button-danger",
        rejectClass: "p-button-info",
        accept: () => {
            form.delete(route('user.destroy', form.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.add({severity:'success', summary: 'Success', detail:'User Deleted Successfully', life: 3000});
                    form.reset()
                    form.clearErrors();
                }
            })
            },
        reject: () => {
            toast.add({severity:'info', summary: 'Canceled', detail:`Canceled deleting user ${item.name}`, life: 3000});
            form.reset()
            form.cancel();
        }
    })
}


const debounceHandler = debounce((event) => {
    Inertia.get(
        window.location.pathname,
        {
            page: (event.page + 1) || pagination.value.currentPage,
            perPage: event.rows || pagination.value.perPage,
        },
        {
            preserveScroll: true,
            replace: true,
            preserveState: true
        }
    )
}, 200)

</script>

<template>
    <AppLayout title="Chat">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users
            </h2>
        </template>

        <p-datatable
            :value="props.items.data"
            stripedRows
            showGridlines
            dataKey="id"
            :paginator="true"
            :rows="pagination.perPage"
            :totalRecords="pagination.total"
            :lazy="true"
            @page="($event) => {
                debounceHandler($event);
            }"
            :rowsPerPageOptions="[15,45,60]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            currentPageReportTemplate="Showing {first}-{last} Total: {totalRecords}"
        >
            <template #header>
                <div class="text-right">
                    <p-button @click="openDialog = true" label="Create User" icon="pi pi-plus" class="p-button-sm p-button-info" />
                </div>
            </template>


            <p-column v-for="{ text, field } in headers" :header="text" :field="field" />
            <p-column header="Actions" field="actions">
                <template #body="{ data }">
                    <div class="flex items-center justify-evenly">
                        <p-button @click="editUser(data)" icon="pi pi-pencil" class="p-button-text p-button-secondary p-button-sm"/>
                        <p-button @click.prevent="deleteUser($event, data, $event.currentTarget)" icon="pi pi-trash" class="p-button-text p-button-secondary p-button-sm"/>
                    </div>
                </template>
            </p-column>
            <p-column header="Created At" field="created_at">
                <template #body="{ data }">
                    <DateDisplay class="mx-auto" :date="data.created_at" />
                </template>
            </p-column>
        </p-datatable>

        <p-dialog
            :header="form.id ? 'Edit User' : 'Create User'"
            v-model:visible="openDialog"
            :modal="true"
            @after-hide="() => {
                form = useForm(formFields)
            }"
        >
            <form @submit.prevent="submit(form)" class="flex flex-col space-x-3 space-y-2 p-2 items-center">
                <div class="flex flex-col">
                    <label for="name" class="text-lg font-semibold">Name</label>
                    <p-input-text aria-label="name" :class="{'p-invalid': form.errors.name}" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="flex flex-col">
                    <label for="email" class="text-lg font-semibold">Email</label>
                    <p-input-text aria-label="email" v-model="form.email" :class="{'p-invalid': form.errors.email}" />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="flex flex-col">
                    <label for="email" class="text-lg font-semibold">Chat Limit</label>
                    <p-input-number showButtons :min="0" :max="30" aria-label="email" v-model="form.chat_limit" :class="{'p-invalid': form.errors.chat_limit}" />
                    <InputError :message="form.errors.chat_limit" />
                </div>
                <div class="flex flex-col">
                    <label for="role" class="text-lg font-semibold">Role</label>
                    <p-dropdown aria-label="role" v-model="form.role_id" :options="props.roles" optionLabel="display_name" optionValue="id" :class="{'p-invalid': form.errors.chat_limit}" />
                    <InputError :message="form.errors.role_id" />
                </div>
                <div class="flex flex-col">
                    <label for="password" class="text-lg font-semibold">Password</label>
                    <p-input-text type="password" aria-label="password" v-model="form.password" :class="{'p-invalid': form.errors.password}" />
                    <InputError :message="form.errors.password" />
                </div>
                <button type="submit" class="hidden"></button>
            </form>

            <template #footer>
                <div class="flex justify-end">
                    <p-button @click="openDialog = false" :loading="form.processing" label="Cancel" class="p-button-danger"/>
                    <p-button @click="submit(form)" :loading="form.processing" :label="form.processing ? 'Submitting...' : 'Submit'" class="p-button-info"/>
                </div>
            </template>
        </p-dialog>

    </AppLayout>
</template>
