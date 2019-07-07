<template>
<div>
    <editUserSettingsHead :editTitle="'Edit user settings'"></editUserSettingsHead>

    <div class="editUserContainer">

        <div class="container">
            <div class="row">
                <!-- Upload avatar -->
                <div class="col-md-4 d-flex align-items-center justify-content-center h-100">
                    <div class="information">
                        <h3 class="text-center" >Avatar</h3>
                        <div v-bind:style="{ backgroundImage: 'url(' + previewImage + ')' }" class="profileImgSettings align-middle"></div>

                        <input style="display: none" type="file" @change="selectAvatar($event)" accept="image/gif,image/jpeg,image/jpg,image/png" ref="inputFile">

                        <button @click="$refs.inputFile.click()" class="btn btn-primary">Select</button>
                        <button @click="uploadAvatar()" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Upload
                        </button>
                    </div>

                </div>


                <div class="col-md-8">
                    <h3>User information</h3>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputName">Name</label>
                            <input type="text" class="form-control noBorder" v-model="editName" id="inputName" placeholder="Jan">
                            <label class="editUserErrorLabel" for="inputName"><b> {{nameError}}</b></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputSurname">Surname</label>
                            <input type="text" class="form-control noBorder" v-model="editSurname" id="inputSurname" placeholder="Piet">
                            <label class="editUserErrorLabel" for="inputSurname"><b>{{surnameError}}</b></label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Password</label>
                            <input type="password" class="form-control noBorder" v-model="editPassword" id="inputPassword4" placeholder="Password">
                            <label class="editUserErrorLabel" for="inputPassword4"><b>{{passwordError}}</b></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputRepeatPassword">Repeat password</label>
                            <input type="password" class="form-control noBorder" v-model="editPassword_confirmation" id="inputRepeatPassword" placeholder="Repeat password">
                            <label class="editUserErrorLabel" for="inputPassword4"><b>{{password_confirmationError}}</b></label>
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputEmail4">Email</label>
                            <input type="email" class="form-control noBorder" v-model="editEmail" id="inputEmail4" placeholder="Email">
                            <label class="editUserErrorLabel" for="inputEmail4"><b>{{emailError}}</b></label>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="Country">Country</label>
                            <select class="form-control noBorder" v-model="editCountryId">
                                <option v-bind:key="country.id" v-bind:value="country.id" v-for="country in countries">
                                    {{country.name}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck" v-model="booleanEmailNotifications">
                                <label class="form-check-label" for="gridCheck">
                                    Enable e-mail notifications.
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck2" v-model="booleanInviteNotifications">
                                <label class="form-check-label" for="gridCheck2">
                                    Accept job or internship invites.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn btn-primary justify-content-end" @click="updateUserInfo()">Update user</button>
                    </div>


                </div>
            </div>




        <!-- Git connecties -->
        <div class="row mt-5">
            <!--<h5 class="card-title mt-5">Git connections</h5>-->
            <h3 class="text-center mt-5">Git connections</h3>
        </div>
        <div class="row shadow-sm p-3">
            <h4 class="float-left font-weight-bold"><i class="fab fa-github"></i> GitHub</h4>
            <button class="ml-auto btn btn-primary" @click="sendToGit('github')" v-if="!githubConnected">Connect</button>
            <button class="ml-auto btn btn-primary" @click="removeGitConnection('github')" v-if="githubConnected">Disconnect</button>
        </div>
        <div class="row shadow-sm p-3">
            <h4 class="float-left font-weight-bold"><i class="fab fa-gitlab"></i> GitLab</h4>
            <button class="ml-auto btn btn-primary" @click="sendToGit('gitlab')" v-if="!gitlabConnected">Connect</button>
            <button class="ml-auto btn btn-primary" @click="removeGitConnection('gitlab')" v-if="gitlabConnected">Disconnect</button>
        </div>
        <div class="row shadow-sm p-3">
            <h4 class="float-left font-weight-bold"><i class="fab fa-bitbucket"></i> BitBucket</h4>
            <button class="ml-auto btn btn-primary" @click="sendToGit('bitbucket')" v-if="!bitbucketConnected">Connect</button>
            <button class="ml-auto btn btn-primary" @click="removeGitConnection('bitbucket')" v-if="bitbucketConnected">Disconnect</button>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{message}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="getUserInfo()">Ok</button>
                </div>
            </div>
        </div>
    </div>
</div>

<app-footer></app-footer>

</div>
</template>
<script>
    export default {
        data: function() {
            return {
                file: null,
                fileName: null,
                fd: new FormData(),
                previewImage: "",
                message: "",
                projectName: "",
                titleBackgroundUrl: "https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg",
                avatarUrl: null,
                countries: [],
                userId: null,
                editName: null,
                editSurname: null,
                editPassword: null,
                editPassword_confirmation: null,
                editEmail: null,
                editCountryId: 1,
                userName: null,
                nameError: null,
                surnameError: null,
                emailError: null,
                passwordError: null,
                password_confirmationError: null,
                booleanEmailNotifications: null,
                booleanInviteNotifications: null,
                gitlabConnected: false,
                githubConnected: false,
                bitbucketConnected: false,
            };
        },
        methods: {
            sendToGit(provider) {
                const url = this.createURL('/auth/addToExisting/' + this.userId + '/' + provider);
                window.location.replace(url);
            },
            removeGitConnection(provider) {
                this.$store.dispatch('REMOVE_GIT_CONNECTION', provider, "GET_API_TOKEN").then((response) =>
                        this.message = response.data.message,
                );

                $('#exampleModal').modal('show');
            },
            selectAvatar(event) {
                this.file = event.target.files[0];
                this.fileName = event.target.files[0].name;
                let chosenFile = event.target;

                if (chosenFile.files && chosenFile.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.previewImage = e.target.result;
                    };
                    reader.readAsDataURL(chosenFile.files[0]);
                }
            },
            uploadAvatar() {
                this.message = null;
                if (this.fileName) {
                    this.fd.append('file', this.file, this.fileName);
                    this.$store.dispatch('UPLOAD_AVATAR_TO_DB', this.fd, "GET_API_TOKEN").then((response) =>
                            this.message = response
                        // todo add api token and set loggedin
                    );
                } else {
                    this.message = "Je hebt geen bestand geselecteerd.";
                }
                $('#exampleModal').modal('show');
            },
            updateUserInfo() {
                this.$store.dispatch('UPDATE_USER_INFO_IN_DB', {
                    email: this.editEmail,
                    password: this.editPassword,
                    password_confirmation: this.editPassword_confirmation,
                    name: this.editName,
                    surname: this.editSurname,
                    country_id: this.editCountryId
                }, "GET_API_TOKEN").then((response) =>
                    this.setErrorMessage(response)
                );
                this.updateUserSettings();
            },

            setErrorMessage(response) {
                this.resetErrorMessages();
                this.message = response.data.message;
                if(null != this.message) {
                    $('#exampleModal').modal('show');
                }
                try {
                    if (null!=response.data.error.name) {
                        this.nameError = response.data.error.name[0];
                    }
                    if (null!=response.data.error.surnamel) {
                        this.surnameError = response.data.error.surname[0];
                    }
                    if (null!=response.data.error.email) {
                        this.emailError = response.data.error.email[0];
                    }
                    if (null!=response.data.error.password) {
                        this.passwordError = response.data.error.password[0];
                        this.password_confirmationError = response.data.error.password[1];
                    }
                    this.message = null;
                } catch (e) {
                    // Error retrieving error messages.
                }
            },
            resetErrorMessages() {
                this.nameError = null;
                this.surnameError = null;
                this.emailError = null;
                this.passwordError = null;
                this.password_confirmationError = null;
            },
            updateUserSettings() {
                this.$store.dispatch('UPDATE_USER_SETTINGS', {
                    accept_invites: this.booleanInviteNotifications,
                    email_notifications: this.booleanEmailNotifications
                }, "GET_API_TOKEN");
            },
            getUserInfo() {
                this.$store.dispatch('GET_USER_INFO',"GET_API_TOKEN").then((response) => {
                    this.userId = response.data.id;
                    this.editName = response.data.name;
                    this.userName = response.data.name;
                    this.editSurname = response.data.surname;
                    this.editEmail = response.data.email;
                    this.editCountryId = response.data.country_id;
                    this.editPassword = response.data.password;
                    this.editPassword_confirmation = response.data.password_confirmation;
                    this.gitlabConnected = response.data.gitlab_id;
                    this.githubConnected = response.data.github_id;
                    this.bitbucketConnected = response.data.bitbucket_id;
                    this.avatarUrl = response.data.information.image.path;
                    this.previewImage = response.data.information.image.path;
                });
            }
        },
        mounted() {
            this.getUserInfo();
            this.getAllCountries().then((response) => {
                this.countries = response.data;
            });

            this.$store.dispatch('GET_USER_SETTINGS',"GET_API_TOKEN").then((response) => {
                this.booleanInviteNotifications = response.data.accept_invites;
                this.booleanEmailNotifications = response.data.email_notifications;
            });
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
