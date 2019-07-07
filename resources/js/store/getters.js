let getters = {
    users: state => {
        return state.users;
    },
    getUser: state => {
        return state.user;
    },
    isLoggedIn: state => {
        return true === state.isLoggedIn;
    },
    getToken: state => {
        return state.token;
    },
    getLatestMessage: state => {
        return state.message ? state.message.split(": ") : '';
    },
    getCurrentUser: state => {
        return state.currentUser;
    }
};

export default getters;
