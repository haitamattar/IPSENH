import app from '../app.js';

const handleError = function(error) {
    const message = error.response.error;

    app.$emit('show-modal', 'Something went wrong', message);

    return Promise.reject(error);
};

const showDialog = function(title, message) {
    app.$emit('show-modal', title, message);
};

let actions = {
    SEARCH_USERS({commit}, query) {
        let params = {
            query
        };
        axios.get(`/api/users/search`, {
                params
        }).catch((error) => {
            handleError(error);
        });
    },
    GET_USERS({commit}) {
        axios.get('/api/users')
            .then(res => {
                {
                    commit('SET_USERS', res.data);
                }
            }).catch((error) => {
            handleError(error);
        });
    },
    GET_USER_ID({commit, getters}) {
        return new Promise((resolve)=> {
            axios.get('/api/me/id',{
                headers: {
                    Authorization: getters.getToken
                }
            }).then((response)=>{
                resolve(response);
            });
        });
    },
    GET_API_TOKEN({commit}, payload) {
        return new Promise((resolve) => {
            axios.post('/api/login/token', {
                email: payload.email,
                password: payload.password
            }).then((response) => {
                commit('SET_API_TOKEN', {
                    token: response.data.token
                });
                if(response.data.message) {
                    showDialog('Incorrect login', response.data.message);
                }
                resolve(response);
            }).catch((error) => {
                handleError(error);
            });
        });
    },
    REGISTER_WITH_EMAIL({commit,dispatch}, payload) {
        return axios.post('/api/register', {
            email: payload.email,
            name: payload.name,
            surname: payload.surname,
            password: payload.password,
            password_confirmation: payload.password_confirmation,
            country_id: payload.country_id
        }).catch((error) => {
            handleError(error);
        });
    },
    CREATE_EXPERIENCE({commit, getters}, payload) {
        return axios.post('/api/experience/add', payload, {
            headers: {
                Authorization: getters.getToken
            }
        }).then(response => {

        });
    },
    ADD_SKILLS({commit, getters}, payload) {
        return axios.post('/api/user/addSkills', payload, {
            headers: {
                Authorization: getters.getToken
            }
        }).then(response => {
            
        });
    },
    GET_ALL_SKILLS() {
        return new Promise((resolve) => {
            return axios.get('/api/skills/all').then((response) => {
                resolve(response);
            }).catch((error) => {
                handleError(error);
            });
        });
    },
    UPLOAD_AVATAR_TO_DB({commit, getters}, payload) {
        return axios.post('/api/upload/avatar', payload, {
            headers: {
                Authorization: getters.getToken
            }
        }
        ).then((response) =>
             response = response.data['message']
        ).catch((error) => {
            handleError(error);
        });
    },
    GET_CURRENT_USER({commit, getters}) {
        return new Promise((resolve) => {
            return axios.get('/api/me', {
                headers: {
                    Authorization: getters.getToken
                }
            }).then((response) => {
                const user = response.data;
                resolve(user);
            }).catch((error) => {
                handleError(error);
            });
        });
    },
    GET_IS_FOLLOWING({getters},id) {
        return new Promise((resolve) => {
            axios.get('/api/user/isFollowing/' + id, {
                headers: {
                    Authorization: getters.getToken
                }
            }).then((response) => {
                resolve(response);
            });
        });
    },
    GET_IS_OWNER({getters},id) {
        return new Promise((resolve)=> {
            axios.get('/api/user/isOwner/'+id,{
                headers: {
                    Authorization: getters.getToken
                }
            }).then((response) => {
                resolve(response);
            });
        });
    },
    UPDATE_USER_INFO_IN_DB({commit, getters}, payload) {
        return axios.post('/api/update/user', payload, {
                headers: {
                    Authorization: getters.getToken
                }
            }

        );
    },
    GET_EXTERNAL_PROJECTS({getters}, payload) {
        return new Promise((resolve) => {
            return axios.get('/api/user/' + payload.userId + '/' + payload.provider + '/repositories').then((response) => {
                resolve(response.data);
            }).catch((error) => {
                handleError(error);
            });
        });
    },
    CREATE_PROJECT({getters}, payload) {
        return new Promise((resolve) => {
           return axios.post('/api/project/create', payload, {
               headers: {
                   Authorization: getters.getToken
               }
           }).then((response) => {
               resolve(response);
           }).catch((error) => {
               handleError(error);
           });
        });
    },
    GET_REMOTE_REPOSITORY({getters}, payload) {
        return new Promise((resolve) => {
           return axios.get('/api/user/' + payload.id + '/' + payload.provider + '/repository/' + payload.externalId).then((response) => {
               resolve(response);
           }).catch((error) => {
               handleError(error);
           });
        });
    },
    GET_PROJECT_LANGUAGES({getters}, payload) {
        return new Promise((resolve) => {
            return axios.get('/api/user/' + payload.id + '/' + payload.provider + '/repository/' + payload.externalId + '/languages').then((response) => {
                resolve(response);
            }).catch((error) => {
                handleError(error);
            });
        });
    },
    UPDATE_USER_SETTINGS({commit, getters}, payload) {
        return axios.post('/api/user/settings', payload, {
                headers: {
                    Authorization: getters.getToken
                }
            }

        );
    },
    GET_USER_INFO({commit, getters}) {
        return axios.get('api/user/getCurrentUser', {
            headers: {
                Authorization: getters.getToken
            }
        } );
    },
    GET_USER_SETTINGS({commit, getters}) {
        return axios.get('/api/user/settings', {
            headers: {
                Authorization: getters.getToken
            }
        } );
    },
    GET_AVATAR_URL({commit, getters}) {
        return axios.get('/api/download/avatar', {
            headers: {
                Authorization: getters.getToken
            }
        } );
    },
    REMOVE_GIT_CONNECTION({commit, getters}, payload) {
        return axios.delete('/api/user/removeConnection/'+ payload, {
            headers: {
                Authorization: getters.getToken
            }
        } );
    },
    GET_USER_SKILLS({commit, getters}) {
        return axios.get('/api/user/skills', {
            headers: {
                Authorization: getters.getToken
            }
        } );
    },
    FOLLOW_USER({commit,getters},payload) {
        return axios.post('/api/user/follow/'+payload,'',{
            headers: {
                Authorization : getters.getToken
            }
        });
    },
    SIGN_OUT({commit}) {
        return new Promise((resolve) => {
            commit('SIGN_OUT');
            resolve();
        });
    }
};
export default actions;
