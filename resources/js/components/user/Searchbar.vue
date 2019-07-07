<template>
    <div class="searchUtils">
        <div class="form-row">
            <div class="form-group col-md-10">
                <label for="nameInput">Name</label>
                <input v-model="query" type="text" class="form-control roundedCorners customInput1" id="nameInput" placeholder="Hans Zwans">
            </div>
            <div class="form-group col-md-2">
                <label for="inputJob">&nbsp;</label>
                <button type="button" @click="searchUsers" @keyup.enter="searchUsers"
                    class="btn btn-primary roundedCorners cutomBtn1 fullWidth">
                    <i class="fas fa-search"></i>  search 
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Searchbar",
        data() {
            return {
                query: '',
            };
        },
        watch: {
            query: {
                handler: _.debounce(function () {
                    this.searchUsers();
                }, 10)
            }
        },
        methods: {
            searchUsers() {
                if('' == this.query) {
                    this.$store.dispatch('GET_USERS');
                } else{
                this.$store.dispatch('SEARCH_USERS', this.query);
                }
            }
        }
    };
</script>
