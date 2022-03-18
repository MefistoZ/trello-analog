<template>
    <div class="form-group">
        <input type="text" @blur="saveName" v-model="name" class="form-control">
    </div>
</template>

<script>
export default {
    props: [
        'deskId'
    ],
    data() {
        return {
            name: null,
            errored: false,
            loading: true,
        }
    },
    methods: {
        saveName() {
            axios.post('/api/v1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name
                })
                .then(response => {
                    this.name =  response.data.name
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
    }
}
</script>

<style scoped>

</style>
