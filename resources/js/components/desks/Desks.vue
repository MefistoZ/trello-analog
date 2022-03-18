<template>
<div>
    <h1>Доски</h1>
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
    <div class="alert alert-danger" role="alert" v-if="errored">
        Ошибка загрузки данных!
    </div>
    <div class="spinner-grow text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            desks: [],
            errored: false,
            loading: true,
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
        }
    },
    mounted() {
        this.getAllDesks()
    }
}
</script>

<style scoped>

</style>
