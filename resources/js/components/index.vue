<template>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <router-link class="nav-item nav-link active" :to="{name: 'home'}">Главная</router-link>
                        <router-link class="nav-item nav-link active" :to="{name: 'desks'}">Доски</router-link>
                    </div>
                </div>
            </nav>
            <div>
                <router-view></router-view>
            </div>
        </div>
</template>

<script>
export default {
    name: "index"
}
</script>

<style scoped>

</style>
