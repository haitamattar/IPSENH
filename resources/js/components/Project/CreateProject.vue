<template>
    <div>
        <!--<navigation></navigation>-->
        <editUserSettingsHead :editTitle="'Create a new project'"></editUserSettingsHead>

        <div class="createProject-container" style="margin-top: 0">

            <div class="row">

                    <div class="col">
                        <label for="projectName" class="createProject-form-input">Project name:</label>
                        <input class="form-control rounded-corners"
                               style="width: 400px"
                               v-model="projectName"
                               placeholder="My awesome project"
                               id="projectName"
                               name="projectName">
                        <br>
                        <label class="createProject-form-input">Add a short description:</label>
                        <wysiwyg v-model="projectDescription" class="createProject-wysiwyg"
                                 placeholder="Add a summary about your project">
                        </wysiwyg>

                    </div>

                    <div class="col">
                        <label class="createProject-form-input">Header image preview</label>
                            <img class="img-thumbnail" style="height: 150px; width: 800px;"
                                 :src="headerImage"
                                 alt="Header image" v-if="headerImage.length > 0">
                        <div class="col" style="margin-top: 20px">
                            <input
                                    style="display: none"
                                    type="file"
                                    @change="selectHeaderImage($event)"
                                    accept="image/gif,image/jpeg,image/jpg,image/png"
                                    ref="inputFile">
                            <div class="row"></div>
                            <button @click="$refs.inputFile.click()" class="btn btn-primary">Select header</button>
                        </div>
                    </div>

                </div>

            <!-- Description buttons row -->
            <div class="row createProject-addDescription-buttons">
                <div class="col">
                    <button class="btn btn-primary rounded-corners" v-on:click="addTextDescription()">
                        Add text description
                    </button>
                    <button class="btn btn-primary rounded-corners" v-on:click="addImageDescription()">
                        Add image description
                    </button>
                    <button class="btn btn-primary rounded-corners" v-on:click="addExternalProjectPanel()" v-if="hasAccountsConnected()">
                        <span v-if="userLoaded">Link to a git project</span>
                        <span v-if="!userLoaded">Loading...</span>
                    </button>
                </div>
            </div>

            <div class="row" v-if="showProjectPicker" style="margin-bottom: 50px">

                <div class="col-12 fadeIn" v-if="githubLoaded">
                    <hr>
                    <label for="githubPicker">Select a Github project.</label>
                    <select name="githubPicker" id="githubPicker" v-model="selectedGithubProject" class="form-control createProject-selector">
                        <option selected :value="null">Select a project</option>
                        <option v-for="(project, i) in githubProjects" v-bind:key="i" :value="project"> {{ project.name }} </option>
                    </select>
                </div>

                <div class="col-12 fadeIn" v-if="gitlabLoaded">
                    <hr>
                    <label for="gitlabPicker">Select a Gitlab project.</label>
                    <select name="gitlabPicker" id="gitlabPicker" v-model="selectedGitlabProject" class="form-control createProject-selector">
                        <option selected :value="null">Select a project</option>
                        <option v-for="(project, i) in gitlabProjects" v-bind:key="i" :value="project"> {{ project.name }} </option>
                    </select>
                </div>

                <!-- Bitbucket api wasn't working well, had to disable -->
                <!--<div class="col-12 fadeIn" v-if="bitbucketLoaded">-->
                    <!--<hr>-->
                    <!--<label for="bitbucketPicker">Select a Bitbucket project.</label>-->
                    <!--<select name="bitbucketPicker" id="bitbucketPicker" v-model="selectedBitbucketProject" class="form-control createProject-selector">-->
                        <!--<option selected :value="null">Select a project</option>-->
                        <!--<option v-for="(project, i) in bitbucketLoaded" v-bind:key="i" :value="project"> {{ project.name }} </option>-->
                    <!--</select>-->
                <!--</div>-->

            </div>

            <div class="row">
                <div class="col">

                    <div v-for="(description, i) in descriptions" :key="i">
                        <hr>
                        <!-- Title -->
                        <label :for="'description-title-' + i" class="createProject-form-input">Description title</label>
                        <input class="form-control rounded-corners"
                               style="width: 400px"
                               v-model="description.title"
                               placeholder="Title"
                               :id="'description-title-' + i"
                               :name="'description-title-' + i">
                        <br>
                        <!-- Text description -->
                        <div v-if="'text' === description.type">

                            <label class="createProject-form-input">Description </label>
                            <wysiwyg v-model="description.description" class="createProject-wysiwyg"
                                     placeholder="Add a summary about your project">
                            </wysiwyg>
                        </div>

                        <!-- Image description-->
                        <div v-if="'image' === description.type">
                            <div class="row">
                                <div class="col">
                                    <label class="createProject-form-input">Text description </label>
                                    <wysiwyg v-model="description.description" class="createProject-wysiwyg" style=""
                                             placeholder="Add a summary about your project">
                                    </wysiwyg>
                                </div>
                                <div class="col">
                                    <label class="createProject-form-input">Header image preview</label>
                                    <img class="img-thumbnail" style="max-height: 300px;"
                                         :src="description.image"
                                         alt="Header image" v-if="description.image.length > 0">
                                    <div class="col" style="margin-top: 20px">
                                        <input
                                                style="display: none"
                                                type="file"
                                                @change="selectImageDescriptionImage($event, description)"
                                                accept="image/gif,image/jpeg,image/jpg,image/png"
                                                ref="inputImageFile">
                                        <div class="row"></div>
                                        <button @click="openFilePickerForDescription(description)"
                                                class="btn btn-primary">Select header</button>
                                        <span style="margin-left: 15px;">Side: </span>
                                        <select name="sideSelector" class="form-control createProject-selector"
                                                v-model="description.left">
                                            <option :value="true" >Left</option>
                                            <option :value="false" >Right</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Buttons for each description -->
                        <div class="createProject-description-buttons">
                            <button class="btn btn-primary rounded-corners" v-on:click="clearDescriptionText(description)">
                                Clear text
                            </button>

                            <button class="btn btn-primary rounded-corners" style="background-color: darkred" v-on:click="removeDescription(description)">
                                Remove description
                            </button>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row" style="margin-top: 60px">
                <div class="col">
                    <hr>
                    <button class="btn btn-primary rounded-corners" style="margin-top: 20px; float: right" v-on:click="save()">
                        Save
                    </button>
                </div>
            </div>
            <!--</div>-->

        </div>

        <app-footer></app-footer>

    </div>



