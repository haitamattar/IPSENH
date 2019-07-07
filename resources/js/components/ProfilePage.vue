<template>

<div>
    <navigation></navigation>
    <div id="profile_page">
        <div class="profileIntroHeader">
            <div class="row">

                <div class="col-md-6">
                    <div v-bind:style="{ backgroundImage: 'url(' + user.information.image.path + ')' }" class="profileImg"></div>
                </div>

                <div class="col-md-6">
                    <h1>{{user.name}} {{user.surname}}</h1>
                    <p class="bioUserProfile">{{user.information.bio}}</p>
                    <p class="countryUserProfile"><i class="fas fa-globe-europe"></i> {{country}}</p>

                </div>

            </div>

            <div id="profile_nav">

                <nav>
                    <ul>
                        <li><a href="#experiences">Experience</a></li>
                        <li> <a href="#projects">Projects</a></li>
                        <li> <a href="#blogs">Blogs</a></li>
                        <li> <a href="#skills">Skills</a></li>
                        <li> <a href="#" onClick="alert('href to contact page')">Contact</a></li>
                    </ul>
                </nav>
                <div class="clear"></div>

            </div>

        </div>

        <div class="container">

            <div class="row topUserProfile">
                <div id="experiences" class="col-md-8">
                    <h4>Experience</h4>
                    <div class="col-md-12">
                        <div v-for="experience in user.experience" :key="experience.id">
                            <experience_card v-bind:experience=experience></experience_card>
                        </div>
                    </div>

                    <router-link v-if="owner" class="btn btn-primary rounded-corners addExpBtn" :to="{name:'addExperience'}"><i class="fas fa-plus"></i> Add experience</router-link>
                </div>

                <div id="followers" class="col-md-4">

                    <h4>Followers {{user.following.length}}</h4>
                    <button v-if="!owner&&!follower" id="profile_follow_btn" class="btn btn-primary rounded-corners" v-on:click="followThisUser()"><i class="fas fa-frog"></i> Follow</button>
                    <div class="clear"></div>

                    <div class="row rowFollowers">
                        <a :href="'/profilePage/'+follower.user_id" class="col-md-3" v-for="follower in user.following" :key="follower.user_id">
                            <follower_card v-bind:follower=follower.following_user></follower_card>
                        </a>
                    </div>
                </div>
            </div>

            <!--Projects-->

            <div id="projects">
                <h4>Projects</h4>
                <router-link v-if="owner" class="btn btn-primary rounded-corners addExpBtn" :to="{name:'create-project'}"><i class="fas fa-plus"></i> Add project</router-link>
                <div class="row">
                    <router-link :to="{name:'project',params: {id:project.id}}" class="col-md-4" v-for="project in projects" :key="project.id">
                        <project_card v-bind:project="project"></project_card>
                    </router-link>
                </div>

            </div>

            <div id="blogs">
                <h4>Blogs</h4>

                <div class="row">
                    <div onClick="alert('href to blog page')" class="col-md-6" v-for="blog in user.blog" :key="blog.id">
                        <blog_card v-bind:blog="blog"></blog_card>
                    </div>
                </div>
            </div>

            <div id="skills">
                <h4>Skills</h4>
                <router-link v-if="owner" :to="{name:'addSkill'}" class="btn btn-primary rounded-corners"><i class="fas fa-plus"></i> Add skills</router-link>

                <input class="form-control rounded-corners searchSkillInput" v-model.trim="searchedSkill" v-on:input="searchSkill" type="text" placeholder="Search a skill" />
                <p>{{noSkillFound}}</p>
                <div class="row">
                    <div class="col-md-4" v-for="skill in showingSkills" :key="skill">
                        <p>{{skill}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <social-footer></social-footer>
</div>
</template>

<script>
export default{
        data:function() {
            return {
                owner:false,
                follower:true,
                user:{
                    name:undefined,
                    surname:undefined,
                    country_id:undefined,
                    information:{
                        bio:undefined,
                        image:{
                            path:"/images/profile.png"
                        }
                    },
                    experience:[],
                    following:[],
                    blog:[],
                    skills:[]
                },
                country:"No county",
                projects:[],
                showingSkills:[],
                searchedSkill:undefined,
                noSkillFound:""
            };
        },
        watch: {
            '$route.params.user_id': function (id) {
                let $user_id = this.$route.params.user_id;
                this.reloadPage($user_id);
            }
        },methods: {
            searchSkill() {
                let $showingSkills = [];
                for(let $index in this.user.skills) {
                    let $skill = this.user.skills[$index].skill.name;
                    if($skill.toLowerCase().includes(this.searchedSkill.toLowerCase())) {
                        $showingSkills.push($skill);
                    }
                }
                this.showingSkills = $showingSkills;
                if(0==$showingSkills.length) {
                    this.noSkillFound="Guess I need to learn more";
                }else {
                    this.noSkillFound="";
                }
            },
            reloadPage($id) {
                this.showingSkills = [];
                this.owner = false;
                this.follower = true;

                this.getUserProfile($id).then((result)=>{
                    this.user = result.data;
                    this.setSkills(this.user.skills);
                    this.getUserCountry(this.user.country_id).then((result)=>{
                        this.country = result.data;
                    });
                });

                this.getUserProjects($id).then((result)=>{
                    this.projects = result.data.projects;
                });

                if(this.$store.getters.isLoggedIn) {
                    let $user_id = this.$store.getters.getUser.id;

                    this.$store.dispatch('GET_IS_FOLLOWING',this.$route.params.user_id).then((result)=>{
                        this.follower = result.data.isFollowing;
                    });

                    this.$store.dispatch('GET_IS_OWNER',this.$route.params.user_id).then((result)=>{
                        this.owner = result.data.isOwner;
                    });
                }
            },
            setSkills($skills) {
                for(let $index in $skills) {
                    let $skill = $skills[$index].skill.name;
                    this.showingSkills.push($skill);
                }
            },
            followThisUser() {
                this.follower = true;
                this.$store.dispatch('FOLLOW_USER',this.$route.params.user_id).then((result)=>{
                    let $user_id = this.$route.params.user_id;
                    this.reloadPage($user_id);
                });

            }
        },
        mounted() {
            let $user_id = this.$route.params.user_id;
            this.reloadPage($user_id);
        }
    };
</script>
