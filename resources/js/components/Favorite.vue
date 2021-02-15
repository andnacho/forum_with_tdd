<template>
    <button type="submit" :class="classes" class="btn btn-default text-sm-left" @click="toggle">
        <span><i class="fa fa-heart"></i></span>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data () {
            return {
                favoritesCount: this.reply.favoritesCount,
                isFavorited: this.reply.isFavorited
            }
        },
        methods: {
            toggle() {
                this.isFavorited ? this.destroy() : this.create();
            },
            create() {
                axios.post(this.endpoint);
                this.favoritesCount++;
                this.isFavorited = true;
            },
            destroy() {
                axios.delete(this.endpoint);
                this.favoritesCount--;
                this.isFavorited = false;
            }
        },
        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
            },
            endpoint() {
                return '/replies/' + this.reply.id + '/favorites';
            }
        }

    }
</script>

<style scoped>

</style>
