<template>
<div>
    <h1>Доски</h1>
    <div class="row">
        <div class="col-lg-4" v-for="desk in desks">
            <div class="card mt-3" style="width: 18rem;">
                <a href="#" class="card-body">
                    <h5 class="card-title">{{ desk.name }}</h5>
                </a>
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
    mounted() {
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
}
</script>

<style scoped>

</style>
