<template>
<div>
    <editUserSettingsHead :editTitle="'Add experience'"></editUserSettingsHead>

    <div class="addExperience">
        <div class="container">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="inputName">Name</label>
                        <input v-bind:class="{'is-invalid': errors.name}" required v-model="post.name" type="text" class="form-control" id="inputName" placeholder="Name or company">
                        <div v-if="errors.name" class="invalid-feedback">
                            {{errors.name[0]}}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="inputJobTitle">Job Title</label>
                        <input v-bind:class="{'is-invalid': errors.jobTitle}" required v-model="post.jobTitle" type="text" class="form-control" id="inputJobTitle" placeholder="Job title or function">
                        <div v-if="errors.jobTitle" class="invalid-feedback">
                            {{errors.jobTitle[0]}}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputDescription">Description</label>
                    <textarea v-bind:class="{'is-invalid': errors.description}" required v-model="post.description" class="form-control" placeholder="I liked the job" id="inputDescription" rows="3"></textarea>
                    <div v-if="errors.description" class="invalid-feedback">
                        {{errors.description[0]}}
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="inputStartDate">Start date</label>
                        <input v-bind:class="{'is-invalid': errors.startDate}" required v-model="post.startDate" type="date" class="form-control" id="inputStartDate">
                        <div v-if="errors.startDate" class="invalid-feedback">
                            {{errors.startDate[0]}}
                        </div>
                    </div>
                    <div class="form-group col-md-4">

                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEndDate">End date</label>
                        <input v-bind:class="{'is-invalid': errors.endDate}" required v-model="post.endDate" type="date" class="form-control" id="inputEndDate">
                        <div v-if="errors.endDate" class="invalid-feedback">
                            {{errors.endDate[0]}}
                        </div>
                    </div>
                </div>
                <button type="button" @click="Send" class="btn btn-primary">Add Experience</button>
            </form>
        </div>
    </div>

</div>
</template>



<script>
import { mapGetters } from 'vuex';
export default {
    name: "AddExperience",
    data:function() {
        return {
        post: {
            name: '',
            jobTitle: '',
            description: '',
            startDate: '',
            endDate: '',
        },
        errors: [],
    };

    },
    methods: {
        Send() {
            this.$store.dispatch('CREATE_EXPERIENCE', {
                name: this.post.name,
                jobTitle: this.post.jobTitle,
                description: this.post.description,
                startDate: this.post.startDate,
                endDate: this.post.endDate
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
