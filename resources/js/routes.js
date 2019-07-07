import test from './components/Test.vue';
import login from './components/Auth/Login.vue';
import register from './components/Auth/Register.vue';
import landingPage from './components/LandingPage.vue';
import search from './components/Search.vue';
import profilePage from './components/ProfilePage.vue';
import addExperience from './components/AddExperience.vue';
import createProject from './components/Project/CreateProject.vue';
import project from './components/Project/Project.vue';
import settings from './components/settings';
import addSkill from './components/AddSkill.vue';
import notFound from './components/NotFound.vue';

export default [
    {
        path: '/test',
        name: 'test',
        component: test
    },
    {
        path: '/',
        name: 'welcome',
        component: landingPage
    },
    {
        path: '/search',
        name: 'search',
        component: search
    },
    {
        path: '/login',
        name: 'login',
        component: login
    },
    {
        path: '/settings',
        name: 'settings',
        component: settings
    },
    {
        path: '/profilePage/:user_id',
        name: 'profilePage',
        component: profilePage
    },
    {
        path: '/register',
        name: 'register',
        component: register,
        meta: {
            transitionName: 'slide'
        }
    },
    {
        path: '/project/:id',
        name: 'project',
        component: project
    },
    {
        path: '/createProject',
        name: 'create-project',
        component: createProject,
    },
    {
        path: '/addExperience',
        name: 'addExperience',
        component: addExperience,
        // TODO: add middleware option
        // meta: {
        //     requiresAuth: true
        // },
    },
    {
        path: '/addSkills',
        name: 'addSkill',
        component: addSkill
    },
    {
        path: '*',
        name: 'notFound',
        component: notFound
    }

];
