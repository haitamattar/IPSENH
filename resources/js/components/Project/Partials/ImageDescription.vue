<template> <!-- TODO dynaic loading + fix the image on right side -->
    <div class="container-fluid bg-white image-desc">
        <div class="row">

            <div v-if="left" class="col-md-6 col-sm-12 image-desc-img-left">
                <div class="imageProjectShowcase" v-bind:style="{ backgroundImage: 'url(' + description.image.path + ')' }">
                </div>
            </div>



            <div class="col-md-6  col-sm-12" style="padding-left: 15px;">
                <h2>{{ description.title }}</h2>
                <span v-html="description.description"></span>

            </div>

            <!-- Image to show if left is false -->
            <div v-if="!left" class="col-md-6 col-sm-12 image-desc-img-right">
                <div class="imageProjectShowcase" v-bind:style="{ backgroundImage: 'url(' + description.image.path + ')' }">
                </div>
            </div>

        </div>
    </div>
</template>
<script>
    export default {
        props: ['left', 'description'],
    };
</script>
