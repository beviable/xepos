<template>
    <div class="container">
        <div class="row">
            <div class="col-12 mb-2 text-end">
                <router-link :to='{name:"add-employee"}' class="btn btn-primary">Create</router-link>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Employees</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Company</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody v-if="employees && employees.length > 0">
                                    <tr v-for="(employee,index) in employees" :key="index">
                                        <td>{{ employee.id }}</td>
                                        <td>{{ employee.first_name }}</td>
                                        <td>{{ employee.last_name }}</td>
                                        <td>{{ employee.email }}</td>
                                        <td>{{ employee.phone }}</td>
                                        <td>{{ employee?.company?.name }}</td>
                                        <td>
                                            <router-link :to='{name:"edit-employee",params:{id:employee.id}}' class="btn btn-success">Edit</router-link>
                                            <button type="button" @click="deleteEmployee(employee.id)" class="btn btn-danger">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="7" align="center">No Company Found.</td>
                                    </tr>
                                </tbody>
                            </table>

                           </div>
                           <Bootstrap5Pagination align="center" :data="employeesPagination" @pagination-change-page="getEmployees" />
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
    name:"employees",
    components: {
        Bootstrap5Pagination,
    },
    data(){
        return {
            employees:[],
            employeesPagination: {}
        }
    },
    mounted(){
        this.getEmployees()
    },
    methods:{
        ...mapActions({
            signIn:'employee/list'
        }),
        async getEmployees(page=1){
            await axios.get('/sanctum/csrf-cookie')

            await axios.get(`/api/employee?page=${page}`).then(response=>{
                this.employeesPagination = response.data;
                this.employees = response.data.data;
            }).catch(error=>{
                console.log(error)
                this.employees = []
            })
        },
        async deleteEmployee(id){
            if(confirm("Are you sure to delete this employee ?")){
                await axios.get('/sanctum/csrf-cookie')
                await axios.delete(`/api/employee/${id}`).then(response=>{
                    this.getEmployees()
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
