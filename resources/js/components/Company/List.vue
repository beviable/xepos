<template>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-2 text-end">
                <router-link :to='{name:"add-company"}' class="btn btn-primary">Create</router-link>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Companies</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Logo</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="companies && companies.length > 0">
                                    <tr v-for="(company,index) in companies" :key="index">
                                        <td>{{ company.id }}</td>
                                        <td>{{ company.name }}</td>
                                        <td>{{ company.email }}</td>
                                        <td>{{ company.website }}</td>
                                        <td>
                                            <div v-if="company.logo">
                                                <img :height="100" :width="100" :src="company.logo" alt="">
                                            </div></td>
                                        <td>
                                            <router-link :to='{name:"edit-company",params:{id:company.id}}' class="btn btn-success">Edit</router-link>
                                            <button type="button" @click="deleteCompany(company.id)" class="btn btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6" align="center">No Company Found.</td>
                                    </tr>
                                </tbody>
                            </table>

                           </div>
                           <Bootstrap5Pagination align="center" :data="companyiesPagination" @pagination-change-page="getCompanies" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';
    import { mapActions } from 'vuex'
</script>

<script>

export default {
    name:"companies",
    components: {
        Bootstrap5Pagination,
    },
    data(){
        return {
            companies:[],
            companyiesPagination: {}
        }
    },
    mounted(){
        this.getCompanies()
    },
    methods:{
        ...mapActions({
            signIn:'company/list'
        }),
        async getCompanies(page=1){
            await axios.get('/sanctum/csrf-cookie')

            await axios.get(`/api/company?page=${page}`).then(response=>{
                this.companyiesPagination = response.data;
                this.companies = response.data.data;
            }).catch(error=>{
                console.log(error)
                this.companies = []
            })
        },
        async deleteCompany(id){
            if(confirm("Are you sure to delete this category ?")){
                await axios.get('/sanctum/csrf-cookie')
                await axios.delete(`/api/company/${id}`).then(response=>{
                    this.getCompanies()
                }).catch(error=>{
                    console.log(error)
                })
            }
        }
    }
}
</script>
<style scoped>
    .pagination{
        margin-bottom: 0;
    }
</style>
