<template>
    <div>
        <button :class="classes" @click="subscribe" v-text="subscribeText">Suscribe</button>
    </div>
</template>

<script>
    export default {
        name: "SubscribeButton",
        props: ['active'],
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-info' : 'btn-primary'];
            },
            subscribeText() {
                return this.active ? 'Subscribed' : 'Subscribe';
            }
        },
        data() {
            return {

            }
        },
        methods: {
            subscribe() {
                axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions');

                flash(this.active ? 'Unsubscribed' : 'Subscribed');

                this.active = ! this.active;


            }
        }
    }
</script>

<style scoped>

</style>
