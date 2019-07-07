let mutations = {
    SET_USERS(state, users) {
        state.users = users;
    },
    SET_API_TOKEN({dispatch, state}, payload) {
        if('undefined' == typeof(payload.token)) {
            this.state.loggedIn = false;
        } else {
            this.state.loggedIn = true;
            this.state.token = payload.token;
            window.localStorage.setItem('token', payload.token);
            document.cookie = "token=" + window.localStorage.getItem('token');
        }
    },
    SET_CURRENT_USER({state}, payload) {
        this.state.currentUser = payload;
    },
    SIGN_OUT({state}) {
        this.state.isLoggedIn = false;
        this.state.token = undefined;

        window.localStorage.removeItem('token');
        document.cookie = "token=";
        resolve();
    }
};

export default mutations;
