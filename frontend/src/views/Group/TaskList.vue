<template>
  <v-container>
    <router-view v-if="$route.name != 'tasks'" />
    <div v-else>
      <v-row>
        <v-col cols="12" md="8"></v-col>
      </v-row>
      <v-row v-if="tasks.length > 0">
        <v-col cols="12" md="8">
          <div class="text-h5 font-weight-light">
            {{ $tc("task.label", tasks.length) }}

            <v-btn
              color="success"
              class="float-right"
              :to="{ name: 'newTask' }"
              small
              >{{ $t("global.add") }}</v-btn
            >
          </div>
          <div class="text-h5 transition-swing"></div>
          <v-list flat v-for="(items, key) in tasksGrouped" :key="items.length">
            <div class="text-h6 font-weight-light title-days">
              <span class="line">{{ key | capitalize }}</span>
            </div>
            <task-list-item v-for="task in items" :key="task.id" :task="task" />
          </v-list>
        </v-col>
        <v-col cols="12" md="4">
          <div class="text-h5 font-weight-light">
            {{ $tc("task.types.3.label", projects.length) }}
          </div>
          <v-list flat>
            <task-list-item
              v-for="task in projects"
              :key="task.id"
              :task="task"
              :hasDueDate="true"
            >
            </task-list-item>
          </v-list>
        </v-col>
      </v-row>
      <v-row v-else>
        <v-col cols="12" md="8">
          <i18n path="task.no_results" tag="label">
            <router-link
              :to="{
                name: 'newTask',
              }"
              v-html="$t('task.create_link')"
            />
          </i18n>
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import taskModule from "@/store/modules/tasks";
import { Task } from "@/types/task";
import TaskListItem from "@/components/task/TaskItemList.vue";
import { Dictionary } from "@/types/helpers";
import moment from "moment";

@Component({
  components: {
    TaskListItem,
  },
  filters: {
    capitalize: function (value: string) {
      if (!value) return "";
      value = value.toString();
      return value.charAt(0).toUpperCase() + value.slice(1);
    },
  },
})
export default class TaskList extends Vue {
  get tasks(): Task[] {
    return taskModule.tasks;
  }

  get tasksGrouped(): Dictionary<Task[]> {
    let tasksByDays = {};
    taskModule.tasks.forEach((task: Task) => {
      const due = moment(task.due_at);
      const key = due.endOf("day").fromNow();
      if (tasksByDays[key] == undefined) {
        tasksByDays[key] = [task];
      } else {
        tasksByDays[key].push(task);
      }
    });
    return tasksByDays;
  }

  get projects(): Task[] {
    return taskModule.projects;
  }
}
</script>

<style lang="scss" scoped>
.title-days {
  width: 100%;
  border-bottom: 1px solid #e0e0e0;
  line-height: 0.1em;
  margin: 10px 0 20px;
  font-weight: 300;
  color: #717871;

  span {
    background: #fff;
    padding: 0 10px;
  }
}
</style>
