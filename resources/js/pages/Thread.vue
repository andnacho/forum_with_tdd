<script>
    import Replies from './../components/Replies.vue';
    import SubscribeButton from './../components/SubscribeButton.vue';

    export default {
        props: ['thread'],
        components: {
            Replies,
            SubscribeButton
        },
        data () {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                }
            }
        },
        methods: {
            cancel () {
                this.form = {
                    title: this.thread.title,
                    body: this.thread.body
                };

                this.editing = false;
            },
            toggleLock () {
                this.locked = !this.locked;

                let uri = `/locked-threads/${this.thread.slug}`;
                axios[this.locked ? 'post' : 'delete'](uri);
            },
            update () {

                let uri = `/threads/${this.thread.channel.slug}/${this.thread.slug}`;

                axios.patch(uri, {
                    title: this.form.title,
                    body: this.form.body
                }).then(() => {
                    flash('You thread have updated');
                    this.thread.title = this.form.title;
                    this.thread.body = this.form.body;
                });

                this.editing = false;
            }
        }
    }
</script>

<style scoped>

</style>
