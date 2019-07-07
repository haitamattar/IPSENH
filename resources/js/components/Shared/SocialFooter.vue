<template> <!-- TODO dynamic loading -->
    <div class="container-fluid social-footer">
        <div class="row">
            <div class="col-md-5 col-sm-12 col-xs-12">
                <h3>Contact</h3>
                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.</p>
                <button class="btn btn-secondary rounded-corners" style="color: #4A00A3">Send message</button>
            </div>
            <div class="col-md-7 col-sm-12 col-xs-12 social-col">
                <h3>Social</h3>
                 <!-- TODO multiple columns, maximum of n?-->
                 <!-- TODO fix styling a bit -->
                    <div v-for="(value, key, index) in socialAccounts" :key="index">
                        <i v-bind:class="'fab fa-' + key" style="font-size: 2em"></i>
                        <span>{{ value }}</span>
                    </div>

            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data: function() {
            return {
                socialAccounts: {
                    "twitter": "@Test",
                    "gitlab": "TestUser",
                    "bitbucket": "TestUser1",
                    "facebook": "FacebookUser01",
                }
            };
        },
        methods: {
            numberOfColumnsNeeded: function() {
                return Math.ceil(this.socialAccounts.length / 3);
            }
        }
    };
</script>