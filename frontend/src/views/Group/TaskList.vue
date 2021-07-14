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
            ref="search"
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
      <v-row v-if="!isLoaded()">
        <v-col cols="12">
          <div class="text-center">
            <v-progress-circular
              :size="70"
              :width="7"
              color="primary"
              indeterminate
              class="mt-5"
            ></v-progress-circular>
          </div>
        </v-col>
      </v-row>
      <v-row v-else-if="tasks.length > 0">
        <v-col cols="12" md="8">
          <div class="text-h5 transition-swing"></div>
          <v-list flat v-for="(items, key) in tasksGrouped" :key="key">
            <div class="text-h6 font-weight-light title-days">
              <span class="line">{{ key | capitalize }}</span>
            </div>
            <task-list-item v-for="task in items" :key="task.id" :task="task" />
          </v-list>
          <div class="text-center">
            <v-pagination
              v-show="pages > 1"
              v-model="page"
              :length="pages"
              circle
            ></v-pagination>
          </div>
        </v-col>
        <v-col cols="12" md="4" v-if="projects.length > 0">
          <div class="text-h5 font-weight-light">
            {{ $tc("task.types.3.label", projects.length) }}
          </div>
          <paginate :items="projects" :perPage="5">
            <template #default="{ items }">
              <v-list flat>
                <task-list-item
                  v-for="task in items"
                  :key="task.id"
                  :task="task"
                  :hasDueDate="true"
                >
                </task-list-item>
              </v-list>
            </template>
          </paginate>
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
import { Component, Ref, Vue, Watch } from "vue-property-decorator";
import taskModule from "@/store/modules/tasks";
import groupModule from "@/store/modules/groups";
import { Task } from "@/types/task";
import TaskListItem from "@/components/task/TaskItemList.vue";
import { Dictionary, TaskType } from "@/types/helpers";
import moment from "moment";
import SearchBar from "@/components/SearchBar.vue";
import Paginate from "@/components/utility/Paginate.vue";

@Component({
  components: {
    TaskListItem,
    SearchBar,
    Paginate,
  },
})
export default class TaskList extends Vue {
  @Ref() readonly search!: SearchBar;
  isFiltered = false;
  filteredTasks: Task[] = [];
  page = 1;
  perPage = 10;

  get groupId(): string {
    return groupModule.selectedId;
  }

  get pages(): number {
    return Math.ceil(this.tasks.length / this.perPage);
  }

  get visibleTasks(): Task[] {
    return this.tasks.slice(
      (this.page - 1) * this.perPage,
      this.page * this.perPage
    );
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

  isLoaded(): boolean {
    return this.search ? this.search.isLoaded || !this.isFiltered : true;
  }

  get tasksGrouped(): Dictionary<Task[]> {
    let tasksByDays = {};
    this.visibleTasks.forEach((task: Task) => {
      const due = moment(task.due_at);
      const key = due.endOf("day").fromNow();
      if (tasksByDays[key] == undefined) {
        Vue.set(tasksByDays, key, [task]);
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

  @Watch("tasks")
  updatePage(): void {
    this.page = 1;
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
