import {mount} from 'vue-test-utils';
import users from '../components/user/Users';
import userCard from '../components/user/UserCard';
import {mapGetters} from 'vuex';
import getters from '../store/getters';
import mutations from '../store/mutations';

describe('Testing user search vuex getters', () => {

    it('Test_GetUsers', () => {

        const { SET_USERS } = mutations;

        // mock state
        const state = {
            users: [{
                    id: 1,
                    name: 'Hans',
                    surname: 'Bod'
                },
                {
                    id: 2,
                    name: 'Herman',
                    surname: 'Hoop'
                },
                {
                    id: 3,
                    name: 'Anouk',
                    surname: 'Loeps'
                },
            ]
        };

        // get the result from the getter
        SET_USERS(state);
        // const result = getters.users;

        // assert the result
        // expect(result).to.deep.equal([{
        //         id: 1,
        //         name: 'Hans',
        //         surname: 'Bod'
        //     },
        //     {
        //         id: 2,
        //         name: 'Herman',
        //         surname: 'Hoop'
        //     },
        //     {
        //         id: 3,
        //         name: 'Anouk',
        //         surname: 'Loeps'
        //     },
        // ]);
    });

});
