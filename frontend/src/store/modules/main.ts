import { Group } from "@/types/group";
import { Task } from "@/types/task";
import store from "@/store";
import axios, { AxiosResponse } from "axios";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
} from "vuex-module-decorators";

interface Bag {
  group: Group;
  tasks: Task[];
}

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "mainStore",
  preserveState: localStorage.getItem("vuex") !== null,
})
class MainModule extends VuexModule {
  private _group: Group | undefined = undefined;
  private _status = "";
  private _tasks: Task[] = [];
  private _groupId = -1;

  get selectedId(): number {
    return this._groupId;
  }

  get group(): Group | undefined {
    return this._group;
  }

  get status(): string {
    return this._status;
  }

  get tasks(): Task[] {
    return this._tasks;
  }

  //Mutation
  @Mutation
  private REQUEST() {
    this._status = "loading";
  }

  @Mutation
  private ERROR() {
    this._status = "error";
  }

  @Mutation
  private SUCCESS({ group, tasks }: Bag) {
    this._status = "success";
    this._group = group;
    this._tasks = tasks;
  }

  //Actions
  @Action
  loadGroup(id: string): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${id}`,
        method: "GET",
      })
        .then((response) => {
          const group: Group = response.data;
          const tasks: Task[] = [];
          this.SUCCESS({ group, tasks });
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }
}

const instance = getModule(MainModule);

export default instance;
