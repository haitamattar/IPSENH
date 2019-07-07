<template>
    <div>
        <navigation></navigation>
        <div class="settingsHeadTitle">
            <h2>{{editTitle}}</h2>
        </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';

    export default {
        name: "settingsHeader",
        props:["editTitle"],
    };
</script>
