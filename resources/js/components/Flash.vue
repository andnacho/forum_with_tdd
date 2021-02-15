<template>
    <div :class="alertClass" role="alert" v-show="show">
        <strong v-text="title"></strong> {{ body }}
    </div>
</template>

<script>
    export default {
        props: ['message', 'type'],
        data() {
            return {
                body: '',
                show: false,
                alertClass: '',
                title: 'Success!'
            }
        },
        created() {
            if (this.message && this.type && this.title) {
                this.flash(this.message, this.type, this.title);
            } else if (this.message && this.type) {
                this.flash(this.message, this.type);
            } else if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', (message, type, title) => {
                this.flash(message, type, title);
            })
        },
        methods: {
            flash(message, type, title) {

                if (type) {
                    this.alertClass = 'alert alert-flash alert-'+ type;
                } else {
                    this.alertClass = 'alert alert-flash alert-success';
                }

                if (title) {
                    this.title = title;
                }

                this.body = message;
                this.show = true;

                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 0;
    }
</style>
