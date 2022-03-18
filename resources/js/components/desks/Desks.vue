<template>
<div>
    <h1>Доски</h1>

    <form @submit.prevent="addNewDesk">
        <div class="form-group mb-2">
            <input type="text" v-model="name" class="form-control" id="nameDesk" placeholder="Введите имя доски" :class="{ 'is-invalid': $v.name.$error }">
            <div class="invalid-feedback" v-if="!$v.name.required">
                Обязательное поле.
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength">
                Макс. кол-во символов: {{$v.name.$params.maxLength.max}}.
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>

    <div class="alert alert-danger mt-3" role="alert" v-if="errored">
        Ошибка загрузки данных! <br>
        {{ errors[0] }}
    </div>

    <div class="row">
        <div class="col-lg-4" v-for="desk in desks">
            <div class="card mt-3">
                <router-link class="nav-item nav-link active" :to="{name: 'ShowDesk', params: {deskId: desk.id}}">
                    <h4 class="card-title">{{ desk.name }}</h4>
                </router-link>
                <button type="button" @click="deleteDesk(desk.id)" class="btn btn-danger">Удалить</button>
            </div>
        </div>
    </div>
    <div class="spinner-grow text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
</div>
</template>

<script>
import { required, maxLength } from 'vuelidate/lib/validators'
export default {
    data() {
        return {
            desks: [],
            errored: false,
            errors: [],
            loading: true,
            name: null,
        }
    },
    methods: {
        deleteDesk(deskId) {
            if (confirm('Вы действительно хотите удалить доску?')) {

            }
            axios.post('/api/v1/desks/' + deskId, {
                _method: 'DELETE'
            })
                .then(response => {
                    this.desks = [];
                    this.getAllDesks()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        getAllDesks() {
            axios.get('/api/v1/desks')
                .then(response => {
                    this.desks =  response.data.data
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        addNewDesk() {
            this.$v.$touch()
            if(this.$v.$anyError) {
                return;
            }
            axios.post('/api/v1/desks/', {
                name: this.name
            })
                .then(response => {
                    this.name = '';
                    this.desks = [];
                    this.getAllDesks()
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
        this.getAllDesks()
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
