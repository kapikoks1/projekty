<template>
  <div id="app">
    <h1>To-Do List</h1>

    <!-- Pole wyszukiwania z ikoną lupy -->
    <div class="search-container">
      <input
        type="text"
        v-model="searchQuery"
        placeholder="Szukaj..."
        class="search-box"
      />
      <i class="fas fa-search search-icon"></i> <!-- Ikona lupy -->
    </div>

    <!-- Lista zadań -->
    <TaskList
      :tasks="filteredTasks"
      @deleteTask="deleteTask"
      @editTask="editTask"
      @saveTask="saveTask"
    />

    <!-- Formularz dodawania zadań -->
    <div class="add-task">
      <input
        type="text"
        v-model="newTaskText"
        placeholder="Nowe zadanie"
      />
      <input
        type="datetime-local"
        v-model="newTaskDeadline"
      />
      <button @click="addTask">Dodaj zadanie</button>
    </div>

    <!-- Walidacja -->
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script>
import TaskList from './components/TaskList.vue'; // Upewnij się, że ścieżka jest poprawna

export default {
  components: {
    TaskList
  },
  data() {
    return {
      tasks: JSON.parse(localStorage.getItem('tasks')) || [],
      newTaskText: '',
      newTaskDeadline: '',
      searchQuery: '',
      error: ''
    };
  },
  computed: {
    filteredTasks() {
      if (this.searchQuery.length < 2) {
        return this.tasks;
      } else {
        return this.tasks.filter(task =>
          task.text.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
    }
  },
  methods: {
    addTask() {
      const text = this.newTaskText.trim();
      const deadline = this.newTaskDeadline;

      // Walidacja zadania
      if (text.length < 3 || text.length > 255) {
        this.error = "Zadanie musi mieć od 3 do 255 znaków.";
        return;
      }

      if (deadline && new Date(deadline) < new Date()) {
        this.error = "Data musi być pusta lub w przyszłości.";
        return;
      }

      this.tasks.push({ text, deadline, editing: false, completed: false });
      this.saveTasks();
      this.newTaskText = '';
      this.newTaskDeadline = '';
      this.error = '';
    },
    deleteTask(index) {
      this.tasks.splice(index, 1);
      this.saveTasks();
    },
    editTask(index) {
      this.tasks[index].editing = true;
    },
    saveTask(index) {
      this.tasks[index].editing = false;
      this.saveTasks();
    },
    toggleComplete(index) {
      this.tasks[index].completed = !this.tasks[index].completed;
      this.saveTasks();
    },
    saveTasks() {
      localStorage.setItem('tasks', JSON.stringify(this.tasks));
    }
  }
};
</script>

<style>
/* Styl tła */
/* Stylowanie dla całej strony */
/* Stylowanie dla całej strony */
body {
    font-family: 'Arial', sans-serif;
    background-image: url('@/assets/bg.jpg'); /* Ścieżka do tła w folderze assets */
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed; /* Tło nieruchome */
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

/* Główne stylowanie aplikacji */
#app {
    background-color: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 600px; /* Maksymalna szerokość kontenera */
    text-align: center;
    box-sizing: border-box; /* Zapobiegamy "wychodzeniu" poza ekran */
    margin: 0 auto; /* Automatyczny margines zapewnia wycentrowanie */
    position: relative; /* Ważne dla elementów wewnętrznych */
}

/* Stylowanie nagłówka */
h1 {
    font-family: 'Courier New', Courier, monospace;
    margin-bottom: 20px;
}

/* Styl pola wyszukiwania z ikoną lupy */
.search-container {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.search-box {
    padding: 10px 10px 10px 40px; /* Zostawiamy miejsce na ikonę lupy */
    width: 100%;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box; /* Box-sizing, aby nie rozjeżdżało się */
}

.search-icon {
    position: absolute;
    left: 10px;
    color: #888;
    font-size: 18px;
}

/* Styl formularza dodawania zadań */
.add-task {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    flex-wrap: wrap; /* Sprawia, że elementy będą się odpowiednio "owijać" */
}

.add-task input {
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    width: calc(100% - 120px); /* Dynamiczna szerokość, by dostosować się do małych ekranów */
    box-sizing: border-box;
}

.add-task input[type="datetime-local"] {
    width: calc(100% - 120px); /* Dopasowanie dla pola daty */
}

.add-task button {
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100px; /* Stała szerokość przycisku */
    flex-shrink: 0; /* Zapobiega kurczeniu się przycisku na małych ekranach */
}

.add-task button:hover {
    background-color: #45a049;
}

/* Styl walidacji błędów */
.error {
    color: red;
    font-size: 14px;
}

/* Stylowanie listy zadań */
.task-list {
    list-style: none;
    padding: 0;
    margin: 20px 0;
}

.task-list li {
    margin: 10px 0;
    padding: 10px;
    background-color: #f9f9f9;
    border-left: 5px solid #4CAF50;
    display: flex;
    align-items: center;
    justify-content: space-between; /* Używamy tego, by równomiernie rozłożyć przestrzeń */
    border-radius: 4px;
}

/* Styl zadań ukończonych */
.completed {
    text-decoration: line-through;
    color: #aaa;
}

/* Styl przycisku usuwania */
.delete-btn {
    background: none;
    border: none;
    color: #888;
    cursor: pointer;
    font-size: 16px;
    margin-left: 10px;
}

.delete-btn:hover {
    color: red;
}

/* Media Queries dla lepszej responsywności na iPhone'ach i innych mniejszych urządzeniach */
@media (max-width: 600px) {
    #app {
        max-width: 100%; /* Ustawiamy pełną szerokość na mniejszych ekranach */
        padding: 10px;
    }

    .add-task {
        flex-direction: column; /* Ustawiamy kolumnę dla lepszej czytelności */
        gap: 10px;
    }

    .add-task input, .add-task button, .add-task input[type="datetime-local"] {
        width: 100%; /* Pełna szerokość elementów */
    }

    .task-list li {
        flex-direction: column; /* Zadania wyświetlają się w kolumnie */
        text-align: left;
    }

    .delete-btn {
        margin-left: 0;
        align-self: flex-end; /* Przycisk usuwania wyśrodkowany na mniejszych ekranach */
    }
}


</style>
