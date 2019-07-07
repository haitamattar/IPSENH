<template>
    <div class="card customSearchUserOverviewCard">
        <div class="card-body ">
            <div class="row">
                <div class="col-md-1">
                    <div v-bind:style="{ backgroundImage: 'url(' + userCard.information.image.path + ')' }" class="profileImgSearch"></div>
                </div>
                <div class="col-md-4">
                    <router-link :to="{ name: 'profilePage', params: { user_id: userCard.id }}"><h5 class="card-title" v-text="userCard.name + ' ' + userCard.surname"> <span class="badge badge-primary ">74 Followers</span></h5></router-link>
                    <h6 class="card-subtitle mb-2 text-muted">{{userCard.information.bio}}</h6>
                </div>
                <div class="col-md-7 skillsSearchOverview">
                    <h6 class="card-subtitle mb-2 text-muted">Skills</h6>
                    <span v-for="(skill, index)  in userCard.skills" :key="index" class="badge badge-primary userSkillTag">
                        {{skill.skill.name}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserCard",
        props: ['userCard']
    };
</script>
