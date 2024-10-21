<template>
  <div id="app" class="container">
    <h1>Lista Zadań</h1>
    <SearchTask @search="setSearchTerm" />
    <TaskList 
      :tasks="filteredTasks" 
      @delete-task="deleteTask" 
      @update-task="updateTask"
    />
    <AddTask @add-task="addTask" />
  </div>
</template>

<script>
import TaskList from './components/TaskList.vue'
import AddTask from './components/AddTask.vue'
import SearchTask from './components/SearchTask.vue'

export default {
  name: 'App',
  components: {
    TaskList,
    AddTask,
    SearchTask
  },
  data() {
    return {
      tasks: JSON.parse(localStorage.getItem('tasks')) || [],
      searchTerm: '',
    };
  },
  computed: {
    filteredTasks() {
      if (this.searchTerm.length < 2) return this.tasks;
      return this.tasks.filter(task =>
        task.text.toLowerCase().includes(this.searchTerm.toLowerCase())
      );
    }
  },
  methods: {
    addTask(newTask) {
      this.tasks.push(newTask);
      this.saveTasks();
    },
    deleteTask(taskIndex) {
      this.tasks.splice(taskIndex, 1);
      this.saveTasks();
    },
    updateTask(updatedTask, taskIndex) {
      this.tasks[taskIndex] = updatedTask;
      this.saveTasks();
    },
    saveTasks() {
      localStorage.setItem('tasks', JSON.stringify(this.tasks));
    },
    setSearchTerm(searchTerm) {
      this.searchTerm = searchTerm;
    }
  }
}
</script>

<style>
body {
  margin: 0;
  padding: 0;
  font-family: Arial, sans-serif;
  background-image: url('@/assets/bg.jpg'); 
  background-size: cover; 
  background-position: center;
  background-attachment: fixed; 
  height: 100vh; 
  display: flex; 
  justify-content: center; 
  align-items: center; 
}

.container {
  max-width: 600px;
  width: 100%; 
  padding: 20px;
}

.task-container {
  background-color: rgba(255, 255, 255, 0.9); 
  border-radius: 10px; 
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  padding: 20px;
  display: flex;
  flex-direction: column; 
  gap: 10px; 
}

h1 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}
</style>