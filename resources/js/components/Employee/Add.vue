<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Add Employee</div>

            <div class="card-body">

              <!-- THIS SECTION IS FOR ERRORS THAT WOULD COME FROM API -->
              <div v-if="errors">
                <div v-for="error in errors" class="alert alert-danger">{{ error }}</div>
              </div>

              <!-- FORM WITH v-if WILL BE SHOWN BUT THEN HIDDEN AFTER SUCCESS SUBMIT -->
              <form @submit.prevent="create">

                <div class="form-group row">
                  <label for="company" class="col-md-4 col-form-label text-md-right">Company</label>
                  <div class="col-md-6">
                    <select @change="changeCompany($event)" class="form-control">
                        <option value='0' selected disabled>::Select Company::</option>
                        <option v-for="company in companies"
                        :value="company.id" :key="company.id">
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
                    <button @click.prevent="submit" type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
                      Add
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
       name:"create-employee",
      data() {
        return {
          formData: {
            first_name: null,
            last_name: null,
            email: null,
            phone: null,
            company_id: 0,
          },
          companies: [],
          errors: null,
          processing:false
        }
      },
      methods: {
        ...mapActions({
            signIn:'employee/create'
        }),
        changeCompany (event) {
            this.formData.company_id = event.target.value
        },

        async getCompanies(){
            this.processing = true
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
                this.processing = false
            });
        },

        async submit() {
          this.errors = null;
          this.processing = true

          await axios.get('/sanctum/csrf-cookie');
          await axios.post('/api/employee', this.formData).then(response => {
            if ( response.status  === 200){
                this.$router.push({name:"list-employee"});
            }
          }).catch(err => {
            if (err.response.status === 422) {
              this.errors = []
              _.each(err.response.data.errors, error => {
                _.each(error, e => {
                  this.errors.push(e)
                })
              })

            }
          }).finally(()=>{
                this.processing = false
          });
        }
      },
      created: function(){
        this.getCompanies()
     }

    }

  </script>
