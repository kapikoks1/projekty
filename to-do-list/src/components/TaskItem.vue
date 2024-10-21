<template>
    <li class="task-item">
      <div v-if="!isEditing" class="task-display">
        <span v-html="highlightedTask"></span>
        <span class="due-date">{{ formattedDueDate }}</span>
        <div class="task-buttons">
          <button @click="isEditing = true">✏️</button>
          <button @click="deleteTask">🗑️</button>
        </div>
      </div>
      <div v-else class="task-edit">
        <input v-model="editableTask" @blur="saveTask" class="edit-input" />
        <input type="datetime-local" v-model="editableDueDate" class="edit-datetime" />
        <button @click="saveTask">Zapisz</button>
      </div>
    </li>
  </template>
  
  <script>
  export default {
    name: 'TaskItem',
    props: {
      task: Object,
      index: Number
    },
    data() {
      return {
        isEditing: false,
        editableTask: this.task.text,
        editableDueDate: this.task.dueDate ? this.task.dueDate.substring(0, 16) : '' 
      };
    },
    computed: {
      highlightedTask() {
        const searchTerm = this.$parent.$parent.searchTerm;
        if (searchTerm.length < 2) return this.task.text;
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return this.task.text.replace(regex, '<mark>$1</mark>');
      },
      formattedDueDate() {
        if (!this.task.dueDate) return '';
        const date = new Date(this.task.dueDate);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}.${month}.${year}, ${hours}:${minutes}`;
      }
    },
    methods: {
      saveTask() {
        if (this.editableTask.length >= 3 && this.editableTask.length <= 255) {
          this.isEditing = false;
          this.$emit('update-task', {
            ...this.task,
            text: this.editableTask,
            dueDate: this.editableDueDate
          }, this.index);
        }
      },
      deleteTask() {
        this.$emit('delete-task', this.index);
      }
    }
  }
  </script>



  <style>
  .task-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9f9f9; 
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ddd;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); 
  }
  
  .task-display {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }
  
  .due-date {
    font-size: 12px;
    color: #999;
    margin-right: 10px;
  }
  
  .task-buttons button {
    background-color: #f0f0f0;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
    margin-left: 10px;
  }
  
  .task-buttons button:hover {
    background-color: #ddd;
  }
  
  .edit-input,
  .edit-datetime {
    margin-top: 10px; 
    padding: 5px;
    width: 100%;
  }
  
  .edit-datetime {
    margin-left: 10px;
  }
  </style>