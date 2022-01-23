<template>
    <app-layout title="Dashboard">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex"
                    style="min-height: 650px; max-height: 650px;"
                >
                    <div
                        class="w-3/12 bg-gray-200 bg-opacity-25 border-r border-gray-200 overflow-auto"
                    >
                        <ul>
                            <li
                                class="p-6 text-lg text-gray-600 leading-7 hover:bg-gray-200 font-semibold border-b border-gray-200 hover:bg-opacity-50 hover:cursor-pointer"
                                v-for="user in users"
                                :key="user.id"
                                @click="() => {loadMessages(user.id)}"
                                :class="(userActive && userActive.id == user.id) ? 'bg-gray-200 bg-opacity-50' : ''"
                            >
                                <p class="flex items-center">
                                    {{ user.name }}
                                    <span
                                        v-if="user.notification"
                                        class="ml-2 w-2 h-2 bg-blue-500 rounded-full"
                                    ></span>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="w-9/12 flex flex-col justify-between">
                        <div class="w-full p-6 flex flex-col overflow-auto">
                            <div 
                            class="w-full mb-3 message"
                            v-for="msg in msgs"
                            :key="msg.id"
                            :class="msg.from == user.id ? 'text-right' : ''"
                            >
                                <p
                                    class="inline-block p-2 rounded-md"
                                    style="max-width: 75%"
                                    :class="msg.from == user.id ? 'messageFromMe' : 'messageToMe'"
                                >
                                    {{ msg.content }}
                                </p>
                                <span class="block mt-1 text-xs text-gray-500"
                                    >{{ msg.created_at }}</span
                                >
                            </div>

                        </div>

                        <div
                            class="w-full bg-gray-200 bg-opacity-25 p-6 border-t border-gray-200"
                            v-if="userActive"
                        >
                            <form v-on:submit.prevent="sendMessage">
                                <div class="flex rounded-md overflow-hidden border">
                                <input
                                    type="text"
                                    class="flex-1 px-4 py-2 text-sm focus:outline-none border-gray-300"
                                    v-model="message"
                                />
                                <button
                                    type="submit"
                                    class="px-4 py-2 text-white bg-indigo-500 hover:bg-indigo-600"
                                >
                                    Enviar
                                </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import { defineComponent, Vue } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Jetstream/Welcome.vue";
import moment from 'moment';
import store from '../store';

export default defineComponent({
    components: {
        AppLayout,
        Welcome,
    },
    data() {
        return {
            users: [],
            msgs: [],
            userActive: null,
            user: store.state.me,
            message: ''
        }
    },
    computed: {
        me() {
            return store.state.me
        }
    },
    methods: {
        scrollToBotton: function(){
            if (this.messages.leght) {
                document.querySelectorAll('.message:last-child')[0].scrollIntoView()
            }
        },
        loadMessages: async function(userId) {

            axios.get(`api/users/${userId}`).then(response => {
                this.userActive = response.data.user
            })
            
            await axios.get('api/messages/' + userId).then(response => {
                this.msgs = response.data.messages
            })

            const user = this.users.filter((user) => {
                if (user.id === userId){
                    return user
                }
            })

            if(user) {
                user[0].notification = false
            }
            
            this.scrollToBotton()
        },
        sendMessage: async function(){
            await axios.post('api/messages/store', {
                'content': this.message,
                'to': this.userActive.id 
            }).then(response => {
                this.msgs.push({
                    'from': this.user.id,
                    'to': this.userActive.id,
                    'content': this.message,
                    'created_at': new Date().toISOString(),
                    'updated_at': new Date().toISOString()
                })
                this.message = ''
            })
            this.scrollToBotton()
        }
    },
    filters: {
        formatDate: function(value){
        if(value) {
            return moment(value).format('DD/MM/YYYY HH:mm');
        }
}
    },
    mounted() {

        axios.get('api/users').then(response => {
            this.users = response.data.users
        })

        Echo.private(`user.${this.user.id}`).listen('.SendMessage', async (e) => {
            if (this.userActive && this.userActive === e.message.from) {
                await this.messages.push(e.message)
                this.scrollToBotton()
            } else {
                const user = this.users.filter((user) => {
                    if (user.id === e.message.from){
                        return user
                    }
                })

                if(user) {
                    user[0].notification = true
                }
            }
        })

    }
});
</script>

<style>
.messageFromMe {
    --tw-bg-opacity: 1;
    background-color: rgb(165 180 252 / var(--tw-bg-opacity));
    --tw-bg-opacity: 0.25;
}

.messageToMe {
    --tw-bg-opacity: 1;
    background-color: rgb(229 231 235 / var(--tw-bg-opacity));
    --tw-bg-opacity: 0.25;
}
</style>
