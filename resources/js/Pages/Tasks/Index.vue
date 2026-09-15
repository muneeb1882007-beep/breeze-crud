<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    tasks: Array
});

const form = useForm({
    title: '',
    description: ''
});

const editingTask = ref(null);

const submit = () => {
    if (editingTask.value) {
        form.put(route('tasks.update', editingTask.value.id), {
            onSuccess: () => resetForm()
        });
    } else {
        form.post(route('tasks.store'), {
            onSuccess: () => resetForm()
        });
    }
};

const editTask = (task) => {
    editingTask.value = task;
    form.title = task.title;
    form.description = task.description;
};

const deleteTask = (id) => {
    if (confirm('Are you sure?')) {
        form.delete(route('tasks.destroy', id));
    }
};

const resetForm = () => {
    editingTask.value = null;
    form.reset();
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto p-6">
            <h1 class="text-2xl font-bold mb-4">Manage Tasks</h1>

            <!-- Form -->
            <form @submit.prevent="submit" class="mb-6 bg-white p-4 rounded shadow">
                <div class="mb-2">
                    <input v-model="form.title" type="text" placeholder="Title" class="w-full border p-2 rounded" required />
                </div>
                <div class="mb-2">
                    <textarea v-model="form.description" placeholder="Description" class="w-full border p-2 rounded"></textarea>
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    {{ editingTask ? 'Update' : 'Add Task' }}
                </button>
                <button v-if="editingTask" @click="resetForm" type="button" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            </form>

            <!-- Table -->
            <div class="bg-white rounded shadow overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-3">Title</th>
                            <th class="p-3">Description</th>
                            <th class="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id" class="border-b">
                            <td class="p-3">{{ task.title }}</td>
                            <td class="p-3">{{ task.description }}</td>
                            <td class="p-3">
                                <button @click="editTask(task)" class="text-blue-500 mr-2">Edit</button>
                                <button @click="deleteTask(task.id)" class="text-red-500">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>