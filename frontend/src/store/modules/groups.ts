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

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "groups",
  preserveState: localStorage.getItem("vuex") !== null,
})
class GroupModule extends VuexModule {
  _groups: Group[] = [];
  _status = "";
  _tasks: Task[] = [];
  _groupId = "";

  get selectedId(): string {
    return this._groupId;
  }

  get groups(): Group[] {
    return this._groups;
  }

  get group(): Group | undefined {
    return this._groups.find((group) => group.id == this._groupId);
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
  private SET_GROUP(groupId: string) {
    this._status = "group_selected";
    this._groupId = groupId;
    this._tasks = [];
  }

  @Mutation
  private GROUPS(groups: Group[]) {
    this._status = "groups_loaded";
    this._groups = groups;
  }

  @Mutation
  private TASKS(tasks: Task[]) {
    this._status = "tasks_loaded";
    this._tasks = tasks;
  }

  @Mutation
  private ERROR() {
    this._status = "error";
  }

  @Mutation
  private ERROR_SELECT(groupId: string) {
    this._status = `Group ${groupId} can't be selected`;
    this._groupId = "";
    this._tasks = [];
  }

  //Actions
  @Action
  loadGroup(id: string): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${id}/tasks`,
        method: "GET",
      })
        .then((response) => {
          const tasks: Task[] = response.data;
          this.TASKS(tasks);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  loadGroups(): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups`,
        method: "GET",
      })
        .then((response) => {
          const groups: Group[] = response.data;
          this.GROUPS(groups);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  async selectGroup(groupId: string): Promise<void> {
    try {
      this.SET_GROUP(groupId);
      await this.loadGroup(groupId);
    } catch {
      this.ERROR_SELECT(groupId);
    }
  }

  @Action
  updateGroup(group: Group): Promise<AxiosResponse> {
    this.REQUEST();
    return new Promise((resolve, reject) => {
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${group.id}`,
        method: "PATCH",
        data: {
          id: group.id,
          name: group.name,
          description: group.description,
        },
      })
        .then((resp) => {
          this.loadGroups();
          resolve(resp);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }
}

const instance = getModule(GroupModule);

instance.loadGroups();

export default instance;
