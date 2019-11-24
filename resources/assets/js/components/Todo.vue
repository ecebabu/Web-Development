<template>
  <div>
    <h2>ToDoList</h2>
    <form @submit.prevent="addTodo" class="mb-3">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Title" v-model="todo.title">
      </div>
      <div class="form-group">
        <textarea class="form-control" placeholder="Description" v-model="todo.description"></textarea>
      </div>
      <button type="submit" class="btn btn-light btn-block">Save</button>
    </form>
    <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchTodo(pagination.prev_page_url)">Previous</a></li>

        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
    
        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchTodo(pagination.next_page_url)">Next</a></li>
      </ul>
    </nav>
    <div class="card card-body mb-2" v-for="todo in todos" v-bind:key="todo.id">
      <h3>{{ todo.title }}</h3>
      <p>{{ todo.description }}</p>
      <hr>
      <button @click="editTodo(todo)" class="btn btn-warning mb-2">Edit</button>
      <button @click="deleteTodo(todo.id)" class="btn btn-danger">Delete</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      todos: [],
      todo: {
        id: '',
        title: '',
        description: ''
      },
      todo_id: '',
      pagination: {},
      edit: false
    };
  },

  created() {
    this.fetchTodo();
  },

  methods: {
    fetchTodo(page_url) {
      let vm = this;
      page_url = page_url || '/api/todo';
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.todo = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    deleteTodo(id) {
      if (confirm('Are You Sure?')) {
        fetch(`api/todo/${id}`, {
          method: 'delete'
        })
          .then(res => res.json())
          .then(data => {
            alert('Todo Removed');
            this.fetchTodo();
          })
          .catch(err => console.log(err));
      }
    },
    addTodo() {
      if (this.edit === false) {
        // Add
        fetch('api/todo', {
          method: 'post',
          description: JSON.stringify(this.todo),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Todo Added');
            this.fetchTodo();
          })
          .catch(err => console.log(err));
      } else {
        // Update
        fetch('api/todo', {
          method: 'put',
          description: JSON.stringify(this.todo),
          headers: {
            'content-type': 'application/json'
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert('Todo Updated');
            this.fetchTodo();
          })
          .catch(err => console.log(err));
      }
    },
    editTodo(todo) {
      this.edit = true;
      this.todo.id = todo.id;
      this.todo.todo_id = todo.id;
      this.todo.title = todo.title;
      this.todo.description = todo.description;
    },
    clearForm() {
      this.edit = false;
      this.todo.id = null;
      this.todo.todo_id = null;
      this.todo.title = '';
      this.todo.description = '';
    }
  }
};
</script>
