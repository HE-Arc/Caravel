<template>
  <v-container>
    <router-view v-if="$route.name != 'tasks'" />
    <div v-else>
      <h1>taskslist</h1>
    </div>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";

@Component
export default class TaskList extends Vue {}
</script>
