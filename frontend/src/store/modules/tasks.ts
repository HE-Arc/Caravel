import store from "@/store";
import { Task } from "@/types/task";
import axios, { AxiosResponse } from "axios";
import groupModule from "@/store/modules/groups";
import questionModule from "@/store/modules/questions";
import Vue from "vue";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
} from "vuex-module-decorators";
import { Dictionary, TaskType } from "@/types/helpers";
import moment from "moment";
import TaskExtended from "@/types/TaskExtended";
import GroupStat from "@/types/GroupStat";
import subjectModule from "@/store/modules/subjects";

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "tasks",
  //preserveState:
  //localStorage.getItem(process.env.VUE_APP_VUEX_VERSION_NAME) !== null,
})
class TasksModule extends VuexModule {
  _tasks: Task[] = [];
  _status = "";
  _taskId = "";

  get tasks(): Task[] {
    return this._tasks;
  }

  get selectedId(): string {
    return this._taskId;
  }

  get getCurrentTask(): Task | undefined {
    return this._tasks.find((item) => item.id == this._taskId);
  }

  get tasksFuture(): Task[] {
    return this._tasks.filter((task) =>
      moment(task.due_at).isSameOrAfter(moment())
    );
  }

  get publicTasks(): Task[] {
    return this._tasks.filter((item) => !item.isPrivate);
  }

  get stats(): GroupStat[] | undefined {
    if (this.publicTasks.length == 0) return undefined;

    const min = this.publicTasks.reduce((current, item) =>
      moment(current.due_at).isBefore(moment(item.due_at)) ? current : item
    ).due_at;
    const max = this.publicTasks.reduce((current, item) =>
      moment(current.due_at).isAfter(moment(item.due_at)) ? current : item
    ).due_at;

    const start = moment(min).startOf("isoWeek");
    const finishLine = moment(max);
    const stats: GroupStat[] = [];
    let id = 1;

    while (start.isBefore(finishLine)) {
      const end = moment(start).endOf("isoWeek");
      let sum = 0;

      const tasks = this.publicTasks.filter((task) =>
        moment(task.due_at).isBetween(start, end)
      );

      const projects = this.publicTasks.filter(
        (task) =>
          task.tasktype_id == TaskType.PROJECT.toString() &&
          moment(task.start_at).isBefore(start) &&
          moment(task.due_at).isAfter(end)
      );

      tasks.forEach((task) => {
        const sub = subjectModule.getSubject(task.subject_id);
        const coef = task.tasktype_id == TaskType.PROJECT.toString() ? 2 : 1;
        if (sub) {
          sum += sub.ects * coef;
        }
      });

      projects.forEach((task) => {
        const sub = subjectModule.getSubject(task.subject_id);
        if (sub) {
          sum += sub.ects;
        }
      });

      const wes = sum > 0 ? sum | 0 : 0;

      stats.push({
        id: (id++).toString(),
        create_at: start.toDate(),
        wes: wes,
      });

      start.add(1, "w");
    }

    return stats;
  }

  get minMaxWeekScore(): number[] {
    if (!this.stats) return [0, 0];
    return this.stats.reduce(
      (acc, val) => {
        acc[0] = acc[0] == -1 || val.wes < acc[0] ? val.wes : acc[0];
        acc[1] = acc[1] == -1 || val.wes > acc[1] ? val.wes : acc[1];
        return acc;
      },
      [-1, -1]
    );
  }


  get medianWeekScore(): number {
    //https://stackoverflow.com/questions/45309447/calculating-median-javascript
    if (!this.stats) return 0;
    const values = this.stats.filter(item => moment(item.create_at).isBefore(moment())).map((item) => item.wes).filter((wes) => wes != 0);
    const v = values.sort((a, b) => a - b);
    const mid = Math.floor(v.length / 2);
    const median = v.length % 2 !== 0 ? v[mid] : (v[mid - 1] + v[mid]) / 2;
    return median;
  }

