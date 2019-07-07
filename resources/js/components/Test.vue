<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card card-default">
          <div class="card-header">Vue message</div>
          <div class="card-body" v-if="loggedIn">You are logged in</div>
          <div class="card-body" v-if="!loggedIn">You are not logged in</div>
          <p>{{token}}</p>
          <button v-on:click="testWelcome()">Click me</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    methods: {
        testWelcome: function() {
            this.$router.push({
              path: 'settings'
            });
        }
    },
    computed: {
        loggedIn() {
            return this.$store.getters.isLoggedIn;
        },
        token() {
            return this.$store.getters.getToken;
        }
    },
};
</script>
