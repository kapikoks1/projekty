<template>
    <input type="text" v-model="searchText" placeholder="Szukaj..." @input="handleSearch" class="search-input"/>
  </template>
  
  <script>
  export default {
    data() {
      return {
        searchText: ''
      };
    },
    methods: {
      handleSearch() {
        this.$emit('search', this.searchText);
      }
    }
  }
  </script>
<style>
.search-input {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  margin-bottom: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
}
</style>  