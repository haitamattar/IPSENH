<template>
    <div>
        <div id="project_img" :style="'background-image:url('+project.header_image.path+')'"></div>
        <p>{{project.name}}</p>
    </div>
</template>
<script>
    export default{
        props: {
            project:Object
        }
    };
</script>