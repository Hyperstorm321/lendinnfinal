<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        <table>
                            <tr v-for="people in peoples" v-bind:key="people.id">
                                <td>{{ people.first_name}}</td>
                                <td>{{ people.last_name}}</td>
                                <td>{{ people.user_id}}</td>
                                <td>{{ people.email}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                peoples:[],
            }
        },
        mounted() {
            this.fetchPeoples()
        },
        methods:{
            fetchPeoples(page_url){
                page_url = page_url || '/api/useraccounts';
                fetch(page_url)
                .then(res => res.json())
                .then(resJson => {
                    this.peoples = resJson
                })
                .catch(err => console.log(err))
            },
        }
    }
</script>
