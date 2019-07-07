<template>
<div>
    <editUserSettingsHead :editTitle="'Add Skills'"></editUserSettingsHead>

    <div class="addExperience">
        <div class="container">
            <!-- <div class="" v-for="(user, index)  in users" :key="index">
                <usercard class="fadeIn" :userCard="user"></usercard>
            </div> -->
            <div v-for="(skillCol, index) in skills" :key="index" class="form-row">

                <div v-for="(skill, index) in skillCol" :key="index" class="form-group col-md-3">
                    <div class="form-check">
                      <input class="form-check-input" v-model="addSkills" type="checkbox" :value="skill.id" id="defaultCheck1">
                      <label class="form-check-label" for="defaultCheck1">
                        {{skill.name}}
                      </label>
                    </div>
                </div>

            </div>
            <button type="button" @click="Send" class="btn btn-primary">Add Skills</button>
        </div>
    </div>
    <app-footer></app-footer>

</div>
</template>



<script>
import { mapGetters } from 'vuex';
export default {
    name: "AddSkill",
    data:function() {
        return {
        skills: [],
        addSkills: [],
        errors: [],
        mess: []
    };

    },
    mounted() {
        this.$store.dispatch('GET_ALL_SKILLS').then((response) => {
            this.skills = _.chunk(response.data, 4); // part in elements of 4
        });
    },
    methods: {
        Send() {
            this.$store.dispatch('ADD_SKILLS', {
                skills: this.addSkills
            }).then(data => {
                this.$store.dispatch('GET_USER_ID').then((result)=>{
                    let userID = result.data;
                    this.$router.push('profilePage/' + userID);
                });
            }).catch(error => {
                this.errors = error.response.data.error;
            });
        }
    }
};
</script>
