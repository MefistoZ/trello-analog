<template>
    <div class="form-group">


        <h1>{{ name }}</h1>
        <input type="text" @blur="saveName" v-model="name" class="form-control"
               :class="{ 'is-invalid': $v.name.$error }">
        <div class="invalid-feedback" v-if="!$v.name.required">
            Обязательное поле.
        </div>
        <div class="invalid-feedback" v-if="!$v.name.maxLength">
            Макс. кол-во символов: {{ $v.name.$params.maxLength.max }}.
        </div>

        <form @submit.prevent="addNewDeskList" class="mt-3">
            <div class="form-group mb-2">
                <input type="text" v-model="deskListName" class="form-control"
                       placeholder="Введите название списка" :class="{ 'is-invalid': $v.deskListName.$error }">
                <div class="invalid-feedback" v-if="!$v.deskListName.required">
                    Обязательное поле.
                </div>
                <div class="invalid-feedback" v-if="!$v.deskListName.maxLength">
                    Макс. кол-во символов: {{ $v.deskListName.$params.maxLength.max }}.
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>

        <div class="row">
            <div class="col-lg-4" v-for="deskList in deskLists">
                <div class="card mt-3">
                    <div class="card-body">
                        <form @submit.prevent="updateDeskList(deskList.id, deskList.name)"
                              v-if="deskListInputId === deskList.id">
                            <input type="text" v-model="deskList.name" class="form-control"
                                   placeholder="Введите название списка">
                        </form>
                        <h4 v-else class="card-title" style="cursor: pointer" title="Редактировать"
                            @click="deskListInputId = deskList.id">{{ deskList.name }}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                                <path fill-rule="evenodd"
                                      d="M11.013 1.427a1.75 1.75 0 012.474 0l1.086 1.086a1.75 1.75 0 010 2.474l-8.61 8.61c-.21.21-.47.364-.756.445l-3.251.93a.75.75 0 01-.927-.928l.929-3.25a1.75 1.75 0 01.445-.758l8.61-8.61zm1.414 1.06a.25.25 0 00-.354 0L10.811 3.75l1.439 1.44 1.263-1.263a.25.25 0 000-.354l-1.086-1.086zM11.189 6.25L9.75 4.81l-6.286 6.287a.25.25 0 00-.064.108l-.558 1.953 1.953-.558a.249.249 0 00.108-.064l6.286-6.286z"></path>
                            </svg>
                        </h4>
                        <button type="button" @click="deleteDeskList(deskList.id)" class="btn btn-danger">Удалить
                        </button>
                        <div class="card mt-3 mb-3 bg-light" v-for="card in deskList.cards" :key="card.id">
                            <div class="card-body">
                                <h4 class="card-title" style="cursor: pointer" title="Редактировать">
                                    {{ card.name }}
                                </h4>

                                <b-button @click="getCard(card.id)" variant="primary" v-b-modal.modal-prevent-closing>
                                    Открыть
                                </b-button>

                                <button type="button" @click="deleteCard(card.id)" class="btn btn-secondary btn-danger">
                                    Удалить
                                </button>
                            </div>
                        </div>
                        <form @submit.prevent="addNewCard(deskList.id)">
                            <input v-model="cardNames[deskList.id]" type="text" class="form-control"
                                   placeholder="Введите название карточки"
                                   :class="{ 'is-invalid': $v.cardNames.$each[deskList.id].$error }">
                            <div class="invalid-feedback" v-if="!$v.cardNames.$each[deskList.id].required">
                                Обязательное поле.
                            </div>
                            <div class="invalid-feedback" v-if="!$v.cardNames.$each[deskList.id].maxLength">
                                Макс. кол-во символов: {{ $v.cardNames.$each[deskList.id].$params.maxLength.max }}.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="spinner-grow text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{ errors[0] }}
        </div>

        <b-modal
            id="modal-prevent-closing"
            ref="modal"
            title="Имя карточки"
            hide-footer
        >
            <b-form-group
                label="Изменить имя"
                label-for="name-input"
                invalid-feedback="Имя обязательно"
            >
                <b-form-input
                    id="name-input"
                    v-model="currentCard.name"
                    required
                    @input="updateCard(currentCard.id, currentCard.name)"
                ></b-form-input>
                <hr>
                <form @submit.prevent="addNewTask">
                    <input v-model="new_task_name" type="text" class="form-control" placeholder="Введите название задачи">
                    <button type="submit" class="btn btn-success mt-3">Создать</button>
                </form>
                <hr>

                <div class="form-check" v-for="task in currentCard.tasks">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        {{ task.name }}
                    </label>
                    <button type="button" class="close" @click="deleteTask(task.id)" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </b-form-group>
        </b-modal>
    </div>
