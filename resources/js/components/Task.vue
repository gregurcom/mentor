<template>
    <div v-if="loading === false">
        <template v-if="tasks.length">
            <div class="mb-5" v-for="task in tasks">
                <h3 class="d-inline"><a href="#" class="head-link text-decoration-none text-black">{{ task.title }}</a></h3>
                <span class="text-secondary px-2">{{ task.end_time }}</span>
                <p class="mt-2">{{ task.description }}</p>

                <button type="submit" class="btn btn-outline-danger" @click="deleteTask(task.id)">Delete</button>
                <a :href="`/tasks/${task.id}/edit`" class="btn btn-outline-info">Edit</a>
            </div>
        </template>
        <template v-else>
            <div class="text-center alert alert-info text-center">
                You have not tasks yet
            </div>
        </template>

        <form @submit.prevent="submit" class="mt-5">
            <input type="text" name="title" class="form-control" placeholder="Task" v-model="fields.title">
            <div class="input-group mt-1">
                <input type="datetime-local" name="end_time" class="form-control" v-model="fields.end_time">
                <button class="btn btn-outline-info" type="button">End time (optional)</button>
            </div>
            <textarea name="description" class="form-control mt-1" placeholder="Description" v-model="fields.description"></textarea>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-outline-dark">Create</button>
            </div>
        </form>
    </div>
    <div v-else class="atom">
        <atom-spinner
            :animation-duration="1000"
            :size="80"
            :color="'#69d2f1'"
        />
    </div>
</template>

<script>
    import {AtomSpinner} from 'epic-spinners'
    export default {
        components: {
            AtomSpinner
        },
        data() {
            return {
                tasks: [],
                fields: {},
                loading: true,
            }
        },
        mounted() {
            axios.get('api/v1/tasks').then(response => {
                this.tasks = response.data
                this.loading = false
            })
        },
        methods: {
            fetchTasks() {
                axios.get('api/v1/tasks').then(response => {
                    this.tasks = response.data;
                });
            },
            submit() {
                axios.post('api/v1/tasks', this.fields).then(() => {
                    this.fields = {}
                    this.fetchTasks()
                }).catch (error => {
                    console.log(error)
                })
            },
            deleteTask(id) {
                axios.delete('api/v1/tasks/' + id).then(() => {
                    this.fetchTasks()
                })
            },
        }
    }
</script>
