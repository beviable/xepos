<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Update Employee - {{ formData.first_name }} {{ formData.last_name }}</div>

            <div class="card-body">

              <!-- THIS SECTION IS FOR ERRORS THAT WOULD COME FROM API -->
              <div v-if="errors">
                <div v-for="error in errors" class="alert alert-danger">{{ error }}</div>
              </div>

              <!-- FORM WITH v-if WILL BE SHOWN BUT THEN HIDDEN AFTER SUCCESS SUBMIT -->
              <form @submit.prevent="update">
                <div class="form-group row">
                  <label for="company" class="col-md-4 col-form-label text-md-right">Company</label>
                  <div class="col-md-6">
                    <select @change="changeCountry($event)" class="form-control">
                        <option value='0' selected disabled>::Select Company::</option>
                        <option v-for="company in companies"
                            :value="company.id"
                            :key="company.id"
                            :selected="company.id== formData.company_id ?true : false"
                        >
                        {{company.name}}
                        </option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="form-group row">
                  <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>
                  <div class="col-md-6">
                    <input v-model="formData.first_name" id="first_name" type="text" class="form-control" name="first_name" required autocomplete="first_name" autofocus>
                  </div>
                </div>
                <br>
                <div class="form-group row">
                  <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>
                  <div class="col-md-6">
                    <input v-model="formData.last_name" id="last_name" type="text" class="form-control" name="last_name" required autocomplete="last_name">
                  </div>
                </div>
                <br>
                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                  <div class="col-md-6">
                    <input v-model="formData.email" id="email" type="email" class="form-control" name="email" autocomplete="email">
                  </div>
                </div>
                <br>
                <div class="form-group row">
                  <label for="phone" class="col-md-4 col-form-label text-md-right">Phone</label>
                  <div class="col-md-6">
                    <input v-model="formData.phone" id="phone" type="phone" class="form-control" name="phone" >
                  </div>
                </div>
                <br>
                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
                      Update
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>

<script setup>
    import { mapActions } from 'vuex'
</script>
<script>
export default {
    name:"update-employee",
    data(){
        return {
            formData:{
                id: null,
                first_name: null,
                last_name: null,
                email: null,
                phone: null,
                company_id: 0,
                _method:"patch"
            },
            companies: [],
            processing: false
        }
    },
    created: function(){
        this.getCompanies()
    },
    mounted(){
        this.showEmployee()
    },
    methods:{
        ...mapActions({
            signIn:'employee/update'
        }),
        changeCountry (event) {
            this.formData.company_id = event.target.value
        },
        async getCompanies(){
            this.processing = true;
            await axios.get('/sanctum/csrf-cookie');
            await  axios.get('/api/getCompanies')
              .then(response => {
                if ( response.status  === 200){
                    this.companies = response.data.companies;
                }
            }).catch(err => {
                if (err.response.status === 422) {
                this.errors = []
                    _.each(err.response.data.errors, error => {
                        _.each(error, e => {
                        this.errors.push(e)
                        })
                    });
                }
            }).finally(()=>{
                this.processing = false;
            });
        },
        async showEmployee(){
            await axios.get('/sanctum/csrf-cookie')
            await axios.get(`/api/employee/${this.$route.params.id}`).then(response=>{
                const { id, first_name, last_name, email, phone, company_id } = response.data
                this.formData.id = id;
                this.formData.first_name = first_name;
                this.formData.last_name = last_name;
                this.formData.email = email;
                this.formData.phone = phone
                this.formData.company_id = company_id;
            }).catch(error=>{
                console.log(error)
            }).finally(()=>{
                this.processing = false;
            });
        },
        async update(){
            //e.preventDefault() // Prevent page from reloading.
            //this.$emit('submit', this.formData);
            this.processing = true;
            await axios.get('/sanctum/csrf-cookie')
            await axios.post(`/api/employee/${this.$route.params.id}`,this.formData).then(response=>{
                this.$router.push({name:"list-employee"})
            }).catch(error=>{
                console.log(error)
            }).finally(()=>{
                this.processing = false;
            });
        }
    }
}
</script>
