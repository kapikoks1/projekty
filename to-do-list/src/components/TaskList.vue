<template>
  <ul class="task-list">
    <li v-for="(task, index) in tasks" :key="index">
      <div v-if="!task.editing" class="task-item">
        <input
          type="checkbox"
          v-model="task.completed"
          @change="toggleComplete(index)"
        />
        <span :class="{ completed: task.completed }" @click="editTask(index)">
          <span v-html="highlight(task.text)"></span>
          <small>
            (Termin: {{ formatDeadline(task.deadline) }})
          </small>
        </span>
        <button @click="deleteTask(index)" class="delete-btn">
          <i class="fas fa-trash"></i> <!-- Ikona śmietnika -->
        </button>
      </div>
      <div v-else>
        <input
          v-model="task.text"
          @blur="saveTask(index)"
          @keyup.enter="saveTask(index)"
        />
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    tasks: {
      type: Array,
      required: true
    }
  },
  methods: {
    deleteTask(index) {
      this.$emit('deleteTask', index);
    },
    editTask(index) {
      this.$emit('editTask', index);
    },
    saveTask(index) {
      this.$emit('saveTask', index);
    },
    toggleComplete(index) {
      this.$emit('toggleComplete', index);
    },
    highlight(text) {
      const searchQuery = this.$parent.searchQuery;
      if (!searchQuery || searchQuery.length < 2) return text;
      const regex = new RegExp(`(${searchQuery})`, 'gi');
      return text.replace(regex, '<mark>$1</mark>');
    },
    formatDeadline(deadline) {
      if (!deadline) return 'Brak';
      const date = new Date(deadline);
      return `${date.toLocaleDateString()} ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`;
    }
  }
};
</script>

<style>
.task-list {
  list-style: none;
  padding: 0;
}

.task-list li {
  margin: 10px 0;
  padding: 10px;
  background-color: #f9f9f9;
  border-left: 5px solid #4CAF50;
  display: flex;
  align-items: center;
  border-radius: 4px;
}

/* Styl wykonanych zadań */
.completed {
  text-decoration: line-through;
  color: #aaa; /* Blaknięcie koloru dla ukończonych zadań */
}

/* Styl przycisku usuwania z ikoną śmietnika */
.delete-btn {
  background: none;
  border: none;
  color: #888;
  cursor: pointer;
  font-size: 18px;
  padding: 5px;
}

.delete-btn:hover {
  color: #e74c3c; /* Kolor czerwony podczas hover */
}

/* Styl dla checkboxów */
.task-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-grow: 1; /* Pozwala na rozwijanie */
}
</style>
