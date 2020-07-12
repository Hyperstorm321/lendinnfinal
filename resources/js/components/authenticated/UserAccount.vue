<template>
    <v-app>
       <v-container>
            <br>
            <v-row justify="center">    
                <v-col cols="12" sm="6">
            <v-form>
                    <h1 class="text-center">Change User Information</h1> <br>
                    <v-text-field v-model='changeUserinfo.first_name' label="First Name" :counter='20' required></v-text-field>
                    <v-text-field v-model='changeUserinfo.middle_name' label="Middle Name" :counter='20'></v-text-field>
                    <v-text-field v-model='changeUserinfo.last_name' label="Last Name" :counter='20' required></v-text-field>
                    <v-text-field v-model='changeUserinfo.extension_name' label="Extension Name" :counter="10"></v-text-field>
                    <v-text-field v-model='changeUserinfo.address' label="Address" :counter="50"></v-text-field>
                    <v-text-field v-model='changeUserinfo.barangay' label="Barangay" :counter="10"></v-text-field>
                    <v-text-field v-model='changeUserinfo.postal_code' label="Postal Code" :counter="10"></v-text-field>
                    <v-text-field v-model='changeUserinfo.city' label="City"></v-text-field>
                    <v-text-field v-model='changeUserinfo.region' label="Region"></v-text-field>
                    <v-text-field v-model='changeUserinfo.province' label="Province"></v-text-field>
                    <v-btn class="primary" v-on:click="changeUserinfo"> Save Changes </v-btn>
            </v-form>
            <br>
            <h1 class="text-center">Change Password</h1> <br>
            <v-form>
                <v-text-field v-model='changePassword.oldPassword' :rules="[rules.required, rules.min]" counter hint="At least 8 characters" label="Password" :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'" :type="show_password ? 'text' : 'password'" @click:append="show_password = !show_password" required></v-text-field>
                <v-text-field v-model='changePassword.newPassword' :rules="[rules.required, rules.min]" counter hint="At least 8 characters" label="Password" :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'" :type="show_password ? 'text' : 'password'" @click:append="show_password = !show_password" required></v-text-field>
                <v-text-field v-model='changePassword.confirmPassword' :rules="[rules.required, rules.min]" counter hint="At least 8 characters" label="Password" :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'" :type="show_password ? 'text' : 'password'" @click:append="show_password = !show_password" required></v-text-field>
                <v-btn class="primary"> Change Password </v-btn>
                
            </v-form>
                 </v-col>
            </v-row>
        </v-container>
    </v-app>
</template>

<script>
export default {
     data(){
        return{
            changeUserinfo:{
                userid:'',
                first_name:'',
                last_name:'',
                middle_name:'',
                extension_name:'',
                address:'',
                barangay:'',
                postal_code:'',
                city:'',
                region:'',
                province:'',
            },
            changePassword:{
                oldPassword:'',
                newPassword:'',
                confirmPassword:''
            },
            rules: {
                required: value => !!value || 'Required.',
                min: v => v.length >= 8 || 'Min 8 characters',
            },
            show_password:false
        }
    },

    mounted(){
        // this.fetchUserinfo();
    },

    methods:{
        changeInfo(){
            const user_id = this.$userId
            fetch(`api/useraccounts/${user_id}`, {
                    method: "put",
                    headers:{
                        'content-type' : 'application/json'
                    },
                    body:JSON.stringify(this.changeUserinfo)
                })
                .catch(err => console.log(err))
            }
        }
    }
</script>