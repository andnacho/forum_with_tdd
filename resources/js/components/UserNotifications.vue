<template>
    <div class="nav-link" v-if="notifications.length">
        <li class="dropdown">
            <a href="#" class="" data-toggle="dropdown">
                <span class="fa fa-bell"></span>
            </a>

            <ul class="dropdown-menu">
                <li class="dropdown-item" v-for="notification in notifications">
                    <a :href="notification.data.link"
                       v-text="notification.data.message"
                       @click="markAsRead(notification)"></a>
                </li>
            </ul>
        </li>
    </div>
</template>

<script>
    export default {
        name: "UserNotifications",
        data(){
            return {
                notifications: false
            }
        },

        methods: {
            markAsRead(notification) {
                axios.delete(/profiles/ + window.App.user.name + "/notifications/" + notification.id);
            }
        },

        created() {
            axios.get("/profiles/" + window.App.user.name + "/notifications")
                .then(response => this.notifications = response.data);
        }
    }
</script>

<style scoped>

</style>
