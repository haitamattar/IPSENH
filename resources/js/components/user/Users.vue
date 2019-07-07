<template>
    <div class="usersOverview">
        <div class="" v-for="(user, index)  in users" :key="index">
            <usercard class="fadeIn" :userCard="user"></usercard>
        </div>
    </div>
</template>



<script>
    import {mapGetters} from 'vuex';
    import userCard from './UserCard';

    export default {
        name: "Users",

        mounted() {
            this.$store.dispatch('GET_USERS');

            Echo.channel('searchUser')
                .listen('searchUserEvent', (data) => {
                    this.$store.commit('SET_USERS', data.users);
                });

        },
        computed: {
            groupedUsers() {
                return this.userCard;
            },
            ...mapGetters([
                'users'
            ])
        }
    };
</script>
