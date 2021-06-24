import store from "@/store";
import { Subject } from "@/types/subject";
import axios, { AxiosResponse } from "axios";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
} from "vuex-module-decorators";
import Vue from "vue";
import groupModule from "@/store/modules/groups";

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "subjects",
  preserveState: localStorage.getItem("vuex") !== null,
})
class SubjectModule extends VuexModule {
  _subjects: Subject[] = [];
  _status = "";

  get subjects(): Subject[] {
    return this._subjects;
  }

  get getSubject() {
    return (id: string): Subject | undefined =>
      this._subjects.find((item) => item.id == id);
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
  private LOAD_SUBJECTS(subjects: Subject[]) {
    this._subjects = subjects;
    this._status = "loaded";
  }

  @Mutation
  private UPSERT_SUBJECT(subject: Subject) {
    const index = this._subjects.findIndex((item) => item.id == subject.id);
    if (index === -1) {
      this._subjects.push(subject);
      this._status = "added";
    } else {
      Vue.set(this._subjects, index, subject);
      this._status = "modified";
    }
  }

  @Mutation
  private REMOVE_SUBJECT(subject: Subject) {
    const index = this._subjects.findIndex((item) => item.id == subject.id);
    if (index !== -1) {
      Vue.delete(this._subjects, index);
      this._status = "delete";
    }
  }

  @Action
  add(subject: Subject): Promise<Subject> {
    const groupId = groupModule.selectedId;
    return new Promise<Subject>((resolve, reject) => {
      this.REQUEST();
      axios({
        url: process.env.VUE_APP_API_BASE_URL + `groups/${groupId}/subjects`,
        method: "POST",
        data: subject,
      })
        .then((response) => {
          const data: Subject = response.data;
          this.UPSERT_SUBJECT(data);
          resolve(data);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  update(subject: Subject): Promise<Subject> {
    const groupId = groupModule.selectedId;
    return new Promise<Subject>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/subjects/${subject.id}`,
        method: "PATCH",
        data: subject,
      })
        .then((response) => {
          const data: Subject = response.data;
          this.UPSERT_SUBJECT(data);
          resolve(data);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  delete(subject: Subject): Promise<AxiosResponse> {
    const groupId = groupModule.selectedId;
    return new Promise<AxiosResponse>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/subjects/${subject.id}`,
        method: "DELETE",
      })
        .then((response) => {
          this.REMOVE_SUBJECT(subject);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  load(subjects: Subject[]) {
    this.LOAD_SUBJECTS(subjects);
  }

  @Action
  async save(subject: Subject): Promise<Subject> {
    if (subject.id == "" || subject.id == "-1") {
      return this.add(subject);
    } else {
      return this.update(subject);
    }
  }
}

const instance = getModule(SubjectModule);

export default instance;
