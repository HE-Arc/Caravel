<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <task-details />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import TaskDetails from "@/components/task/TaskDetails.vue";

@Component({
  components: {
    TaskDetails,
  },
})
export default class TaskNew extends Vue {}
</script>
