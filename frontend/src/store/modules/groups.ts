import { Group } from "@/types/group";
import TaskModule from "@/store/modules/tasks";
import SubjectModule from "@/store/modules/subjects";
import MemberModule from "@/store/modules/members";
import { GroupExtended } from "@/types/groupExtended";
import store from "@/store";
import axios, { AxiosResponse } from "axios";
import Vue from "vue";
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
  //preserveState:
  //localStorage.getItem(process.env.VUE_APP_VUEX_VERSION_NAME) !== null,
})
class GroupModule extends VuexModule {
  _groups: Group[] = [];
  _status = "";
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

  //Mutation
  @Mutation
  private REQUEST() {
    this._status = "loading";
  }

  @Mutation
  protected LOAD_GROUPS(items: Group[]): void {
    this._groups = items;
    this._status = "loaded";
  }

  @Mutation
  protected UPSERT_GROUP(data: Group): void {
    const index = this._groups.findIndex((item) => item.id == data.id);
    if (index === -1) {
      this._groups.push(data);
      this._status = "added";
    } else {
      //see : https://gist.github.com/DawidMyslak/2b046cca5959427e8fb5c1da45ef7748
      Vue.set(this._groups, index, data);
      this._status = "modified";
    }
  }

  @Mutation
  protected REMOVE_GROUP(groupId: string): void {
    const index = this._groups.findIndex((item) => item.id == groupId);
    console.log(index);
    if (index !== -1) {
      Vue.delete(this._groups, index);
      this._status = "delete";
    }
  }

  @Mutation
  private SET_GROUP(groupId: string) {
    this._status = "selected";
    this._groupId = groupId;
  }

  @Mutation
  private ERROR() {
    this._status = "error";
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
          const group: GroupExtended = response.data;
          TaskModule.load(group.tasks);
          SubjectModule.load(group.subjects);
          MemberModule.load(group.members);
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
        url: process.env.VUE_APP_API_BASE_URL + "groups",
        method: "GET",
      })
        .then((response) => {
          const groups: Group[] = response.data;
          this.LOAD_GROUPS(groups);
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
      this.ERROR();
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
          user_id: group.user_id,
        },
      })
        .then((resp) => {
          const group: Group = resp.data;
          this.UPSERT_GROUP(group);
          resolve(resp);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }

  @Action
  leave(group: Group): Promise<AxiosResponse> {
    this.REQUEST();
    return new Promise((resolve, reject) => {
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `profile`,
        method: "DELETE",
        data: {
          group_id: group.id,
        },
      })
        .then((resp) => {
          this.REMOVE_GROUP(group.id);
          resolve(resp);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }

  @Action
  add(formData: FormData): Promise<Group> {
    return new Promise((resolve, reject) => {
      axios
        .post(process.env.VUE_APP_API_BASE_URL + "groups", formData)
        .then((resp) => {
          const group: Group = resp.data;
          this.UPSERT_GROUP(group);
          resolve(group);
        })
        .catch((err) => {
          reject(err);
        });
    });
  }

  @Action
  removeGroup(groupId: string): Promise<void> {
    return new Promise((resolve, reject) => {
      axios
        .delete(process.env.VUE_APP_API_BASE_URL + "groups/" + groupId)
        .then(() => {
          this.REMOVE_GROUP(groupId);
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  }
}

const instance = getModule(GroupModule);

export default instance;
