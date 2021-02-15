<template>
    <div>
        <div class="card mb-3" v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changePage="fetch"></paginator>

        <new-reply @created="add" v-if="! $parent.locked"></new-reply>
        <p v-else>This thread has been locked. No more replies are allowed</p>

    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collection from '../mixins/collection.js';

    export default {
        components: {Reply, NewReply},
        mixins: [collection],
        data() {
            return {
                dataSet: false
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },
            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return location.pathname + '/replies?page=' + page;
            }
        }
    }
</script>

<style scoped>

</style>
