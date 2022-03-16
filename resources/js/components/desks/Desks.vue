<template>
<div>
    <h1>Доски</h1>
    <div class="row">
        <div class="col-lg-4">
            <div class="card mt-3" style="width: 18rem;">
                <a href="#" class="card-body">
                    <h5 class="card-title">Card title</h5>
                </a>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            desks: []
        }
    },
    mounted() {
        axios.get('/api/V1/desks')
        .then(response => {
            this.desks =  response.data.data
        })
    }
}
</script>

<style scoped>

</style>
