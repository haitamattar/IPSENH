<template>
    <div>
        <div id="login-left-white"></div>
        <p>alsdkmfsa;lkd</p>
        <div class="container-fluid">
            <div class="row">
                <!-- Yes, i am using 4000px, yes i will fix this -->
                <div class="col" id="login-col">
                    <h2 id="login-sign-in-title">Sign in</h2>
                    <p id="login-sign-in-subtitle">
                        Sign in with your credentials or GitHub, GitLab or BitBucket account
                    </p>

                    <form id="loginForm">
                        <label for="email" class="login-form-label">
                            Email<span style="color: red;">*</span>
                            <span class="error-text" v-if="emailMissing">
                                This field is required <!-- TODO: trans -->
                            </span>
                        </label>
                        <input class="form-control rounded-corners"
                               v-model="email"
                               placeholder="Example@host.com"
                               id="email"
                               name="email">

                        <label for="password" class="login-form-label">
                            Password<span style="color: red;">*</span>
                            <span class="error-text" v-if="passwordMissing">
                                This field is required <!-- TODO: trans -->
                            </span>
                        </label>
                        <input class="form-control rounded-corners"
                               v-model="password"
                               type="password"
                               id="password"
                               placeholder="password"
                               name="password"
                               value="">


                     </form>

                    <button class="btn btn-primary rounded-corners" id="login-sign-in-btn" v-on:click="login()">
                        Sign in
                    </button>


                    <router-link to="test"> <!-- TODO update -->
                        <p id="login-forgot-password-link">Forgot password?</p>
                    </router-link>

                    <h4 id="external-login-title">Or log in with</h4>
                    <div class="container" id="external-login">
                        <div class="row">
                            <div class="col-md-4">
                                <a v-bind:href="createURL('/auth/register/github')">
                                    <img src="/images/login-with/github-mark.png" alt="Github logo">
                                    <img class="logo-32px" src="/images/login-with/github.png" alt="Github text">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a v-bind:href="createURL('/auth/register/gitlab')">
                                    <img class="logo-32px" src="/images/login-with/gitlab.png" alt="Gitlab logo">
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a v-bind:href="createURL('/auth/register/bitbucket')">
                                    <img class="logo-32px" src="/images/login-with/bitbucket.png" alt="Gitlab logo">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md col-sm-12" id="login-register-col">

                    <h2 id="login-register-title">Register</h2>
                    <p>Sign up now</p>

                    <router-link to="register">
                        <button id="login-register-btn" class="btn btn-secondary rounded-corners" >Register</button>
                    </router-link>


                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        /*
         * This is a Vue lifecycle hook that will be called first in the component,
         * If the user is redirected to this page from github/gitlab/bitbucket and they're already logged in.
         */
        created() {
            if(this.loggedIn) {
                this.$router.push('settings');
            }
        },
        data: function() {
            return {
                email: "",
                password: "",
                emailMissing: false,
                passwordMissing: false,
            };
        },
        computed: {
          loggedIn() {
              return this.$store.getters.isLoggedIn;
          },
        },
        methods: {
            checkLoginForm() {
                if(this.email && this.password) {
                    return true;
                } else {
                    this.emailMissing = ("" === this.email);
                    this.passwordMissing = ("" === this.password);
                }
            },
            login() {
                if(this.checkLoginForm()) {
                    this.$store.dispatch('GET_API_TOKEN', {
                        email: this.email,
                        password: this.password
                    }).then(() => {
                        // TODO: navigate to correct page
                        if(this.loggedIn) {
                            this.$router.push('settings');
                        }
                    });
                }
            }

        }
    };
</script>
