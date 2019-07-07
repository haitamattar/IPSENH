<template>
    <div>
        <!-- <div class="follower_img"></div> -->
        <div v-bind:style="{ backgroundImage: 'url(' + follower.information.image.path + ')' }" class="profileImgFollower align-middle"></div>
        <p id="follower_name">{{follower.name}} {{follower.surname}}</p>
    </div>
</template>
<script>
    export default {
        props: {
            follower:Object
        },
        data:function() {
            return {
                url:"http://lorempixel.com/200/200/cats/"+Math.ceil(Math.random()*10)
            };
        }
    };
</script>
