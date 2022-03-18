<template>
    <div class="form-group">
        <h1>{{name}}</h1>
        <input type="text" @blur="saveName" v-model="name" class="form-control" :class="{ 'is-invalid': $v.name.$error }">
        <div class="invalid-feedback" v-if="!$v.name.required">
            Обязательное поле.
        </div>
        <div class="invalid-feedback" v-if="!$v.name.maxLength">
            Макс. кол-во символов: {{$v.name.$params.maxLength.max}}.
        </div>
        <div class="spinner-grow text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{ errors[0] }}
        </div>
    </div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'
export default {
    props: [
        'deskId'
    ],
    data() {
        return {
            name: null,
            errored: false,
            errors: [],
            loading: true,
        }
    },
    methods: {
        saveName() {
            this.$v.$touch()
            if(this.$v.$anyError) {
                return;
            }
            axios.post('/api/v1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name
                })
                .then(response => {
                    this.name =  response.data.name
                })
                .catch(error => {
                    console.log(error)
                    if (error.response.data.errors.name) {
                        this.errors = []
                        this.errors.push(error.response.data.errors.name[0])
                    }
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        }
    },
    mounted() {
        axios.get('/api/v1/desks/' + this.deskId)
            .then(response => {
                this.name =  response.data.data.name
            })
            .catch(error => {
                console.log(error)
                this.errored = true;
            })
            .finally(() => {
                this.loading = false;
            })
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255)
        }
    }
}
</script>

<style scoped>

</style>
