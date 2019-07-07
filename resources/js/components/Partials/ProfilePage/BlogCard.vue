<template>
    <div>
        <figure>
            <img id="blog_img" :src="url+addons[(Math.round(Math.random()*3))]+'/'+(Math.round(Math.random()*5))" alt="Not supported"/>
            <figcaption>
                <h5>{{blog.name}}</h5>
                <p>{{blog.description}}</p>
            </figcaption>
        </figure>
    </div>
</template>
<script>
    export default{
        props: {
            blog:Object
        },
        data:function() {
            return {
                addons:['abstract','business','technics','fashion'],
                url:"http://lorempixel.com/600/200/"
            };
        }
    };
</script>
