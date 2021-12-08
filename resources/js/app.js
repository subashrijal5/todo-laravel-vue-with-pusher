/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: Echo } = require('laravel-echo');

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('create-todo', require('./components/TodoCreate.vue').default);
Vue.component('task-list', require('./components/TaskList.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        todos: [],
    },

    created() {

        this.fetchTodos();
        window.Echo.private('taskstore')
            .listen('TaskCreated', (e) => {
                e.task.user = e.user
                this.todos.push(
                    e.task
                );
            });
        window.Echo.private('taskupdate')
            .listen('TaskUpdate', (e) => {
                this.fetchTodos()
            });
    },

    methods: {

        fetchTodos() {
            axios.get('/todos/FetchData').then(response => {
                this.todos = response.data.data;
            });
        },
        addTask(task) {
            this.todos.push(task);
            axios.post('/todos', task).then(response => {
                console.log(response.data);
            });
        },
        updateTask(task) {
            axios.post('/todos/update', task).then(response => {
                console.log(response.data);
            });
        },

    }
});
