<template>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">Company</div>

            <div class="card-body">

              <!-- THIS SECTION IS FOR ERRORS THAT WOULD COME FROM API -->
              <div v-if="errors">
                <div v-for="error in errors" class="alert alert-danger">{{ error }}</div>
              </div>

              <!-- FORM WITH v-if WILL BE SHOWN BUT THEN HIDDEN AFTER SUCCESS SUBMIT -->
              <form @submit.prevent="update">

                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                  <div class="col-md-6">
                    <!-- NOTICE v-model="formData.name" - THAT'S HOW IT GETS ATTACHED TO THE FIELD -->
                    <input v-model="formData.name" id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
                  </div>
                </div>

                <div class="form-group row">
                  <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                  <div class="col-md-6">
                    <input v-model="formData.email" id="email" type="email" class="form-control" name="email" required autocomplete="email">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                  <div class="col-md-6">
                    <input v-model="formData.website" id="website" type="website" class="form-control" name="website" >
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-md-right">Logo</label>
                  <div class="col-md-6">
                    <div class="custom-file">
                      <!-- MOST IMPORTANT - SEE "ref" AND "@change" PROPERTIES -->
                      <input type="file" class="custom-file-input" id="customFile"
                          ref="file" @change="handleFileObject()">
                      <label class="custom-file-label text-left" for="customFile">{{ avatarName }}</label>
                    </div>
                  </div>
                </div>

                <div v-if="formData.logo">
                    <img  :height="100" :width="100" :src="formData.logo" alt="">
                </div>

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button @click.prevent="update" type="submit" class="btn btn-primary" style="background: #42b983; border: #42b983;">
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
    name:"update-company",
    data(){
        return {
            formData:{
                id: "",
                name:"",
                email:"",
                website:"",
                logo:"",
                _method:"patch"
            },
            avatar: null,
            errors: null,
            processing:false
        }
    },
    mounted(){
        this.showCompany()
    },
    methods:{
        ...mapActions({
            signIn:'company/update'
        }),
        async showCompany(){
            await axios.get('/sanctum/csrf-cookie')
            await axios.get(`/api/company/${this.$route.params.id}`).then(response=>{
                const { id, name, email, logo, website } = response.data
                this.formData.id = id
                this.formData.name = name
                this.formData.email = email
                this.formData.website = website
                this.formData.logo = logo
            }).catch(error=>{
                console.log(error)
            })
        },
        async update(){

            this.errors = null

            let formData = new FormData()
            formData.append('avatar', this.avatar)
            _.each(this.formData, (value, key) => {
                formData.append(key, value)
            })

            this.processing = true

            await axios.get('/sanctum/csrf-cookie')
            await axios.post(`/api/company/${this.$route.params.id}`,formData).then(response=>{
                this.$router.push({name:"list-company"})
            }).catch(error=>{
                console.log(error)
            }).finally(()=>{
                this.processing = false
            });
        },
        handleFileObject() {
          this.avatar = this.$refs.file.files[0]
        }
    }
}
</script>
