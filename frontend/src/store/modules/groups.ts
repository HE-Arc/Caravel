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
  protected REQUEST() {
    this._status = "loading";
  }

  //Mutation
  @Mutation
  protected LOADED() {
    this._status = "loaded";
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
    } else {
      Vue.set(this._groups, index, data); //reactive data
    }
  }

  @Mutation
  protected REMOVE_GROUP(groupId: string): void {
    const index = this._groups.findIndex((item) => item.id == groupId);
    if (index !== -1) {
      Vue.delete(this._groups, index); //reactive data
    }
  }

  @Mutation
  protected SET_GROUP(groupId: string) {
    this._groupId = groupId;
  }

  @Mutation
  protected ERROR() {
    this._status = "error";
  }

  /**
   * This function set and load a group
   * @param id the group's id
   * @returns the response sended by the backend
   */
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
          this.LOADED();
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  /**
   * This function allow to load all groups for the logged user
   * @returns API's response 
   */
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
          reject(err);
        });
    });
  }

  /**
   * Select the current group
   * @param groupId 
   */
  @Action
  async selectGroup(groupId: string): Promise<void> {
    this.SET_GROUP(groupId);
    await this.loadGroup(groupId);
  }

  /**
   * Update the given group via API call
   * if the call is a success then update group internally
   * @param group 
   * @returns API's Response
   */
  @Action
  updateGroup(group: Group): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      this.REQUEST();
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
        })
        .finally(() => this.LOADED());
    });
  }

  /**
   * Leave the given group for the current logged user
   * @param group 
   * @returns API's Response
   */
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
        })
        .finally(() => {
          this.LOADED();
        });
    });
  }

  /**
   * Add a group
   * @param formData 
   * @returns the newly created group
   */
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

  /**
   * Delete group
   * @param groupId the group's id to delete
   * @returns void promise
   */
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

  /**
   * Upload a file to a group
   * @param file the file to upload
   * @returns File's full path 
   */
  @Action
  async uploadFile(file: File): Promise<string> {
    const form = new FormData();
    form.append("file", file);
    try {
      const file = await axios.post(
        process.env.VUE_APP_API_BASE_URL + `groups/${this.selectedId}/files`,
        form,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      return file.data;
    } catch (err) {
      return "";
    }
  }
}

const instance = getModule(GroupModule);

export default instance;
