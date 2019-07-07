<!-- TODO; trans -->
<template>
    <div class="no-x-overflow" style="padding-bottom: 0" v-if="projectLoaded">
        <navigation></navigation>

        <!-- Top section with title and bg image -->
        <div class="container-fluid project-title-container"
            v-bind:style="{'background-image': 'url(' + titleBackgroundUrl + ')'}">
            <div class="row project-title-blur">
                <div class="col-md-12 text-center project-title-content">
                    <h1 style="color: white">
                        {{ project.name }}
                    </h1>

                    <br />

                    <div v-bind:style="{ backgroundImage: 'url(' + projectOwner.information.image.path + ')' }" class="profileImgProject"></div>

                    <p style="color:white">
                        {{ projectOwner.name + ' ' + projectOwner.surname }}
                    </p>
                </div>
            </div>
        </div>
        <!-- End of title -->

        <!-- Project description and stans -->
        <div class="container-fluid bg-white project-page-content">
            <div class="row">
                <div class="col">
                    <h2>Project description</h2>
                    <p v-html="project.description"></p>
                </div>
                <div class="col" v-if="showStats">
                    <h2>
                        <i v-bind:class="'fab fa-' + project.external_url.provider_name"></i>
                        {{ provider }} Statistics
                    </h2>
                    <div class="row">
                        <div class="col-md-12">
                            <i class="fas fa-star"></i> <span class="primary-text-color">{{ stars }}</span> Star(s)
                        </div>
                        <div class="col-md-12" v-if="'Github' === provider">
                            <i class="fas fa-eye"></i> <span class="primary-text-color">{{ numberOfWatchers }}</span> Watcher(s)
                        </div>
                        <div class="col-md-12">
                            <i class="fas fa-users"></i> <span class="primary-text-color">{{ contributors }}</span> Contributor(s)
                        </div>
                        <div class="col-md-12">
                            <i class="fas fa-code-branch"></i> <span class="primary-text-color">{{ branches }}</span> Branche(s)
                        </div>
                        <div class="col-md-12">
                            <i class="fas fa-balance-scale"></i> License: <a href="#">{{ license }}</a>
                        </div>
                        <div class="col-md-12">
                            <i class="fas fa-globe-europe"></i> Link: <a :href="project.external_url.url"> Visit on {{ provider }} </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of description -->

        <!-- Custom descriptions -->
        <div v-for="(description, i) in project.descriptions"
             :key="i">

            <text-description v-if="'text' === description.type"
                              :description="description">
            </text-description>

            <image-description v-if="'image_left' === description.type"
                               :left="true"
                               :description="description">
            </image-description>

            <image-description v-if="'image_right' === description.type"
                               :left="false"
                               :description="description">
            </image-description>

        </div>
        <!-- End of custom descriptions -->

        <!-- Tech stack -->
        <div class="container-fluid bg-white project-tech-stack " v-if="showStats">
            <h2><i class="fas fa-code"></i> Technologies used</h2>

            <div class="project-tech-list">
                <div class="row">
                    <div class="col-md col-sm-12 col-xs-12" v-for="(n, i) in numberOfColumnsNeeded()" :key="i">
                        <p v-for="technology in technologies.slice(i * 6, i * 6 + 6)" :key="technology">
                            {{ technology }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of tech stack -->

        <!-- Footers -->
        <social-footer></social-footer>
        <app-footer></app-footer>
        <!-- Footers -->

    </div>
</template>
<script>
    export default {
        data: function() {
            return { // TODO dynamic
                projectLoaded: false,
                project: undefined,
                projectOwner: undefined,
                externalInfo: undefined,
                provider: undefined,
                showStats: false,
                titleBackgroundUrl: "https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg",
                stars: 'Loading',
                contributors: 'Loading',
                branches: 'Loading',
                license: 'Loading',
                numberOfWatchers: 'Loading',
                technologies: ['Loading...']
            };
        },
        created() {
            if (this.$route.params.project) {
                this.project = project;
                this.projectLoaded = true;
            }
            if (this.$route.params.id) {
                this.getProjectById(this.$route.params.id).then((response) => {
                    this.project = response.data.project;
                    this.projectOwner = response.data.user;
                    this.projectLoaded = true;
                    this.titleBackgroundUrl = this.project.header_image.path;
                    if (null === this.project.external_url) {
                        // Do nothing
                    } else {
                        this.showStats = true;
                        this.provider = this.project.external_url.provider_name.charAt(0).toUpperCase() + this.project.external_url.provider_name.slice(1);
                        this.$store.dispatch('GET_REMOTE_REPOSITORY', {
                            'id': this.projectOwner.id,
                            'provider': this.project.external_url.provider_name,
                            'externalId': this.project.external_url.external_id
                        }).then((response) => {
                            this.externalInfo = response.data.repository;

                            if('Github' === this.provider) {
                                this.stars = this.externalInfo.stargazers_count;

                                if(null !== this.externalInfo.license) {
                                    this.license = this.externalInfo.license;
                                } else {
                                    this.license = 'None';
                                }
                            } else if('Gitlab' === this.provider) {
                                this.stars = this.externalInfo.star_count;
                                this.license = 'None'; // Gitlab doesnt use licenses

                            }

                            this.numberOfWatchers = this.externalInfo.watchers_count;
                            this.contributors = this.externalInfo.contributors;
                            this.branches = this.externalInfo.branches;

                        });

                        this.$store.dispatch('GET_PROJECT_LANGUAGES', {
                            'id': this.projectOwner.id,
                            'provider': this.project.external_url.provider_name,
                            'externalId': this.project.external_url.external_id
                        }).then((response) => {
                            this.technologies = response.data.languages;
                        });

                    }
                });



            }
        },
        methods: {
            numberOfColumnsNeeded: function() {
                return Math.ceil(this.technologies.length / 6);
            },
        }
    };
</script>
