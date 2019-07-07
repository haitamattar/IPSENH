/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// eslint-disable-next-line
require('./bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import routes from './routes.js';
import App from './components/App.vue';
import axios from 'axios';
import navigation from './components/Shared/Navigation.vue';
import imageDescription from './components/Project/Partials/ImageDescription.vue';
import textDescription from './components/Project/Partials/TextDescription.vue';
import footer from './components/Shared/Footer.vue';
import socialFooter from './components/Shared/SocialFooter.vue';
import userCard from './components/user/UserCard.vue';
import store from './store/index';
import wysiwyg from "vue-wysiwyg";
import modal from "./components/Shared/Modal";

import ExperienceCard from './components/Partials/ProfilePage/ExperienceCard.vue';
import FollowerCard from './components/Partials/ProfilePage/FollowerCard.vue';
import ProjectCard from './components/Partials/ProfilePage/ProjectCard.vue';
import BlogCard from './components/Partials/ProfilePage/BlogCard.vue';

Vue.use(VueRouter);
Vue.use(Vuex);

const router = new VueRouter({
    mode: 'history',
    routes: routes
});




Vue.component('app', App);
Vue.component('navigation', navigation);
Vue.component('image-description', imageDescription);
Vue.component('text-description', textDescription);
Vue.component('app-footer', footer);
Vue.component('social-footer', socialFooter);
Vue.component('searchbar', require('./components/user/Searchbar'));
Vue.component('usercard', userCard);
Vue.component('users', require('./components/user/Users'));
Vue.component('editUserSettingsHead', require('./components/Shared/EditUserSettingHeader.vue'));

Vue.component('experience_card',ExperienceCard);
Vue.component('follower_card',FollowerCard);
Vue.component('project_card',ProjectCard);
Vue.component("blog_card",BlogCard);
Vue.component("modal", modal);

// Config for the WYSIWYG Editor
Vue.use(wysiwyg, {
    hideModules: {
        "code": true,
        "justifyLeft": true,
        "justifyCenter": true,
        "justifyRight": true,
        "image": true,
        "table": true,
    },
    forcePlainTextOnPaste: true,

});

Vue.mixin({
    beforeCreate() {
        if(window.localStorage.getItem('token') && 1 < window.localStorage.getItem('token').length ) {
            this.$store.state.token = window.localStorage.getItem('token');
            this.$store.state.isLoggedIn = true;


            document.cookie = "token=" + window.localStorage.getItem('token');
        } else {
            let tokenCookie = document.cookie.split('; ').find((element) => {
                return element.startsWith('token=');
            });
            // console.log(tokenCookie);
            if('undefined' !== typeof(baseState)) {
                this.$store.state.isLoggedIn = baseState.loggedIn;
                this.$store.state.token = baseState.token;
                if(baseState.loggedIn) {
                    document.cookie = "token=" + window.localStorage.getItem('token');
                    window.localStorage.setItem('token', baseState.token);
                }

            } else {
                this.$store.state.isLoggedIn = false;
                this.$store.state.token = undefined;
            }


        }
    },
    methods: {
        getAllCountries: function() {
            return axios.get('/api/country/all');
        },
        createURL: function(path) {
            return store.state.baseUrl + path;
        },
        getProjectById: function(id) {
            return axios.get('/api/project/' + id);
        },
        getExperiences:function() {
            return axios.get('/api/user/experiences');
        },
        getAllSkills:function() {
            return axios.get('/api/skills/all');
        },
        getUserProfile:function($id) {
            return axios.get('/api/user/profile/'+$id);
        },
        getUserProjects:function($user_id) {
            return axios.get('/api/user/'+$user_id+'/projects');
        },
        getUserCountry:function($id) {
            return axios.get('/api/country/'+$id);
        },
        followUser:function($id) {
            axios.post('/api/user/follow'+$id,{
                headers: {
                    Authorization: this.$store.getters.getToken
                }
            });
        }
    },
    getters: {
        isLoggedIn: state => {
            return state.loggedIn;
        },
        getToken: state => {
            return state.token;
        }
    },
    /*
     * You can call these actions by using:
     * this.$store.dispatch('actionName');
     * You can also give it an associative array as payload
     */
    actions: {
        //TODO add actions
        getApiToken({commit}, payload) {
            return new Promise((resolve,) => {
                axios.post('/api/login/token', {
                    email: payload.email,
                    password: payload.password
                }).then((response) => {
                    commit('setApiToken', {
                        token: response.data.token
                    });
                    resolve(response);
                });
            });
        },
        registerWithEmail({commit,dispatch}, payload) {
            return axios.post('/api/register', {
                email: payload.email,
                name: payload.name,
                surname: payload.surname,
                password: payload.password,
                password_confirmation: payload.password_confirmation,
                country_id: payload.country_id
            });
        },
        getProjectById: function(id) {
            return axios.get('/api/project/' + id);
        }
    },
});


// TODO: Add redirect to pages when user is not loggedin
// router.beforeEach((to, from, next) => {
//   if (to.matched.some(record => record.meta.requiresAuth)) {
        // TODO: get loggindInState
//     if (false == store.getters.isLoggedIn) {
//       next({
//         path: '/login',
//         query: {
//           redirect: to.fullPath,
//         },
//       });
//     } else {
//       next();
//     }
//   } else {
//     next();
//   }
// });

const app = new Vue({
    el: '#app',
    router: router,
    store,
    components: {
      App
    },
});

export default app;