</template>
<script>
    export default {
        data: function() {
            return {
                projectName: '',
                headerImage: '',
                headerImageName: null,
                projectDescription: '',
                descriptions: [],
                textDescriptions: [],
                githubProjects: [],
                gitlabProjects: [],
                bitbucketProjects: [],
                currentUser: [],
                showProjectPicker: false,
                githubLoaded: false,
                gitlabLoaded: false,
                bitbucketLoaded: false,
                userLoaded: false,
                providerName: '',
                selectedGithubProject: null,
                selectedGitlabProject: null,
                selectedBitbucketProject: null,
            };
        },
        mounted() {
            this.$store.dispatch("GET_CURRENT_USER").then((response) => {
                this.currentUser = response;
                this.userLoaded = true;
            });
        },
        methods: {
            openFilePickerForDescription(description) {
                const imageDescriptions = this.descriptions.filter((element) => {
                    return "text" !== element.type;
                });

                const index = imageDescriptions.indexOf(description);

                this.$refs.inputImageFile[index].click();
            },
            addTextDescription() {
                this.descriptions.push({
                    "type": "text",
                    "title": "",
                    "description": ""
                });
            },
            addImageDescription() {
                this.descriptions.push({
                    "type": "image",
                    "title": "",
                    "description": "",
                    "left": true,
                    "image": ""
                });
            },
            hasAccountsConnected() {
              return this.currentUser.github_id || this.currentUser.gitlab_id || this.currentUser.bitbucket_id;
            },
            addExternalProjectPanel() {
                if(!this.currentUser) {
                    return;
                }

                this.showProjectPicker = true;

                if(this.currentUser.github_id) {
                    this.$store.dispatch('GET_EXTERNAL_PROJECTS', {
                        userId: this.currentUser.id,
                        provider: 'github'
                    }).then((response) => {
                        this.githubProjects = response.repositories;
                        this.githubLoaded = true;
                    });
                }

                if(this.currentUser.gitlab_id) {
                    this.$store.dispatch('GET_EXTERNAL_PROJECTS', {
                        userId: this.currentUser.id,
                        provider: 'gitlab'
                    }).then((response) => {
                        this.gitlabProjects = response.repositories;
                        this.gitlabLoaded = true;
                    });
                }

                if(this.currentUser.bitbucket_id) {
                    this.$store.dispatch('GET_EXTERNAL_PROJECTS', {
                        userId: this.currentUser.id,
                        provider: 'bitbucket'
                    }).then((response) => {
                        this.bitbucketProjects = response.repositories;
                        this.bitbucketLoaded = true;
                    });
                }
            },
            clearDescriptionText(description) {
                description.description = '';
            },
            removeDescription(description) {
                const index = this.descriptions.indexOf(description);
                if (-1 < index) {
                    this.descriptions.splice(index, 1);
                }
            },
            selectHeaderImage(event) {
                this.headerImage = event.target.files[0];
                this.headerImageName = event.target.files[0].name;
                let chosenFile = event.target;

                if (chosenFile.files && chosenFile.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.headerImage = e.target.result;
                    };
                    reader.readAsDataURL(chosenFile.files[0]);
                }
            },
            selectImageDescriptionImage(event, description) {
                description.image = event.target.files[0];
                let chosenFile = event.target;

                if (chosenFile.files && chosenFile.files[0]) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        description.image = e.target.result;
                    };
                    reader.readAsDataURL(chosenFile.files[0]);
                }
            },
            save() {
                let data = {
                    "name": this.projectName,
                    "description": this.projectDescription,
                    "header_image": this.headerImage,
                    "descriptions": [],
                    "external": null
                };

                this.descriptions.forEach((description, index) => {
                    let type = '';
                    if('undefined' === typeof(description.left)) {
                        type = "text";
                    } else {
                        type =  description.left ? "image_left" : "image_right";
                    }

                    let descriptionData = {
                        "order": index,
                        "type": type,
                        "title": description.title,
                        "description": description.description,
                    };

                    if ("image" === description.type) {
                        descriptionData['image'] = description.image;
                    }

                    data['descriptions'].push(descriptionData);
                });

                let selectedExternalProjects = 0;
                if(this.selectedGithubProject) {
                    selectedExternalProjects++;
                }

                if(this.selectedGitlabProject) {
                    selectedExternalProjects++;
                }

                if(this.selectedBitbucketProject) {
                    selectedExternalProjects++;
                }
                if(0 < selectedExternalProjects) {
                    if(1 < selectedExternalProjects) {
                        // console.log("only one projecta llowed") todo
                        return;
                    }

                    let projectData;
                    let providerName;
                    let projectUrl;
                    if(this.selectedGithubProject) {
                        projectData = this.selectedGithubProject;
                        providerName = 'github';
                        projectUrl = projectData.html_url;
                    } else if(this.selectedGitlabProject) {
                        projectData = this.selectedGitlabProject;
                        providerName = 'gitlab';
                        projectUrl = projectData.http_url_to_repo;
                    } else if(this.selectedBitbucketProject) {
                        projectData = this.selectedBitbucketProject;
                        providerName = 'bitbucket';
                        projectUrl = projectData.links.html.href;
                    }

                    data['external'] = {
                        'provider_name': providerName,
                        'external_id': projectData.id,
                        'name': projectData.name,
                        'url': projectUrl,
                    };


                }

                this.$store.dispatch('CREATE_PROJECT', data).then(() => {
                    this.$router.push('/profilePage/' + this.currentUser.id);
                });

            }
        }
};
</script>