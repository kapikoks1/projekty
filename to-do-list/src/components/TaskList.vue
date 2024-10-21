<template>
    <ul class="task-list">
      <TaskItem 
        v-for="(task, index) in tasks" 
        :key="index" 
        :task="task" 
        :index="index"
        @delete-task="handleDeleteTask" 
        @update-task="handleUpdateTask"
      />
    </ul>
  </template>
  
  <script>
  import TaskItem from './TaskItem.vue'
  
  export default {
    name: 'TaskList',
    components: { TaskItem },
    props: {
      tasks: Array
    },
    methods: {
      handleDeleteTask(index) {
        this.$emit('delete-task', index);
      },
      handleUpdateTask(updatedTask, index) {
        this.$emit('update-task', updatedTask, index);
      }
    }
  }
  </script>



<style>
.task-list {
  list-style: none;
  padding: 0;
  margin: 0;
  background-color: rgba(255, 255, 255, 0.8); 
  padding: 20px;
  border-radius: 10px; 
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}
</style>  