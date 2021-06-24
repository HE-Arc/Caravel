<template>
  <v-container>
    <router-view v-if="$route.name != 'tasks'" />
    <div v-else>
      <v-row>
        <v-col cols="12" md="8"></v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="9">
          <search-bar
            ref="searchBar"
            class="mb-4"
            :hasFilter="true"
            @handle-tasks="loadTasks"
            @update-state="updateState"
          >
            <v-btn
              color="success"
              class="float-right"
              :to="{ name: 'newTask' }"
              small
              >{{ $t("global.add") }}
            </v-btn>
          </search-bar>
        </v-col>
      </v-row>
      <v-row v-if="tasks.length > 0">
        <v-col cols="12" md="8">
          <div class="text-h5 transition-swing"></div>
          <v-list flat v-for="(items, key) in tasksGrouped" :key="key">
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
import groupModule from "@/store/modules/groups";
import { Task } from "@/types/Task";
import TaskListItem from "@/components/task/TaskItemList.vue";
import { Dictionary, TaskType } from "@/types/helpers";
import moment from "moment";
import SearchBar from "@/components/SearchBar.vue";

@Component({
  components: {
    TaskListItem,
    SearchBar,
  },
})
export default class TaskList extends Vue {
  isFiltered = false;
  filteredTasks: Task[] = [];

  get groupId(): string {
    return groupModule.selectedId;
  }

  get tasks(): Task[] {
    return this.isFiltered ? this.filteredTasks : taskModule.tasksFuture;
  }

  updateState(state: boolean): void {
    this.isFiltered = state;
  }

  loadTasks(tasks: Task[]): void {
    this.filteredTasks = tasks;
  }

  get tasksGrouped(): Dictionary<Task[]> {
    let tasksByDays = {};
    this.tasks.forEach((task: Task) => {
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
    return this.tasks.filter(
      (item) => item.tasktype_id == TaskType.PROJECT.toString()
    );
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
