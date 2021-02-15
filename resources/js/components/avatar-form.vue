<template>
    <div>
        <div class="level">
            <img :src="avatar" width="50" height="50" class="m-2">
            <h1 v-text="user.name"></h1>
        </div>

        <form v-if="canUpdate" method='POST' enctype="multipart/form-data">
            <image-upload name="avatar" class="mr-1" @loaded="onLoad"></image-upload>
        </form>

    </div>
</template>

<script>
    import ImageUpload from './ImageUpload';

    export default {
        name: "avatar-form",
        props: ['user'],
        data() {
            return {
                avatar: '/storage/' + this.user.avatar_path
            };
        },

        components: {
            ImageUpload
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onLoad(avatar) {
                this.avatar = avatar.src;
                this.persist(avatar.file);
            },
            persist(avatar) {
                let data = new FormData();
                data.append('avatar', avatar);
                axios.post(`/api/user/${this.user.name}/avatar`, data)
                    .then(() => flash('Avatar uploaded!'))
                    .catch(() => flash('Error', 'warning', 'Error'));
            }

        }
    }
</script>

<style scoped>

</style>
