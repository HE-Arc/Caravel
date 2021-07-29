<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <task-details :data="task" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import TaskDetails from "@/components/task/TaskDetails.vue";
import Factory from "@/types/Factory";
import { TaskForm } from "@/types/taskForm";
import taskModule from "@/store/modules/tasks";

@Component({
  components: {
    TaskDetails,
  },
})
export default class TaskCreateOrEdit extends Vue {
  get task(): TaskForm {
    if (this.$route.params.task_id == undefined) return Factory.getTaskForm();
    const id = this.$route.params.task_id;
    const originalTask = taskModule.getTask(id);
    if (originalTask) {
      let task: TaskForm = JSON.parse(JSON.stringify(originalTask));
      task.subject_id = task.subject_id.toString();
      task.tasktype_id = task.tasktype_id.toString();
      return task;
    } else {
      return Factory.getTaskForm();
    }
  }
}
</script>
