<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";
let props = defineProps({
    users: Object
})

let users = ref(props.users);

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    const options = {
        year: 'numeric',
        month: 'short',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: true
    };
    const formattedDate = date.toLocaleString('en-US', options);
    return formattedDate;
}

onMounted(() => {
    window.Echo.channel('user')
        .listen('.user-registered', (event) => {
            console.log('Event received:', event);
            users.value = event.user;
        });
});
</script>

<template>
    <Head title="Users" />
    <div
        class="w-full flex justify-center p-[24px] min-h-screen">
        <div class="overflow-x-auto w-full">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <!-- row 1 -->
                <tr 
                    v-for="item of users.data"
                    class="bg-base-200">
                    <th>{{ item?.id }}</th>
                    <td>{{ item?.name }}</td>
                    <td>{{ item?.email }}</td>
                    <td>{{ formatDate(item.created_at) }}</td>
                </tr>
                </tbody>
            </table>
            <div
                v-if="users.data.length != 0"
                class="w-full flex justify-center items-center mt-5">
                <div class="join">
                    <Link 
                        :href="link?.url"
                        v-for="link of users.links"
                        :class="{'bg-red-300' : link.active}"
                        class="join-item btn" v-html="link.label">
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
