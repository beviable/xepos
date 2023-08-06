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
              <form @submit.prevent="submit">

                <div class="form-group row">
                  <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                  <div class="col-md-6">
                    <!-- NOTICE v-model="formData.name" - THAT'S HOW IT GETS ATTACHED TO THE FIELD -->
                    <input v-model="formData.name" id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
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
                  <label for="website" class="col-md-4 col-form-label text-md-right">Website</label>

                  <div class="col-md-6">
                    <input v-model="formData.website" id="website" type="website" class="form-control" name="website" >
                  </div>
                </div>

                <br>

                <div class="form-group row">
                    <label for="logo" class="col-md-4 col-form-label text-md-right">Logo</label>
                    <div class="col-md-6">
                        <input type="file" class="form-control" id="customFile"
                          ref="file" @change="handleFileObject()">
                    </div>
                </div>

                <br>

                <div class="form-group row mb-0">
                  <div class="col-md-6 offset-md-4">
                    <button class="btn btn-primary" style="background: #42b983; border: #42b983;">
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
      name:"create-company",
      data() {
        return {
          formData: {
            name: null,
            email: null,
            website: null,
            logo: null
          },
          avatar: null,
          errors: null,
          processing:false
        }
      },
      methods: {
        ...mapActions({
            signIn:'company/create'
        }),
        async submit() {
            this.errors = null;
            this.processing = true

            let formData = new FormData()
            formData.append('avatar', this.avatar)
            _.each(this.formData, (value, key) => {
                formData.append(key, value)
            })

            await axios.get('/sanctum/csrf-cookie');
            await axios.post('/api/company', formData, {
              headers: {
                'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
              }
            }).then(response => {
            if ( response.status  === 200){
                this.$router.push({name:"list-company"});
            }
          }).catch(err => {
            if (err.response.status === 422) {
              this.errors = [];
              _.each(err.response.data.errors, error => {
                _.each(error, e => {
                  this.errors.push(e)
                })
              })

            }
          }).finally(()=>{
                this.processing = false;
            });
        },

        handleFileObject() {
          this.avatar = this.$refs.file.files[0]
        }
      }

    }

  </script>
