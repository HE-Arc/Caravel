import store from "@/store";
import { Task } from "@/types/task";
import axios, { AxiosResponse } from "axios";
import groupModule from "@/store/modules/groups";
import Vue from "vue";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
} from "vuex-module-decorators";
import { TaskType } from "@/types/helpers";

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "tasks",
  preserveState: localStorage.getItem("vuex") !== null,
})
class TasksModule extends VuexModule {
  _tasks: Task[] = [];
  _status = "";

  get tasks(): Task[] {
    return this._tasks;
  }

  get getTask() {
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
  private LOAD_TASKS(tasks: Task[]) {
    this._tasks = tasks;
    this._status = "loaded";
  }

  @Mutation
  private UPSERT_TASK(task: Task) {
    const index = this._tasks.findIndex((item) => item.id == task.id);
    if (index === -1) {
      this._tasks.push(task);
      this._status = "added";
    } else {
      Vue.set(this._tasks, index, task);
      this._status = "modified";
    }
  }

  @Mutation
  private REMOVE_TASK(task: Task) {
    const index = this._tasks.findIndex((item) => item.id == task.id);
    if (index !== -1) {
      Vue.delete(this._tasks, index);
      this._status = "delete";
    }
  }

  @Action
  add(task: Task): Promise<Task> {
    const groupId = groupModule.selectedId;
    this.REQUEST();
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
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  update(task: Task): Promise<Task> {
    const groupId = groupModule.selectedId;
    return new Promise<Task>((resolve, reject) => {
      this.REQUEST();
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
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  delete(task: Task): Promise<AxiosResponse> {
    const groupId = groupModule.selectedId;
    this.REQUEST();
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
}

const instance = getModule(TasksModule);

export default instance;
