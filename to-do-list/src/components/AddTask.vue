<template>
    <form @submit.prevent="submitTask" class="add-task-form">
      <input type="text" v-model="newTaskText" placeholder="Nowe zadanie" class="task-input"/>
      <input type="datetime-local" v-model="newTaskDueDate" class="date-input"/>
      <button type="submit" class="add-task-btn">Dodaj zadanie</button>
      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </template>
  
  <script>
  export default {
    data() {
      return {
        newTaskText: '',
        newTaskDueDate: '',
        error: ''
      };
    },
    methods: {
      submitTask() {
        this.error = '';
        if (this.newTaskText.length < 3 || this.newTaskText.length > 255) {
          this.error = 'Tekst musi mieć od 3 do 255 znaków';
          return;
        }
        if (this.newTaskDueDate && new Date(this.newTaskDueDate) <= new Date()) {
          this.error = 'Data musi być w przyszłości';
          return;
        }
        const newTask = {
          text: this.newTaskText,
          dueDate: this.newTaskDueDate || ''
        };
        this.$emit('add-task', newTask);
        this.newTaskText = '';
        this.newTaskDueDate = '';
      }
    }
  }
  </script>

<style>
.add-task-form {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 20px;
}

.task-input,
.date-input {
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.add-task-btn {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
}

.add-task-btn:hover {
  background-color: #218838;
}

.error {
  color: red;
  font-size: 12px;
}
</style>  