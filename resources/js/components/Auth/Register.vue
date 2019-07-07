<template>
    <transition name="slide-left" mode="out-in">
        <div class="no-x-overflow">
            <div class="col" id="login-register-col">

                <h2 id="login-register-title">Register</h2>

                <!-- <button id="login-register-btn" class="btn btn-secondary rounded-corners" v-on:click="toggleRegisterScreen">Registrer</button> -->

                <label for="Name">Name <span class="error-text">{{nameError}}</span></label>
                <input required class="form-control rounded-corners" v-model="registerName" placeholder="Name">
                <label for="Surname">Surname <span class="error-text">{{surnameError}}</span></label>
                <input required class="form-control rounded-corners" v-model="registerSurname" placeholder="Surname">
                <label for="Email">Email <span class="error-text">{{emailError}}</span></label>
                <input required class="form-control rounded-corners" v-model="registerEmail" placeholder="Example@host.com">
                <label for="Password">Password <span class="error-text">{{passwordError}}</span></label>
                <input required class="form-control rounded-corners" v-model="registerPassword" type="password" placeholder="Password">
                <label for="Password confirmation">Password confirmation</label>
                <input required class="form-control rounded-corners" v-model="registerPassword_confirmation" type="password" placeholder="Password">
                <label for="Country">Country</label>
                <select class="form-control rounded-corners" v-model="registerCountryId">
                    <option v-bind:key="country.id" v-bind:value="country.id" v-for="country in countries">
                    {{country.name}}
                    </option>
                </select>
                <br/>
                <button class="btn btn-secondary rounded-corners" v-on:click="register()">Sign up</button>


            </div>
        </div>
    </transition>
</template>
<script>
    export default {
        data: function() {
            return {
                registerEmail: "",
                registerPassword: "",
                registerPassword_confirmation:"",
                registerName: "",
                registerSurname: "",
                nameError:"",
                surnameError:"",
                emailError:"",
                passwordError:"",
                countries: [],
                registerCountryId: 1
            };
        },
        methods: {
            register() {
                this.$store.dispatch('REGISTER_WITH_EMAIL', {
                    email: this.registerEmail,
                    password: this.registerPassword,
                    password_confirmation:this.registerPassword_confirmation,
                    name: this.registerName,
                    surname: this.registerSurname,
                    country_id: this.registerCountryId
                }).then((response) => {
                    this.processResponse(response);
            });
            },processResponse(response) {
                if(201==response.status) {
                    this.$store.dispatch('GET_API_TOKEN',{
                        email:this.registerEmail,
                        password:this.registerPassword
                    }).then(() => {
                        this.$router.push('settings');
                    });
                }else{
                    this.setErrorMessages(response);
                }
            },
            setErrorMessages(response) {
                this.resetErrorMessages();
                let messages = response.data.error.name;
                if(messages) {
                    for(let i=0;i<messages.length;i++) {
                        this.nameError += this.addSpace(messages[i]);
                    }
                }
                messages = response.data.error.surname;
                if(messages) {
                    for(let i=0;i<messages.length;i++) {
                        this.surnameError += this.addSpace(messages[i]);
                    }
                }
                messages = response.data.error.email;
                if(messages) {
                    for(let i=0;i<messages.length;i++) {
                        this.emailError += this.addSpace(messages[i]);
                    }
                }
                messages = response.data.error.password;
                if(messages) {
                    for(let i=0;i<messages.length;i++) {
                        this.passwordError += this.addSpace(messages[i]);
                    }
                }
            },resetErrorMessages() {
                this.nameError = "";
                this.surnameError = "";
                this.emailError = "";
                this.passwordError = "";
            },addSpace(message) {
                return message+" ";
            }
        },
        created() {
            this.getAllCountries().then((response)=>{
                this.countries = response.data;
            });
        }
    };
</script>
