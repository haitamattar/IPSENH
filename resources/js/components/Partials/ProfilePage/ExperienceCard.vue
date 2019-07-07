<template id="experienceTemplate">
    <div>
        <div id="experience" class="row experience">
            <div>
                <h3>{{experience.name}}</h3>
                <h4>{{experience.description}}</h4>
                <p>{{experience.type}}</p>
                <h5>{{experience.start_date}} - {{experience.end_date}}</h5>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        props: {
            experience: Object
        },
        data: function() {
            return{
            };
        }
    };
</script>