</template>

<script>
import {required, maxLength} from 'vuelidate/lib/validators'

export default {
    props: [
        'deskId'
    ],
    data() {
        return {
            name: null,
            deskListName: null,
            errored: false,
            errors: [],
            loading: true,
            deskLists: [],
            deskListInputId: null,
            cardNames: [],
            currentCard: [],
            new_task_name: '',
        }
    },
    methods: {
        deleteTask(id) {
            if (confirm('Вы действительно хотите удалить задачу?')) {
                axios.post('/api/v1/tasks/' + id, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getCard(this.currentCard.id)
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
        addNewTask() {
            console.log(this.currentCard.id);
            axios.post('/api/v1/tasks/', {
                name: this.new_task_name,
                card_id: this.currentCard.id
            })
                .then(response => {
                    this.new_task_name = ''
                    this.currentCard.tasks.push(response.data.data)
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        getCard(id) {
            axios.get('/api/v1/cards/' + id)
                .then(response => {
                    this.currentCard = response.data.data
                    console.log(this.currentCard);
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        addNewCard(deskListId) {
            this.$v.cardNames.$each[deskListId].$touch()
            if (this.$v.cardNames.$each[deskListId].$anyError) {
                return;
            }

            axios.post('/api/v1/cards/', {
                name: this.cardNames[deskListId],
                desk_list_id: deskListId
            })
                .then(response => {
                    this.$v.$reset()
                    this.getDeskLists()
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
        },
        updateCard(id, cardName) {
            axios.post('/api/v1/cards/' + id, {
                _method: 'PUT',
                name: cardName
            })
                .then(response => {
                    this.getDeskLists()
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
        },
        getDeskLists() {
            axios.get('/api/v1/desk-lists', {
                params: {
                    desk_id: this.deskId
                }
            })
                .then(response => {
                    this.deskLists = response.data.data
                    console.log(this.deskLists);
                    this.deskLists.forEach(el => {
                        this.cardNames[el.id] = ''
                    })
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
        },
        saveName() {
            this.$v.name.$touch()
            if (this.$v.name.$anyError) {
                return;
            }
            axios.post('/api/v1/desks/' + this.deskId, {
                _method: 'PUT',
                name: this.name
            })
                .then(response => {
                    this.name = response.data.name
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
        },
        addNewDeskList() {
            this.$v.deskListName.$touch()
            if (this.$v.deskListName.$anyError) {
                return;
            }
            axios.post('/api/v1/desk-lists/', {
                name: this.deskListName,
                desk_id: this.deskId
            })
                .then(response => {
                    this.$v.$reset()
                    this.deskListName = '';
                    this.deskLists = [];
                    this.cardNames = [];
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        deleteDeskList(deskListId) {
            if (confirm('Вы действительно хотите удалить список?')) {
                axios.post('/api/v1/desk-lists/' + deskListId, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.deskLists = [];
                        this.getDeskLists()
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
        deleteCard(cardId) {
            if (confirm('Вы действительно хотите удалить список?')) {
                axios.post('/api/v1/cards/' + cardId, {
                    _method: 'DELETE'
                })
                    .then(response => {
                        this.getDeskLists()
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
        updateDeskList(deskListId, deskListName) {
            axios.post('/api/v1/desk-lists/' + deskListId, {
                _method: 'PUT',
                name: deskListName
            })
                .then(response => {
                    this.deskListInputId = null
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
                this.name = response.data.data.name
            })
            .catch(error => {
                console.log(error)
                this.errored = true;
            })
            .finally(() => {
                this.loading = false;
            })
        this.getDeskLists()
    },
    validations: {
        name: {
            required,
            maxLength: maxLength(255)
        },
        deskListName: {
            required,
            maxLength: maxLength(255)
        },
        cardNames: {
            $each: {
                required,
                maxLength: maxLength(255)
            }
        }
    }
}
</script>

<style scoped>

</style>