  get currentWeekScore(): number | undefined {
    return this.stats?.find((item) =>
      moment(item.create_at).isSame(moment(), "isoWeek")
    )?.wes;
  }

  get getTask(): (id: string) => Task | undefined {
    return (id: string): Task | undefined =>
      this._tasks.find((item) => item.id == id);
  }

  get projects(): Task[] {
    return this._tasks.filter(
      (item) => item.tasktype_id == TaskType.PROJECT.toString()
    );
  }

  get status(): string {
    return this._status;
  }

  @Mutation
  private ERROR() {
    this._status = "error";
  }

  @Mutation
  private REQUEST() {
    this._status = "loading";
  }

  @Mutation
  private FINISH() {
    this._status = "loaded";
  }

  @Mutation
  private LOAD_TASKS(tasks: Task[]) {
    this._tasks = tasks;
    this._status = "loaded";
  }

  @Mutation
  private UPSERT_TASK(task: Task) {
    const index = this._tasks.findIndex((item) => item.id == task.id);
    if (index === -1) {
      this._tasks.push(task);
    } else {
      Vue.set(this._tasks, index, task);
    }
  }

  @Mutation
  private REMOVE_TASK(task: Task) {
    const index = this._tasks.findIndex((item) => item.id == task.id);
    if (index !== -1) {
      Vue.delete(this._tasks, index);
    }
  }

  @Mutation
  private SET_TASK(task: TaskExtended) {
    this._taskId = task.id;
  }

  @Action
  add(task: Task): Promise<Task> {
    const groupId = groupModule.selectedId;
    return new Promise<Task>((resolve, reject) => {
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks`,
        method: "POST",
        data: task,
      })
        .then((response) => {
          const data: Task = response.data;
          this.UPSERT_TASK(data);
          resolve(data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }

  @Action
  update(task: Task): Promise<Task> {
    const groupId = groupModule.selectedId;
    return new Promise<Task>((resolve, reject) => {
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/tasks/${task.id}`,
        method: "PATCH",
        data: task,
      })
        .then((response) => {
          const data: Task = response.data;
          this.UPSERT_TASK(data);
          resolve(data);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }

  @Action
  async setFinish(data: Dictionary<unknown>) {
    const groupId = groupModule.selectedId;
    await axios.patch(
      process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/finished`,
      data
    );
  }

  @Action
  delete(task: Task): Promise<AxiosResponse> {
    const groupId = groupModule.selectedId;
    return new Promise<AxiosResponse>((resolve, reject) => {
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/tasks/${task.id}`,
        method: "DELETE",
      })
        .then((response) => {
          this.REMOVE_TASK(task);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  load(tasks: Task[]) {
    this.LOAD_TASKS(tasks);
  }

  @Action
  async save(task: Task): Promise<Task> {
    if (task.id == "" || task.id == "-1") {
      return this.add(task);
    } else {
      return this.update(task);
    }
  }

  @Action
  async postReaction({
    taskId,
    type,
  }: {
    taskId: string;
    type: number;
  }): Promise<void> {
    const groupId = groupModule.selectedId;
    const response = await axios.patch(
      process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/reactions`,
      {
        task_id: taskId,
        type: type,
      }
    );

    const task: Task = response.data;
    this.UPSERT_TASK(task);
  }

  //Actions
  @Action
  loadTask(id: string): Promise<AxiosResponse> {
    const groupId = groupModule.selectedId;
    this.REQUEST();
    return new Promise((resolve, reject) => {
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/tasks/${id}`,
        method: "GET",
      })
        .then((response) => {
          const task: TaskExtended = response.data;
          questionModule.load(task.questions);
          this.SET_TASK(task);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        })
        .finally(() => {
          this.FINISH();
        });
    });
  }

  @Action
  async selectTask(taskId: string): Promise<AxiosResponse> {
    return await this.loadTask(taskId);
  }
}

const instance = getModule(TasksModule);

export default instance;
