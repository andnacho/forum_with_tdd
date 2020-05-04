<template>
    <div>
        <div class="row my-3" v-if="signedIn">
            <div class="col-md-12">
                <div class="form-group">
                    <vue-tribute :options="tributeOptions">
                        <text-editor name="body" v-model="body" placeholder="Have something to say?" :shouldClear="completed" ref="trix"></text-editor>
                    </vue-tribute>
                </div>

                <button type="submit"
                        class="btn btn-primary"
                        @click="addReply">Post the thing...
                </button>


            </div>

        </div>
        <p class="text-center" v-else>
            Please <a href="/login">Sign in</a> to participate
        </p>
    </div>
</template>

<script>
    import VueTribute from 'vue-tribute';

    export default {
        data() {
            return {
                body: '',
                tributeOptions: {},
                completed: false,
            }
        },
        components: {
            VueTribute
        },

        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                    .catch(error => {
                        console.log(error.response.data);
                        if (error.response.data.errors) {
                            flash(error.response.data.errors.body[0], 'danger', 'Error!');
                        } else {
                            flash(error.response.data, 'danger', 'Error!');
                        }
                    })
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted.', 'success', 'Success!');

                        this.completed = true;

                        this.$emit('created', data);
                    });
            },
            noMatchFound() {
                console.log("No matches found!");
            }
        },

        beforeMount() {
            var me = this;
            this.tributeOptions = {
                fillAttr: 'name',
                lookup: 'name',
                values: function (text, cb) {
                    axios.get('/api/user?name=' + text).then(({data}) => {
                        cb(data);
                    }).catch(() => {
                        cb([]);
                    });
                },
                // selectClass: 'highlight',
                // containerClass: 'tribute-container',
                // itemClass: 'tribute-item',
                // function called on select that returns the content to insert
                // selectTemplate: function (item) {
                //     return '<span style="color: lightskyblue">@' + item.string + '</span>';
                // },
            };

        }
    }
</script>

<style scoped>

</style>
