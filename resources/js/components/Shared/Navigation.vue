<template>
    <div>
        <nav class="navbar navbar-app navbar-expand-lg">
            <a class="navbar-brand float-left text-light" href="/">$su user</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">

                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <router-link class="nav-link text-light" :to="{name:'search'}">Search user</router-link>
                    </li>
                </ul>

                <ul v-if="isLoggedIn()" class="navbar-nav">

                    <li class="nav-item">
                        <router-link class="nav-link text-light" :to="{name: 'profilePage',params: {user_id:this.user_id}}">My profile</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link text-light" :to="{name:'settings'}">Settings</router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-light" v-on:click="logOut()" href="/">Sign out</a>
                    </li>

                </ul>

            </div>
        </nav>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                user_id:undefined
            };
        },
        methods: {
            selectAvatar(event) {

            },
            uploadAvatar() {

            },
            isLoggedIn() {
                return this.$store.getters.isLoggedIn;
            },
            logOut() {
                this.$store.dispatch('SIGN_OUT').then(() => {
                    this.$router.push(this.$store.state.baseUrl);
                });
            }
        },

        mounted() {
            this.$store.dispatch('GET_USER_ID').then((result)=>{
                this.user_id = result.data;
            });
        },

    };

</script>
<style>

</style>
