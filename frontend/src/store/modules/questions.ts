import store from "@/store";
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
import Question from "@/types/Question";
import Comment from "@/types/Comment";
import CommentForm from "@/types/CommentForm";
import QuestionForm from "@/types/QuestionForm";

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "questions",
  //preserveState:
  //localStorage.getItem(process.env.VUE_APP_VUEX_VERSION_NAME) !== null,
})
class QuestionModule extends VuexModule {
  _questions: Question[] = [];
  _status = "";

  get questions(): Question[] {
    return this._questions;
  }

  get getQuestion() {
    return (id: string): Question | undefined =>
      this._questions.find((item) => item.id == id);
  }

  get status(): string {
    return this._status;
  }

  @Mutation
  ERROR() {
    this._status = "error";
  }

  @Mutation
  REQUEST() {
    this._status = "loading";
  }

  @Mutation
  LOAD_QUESTIONS(questions: Question[]) {
    this._questions = questions;
    this._status = "loaded";
  }

  @Mutation
  UPSERT_QUESTION(question: Question) {
    const index = this._questions.findIndex((item) => item.id == question.id);
    if (index === -1) {
      this._questions.push(question);
      this._status = "added";
    } else {
      Vue.set(this._questions, index, question);
      this._status = "modified";
    }
  }

  @Mutation
  REMOVE_QUESTION(question: Question) {
    const index = this._questions.findIndex((item) => item.id == question.id);
    if (index !== -1) {
      Vue.delete(this._questions, index);
      this._status = "delete";
    }
  }

  @Action
  upsertQuestion(question: QuestionForm): Promise<Question> {
    const groupId = groupModule.selectedId;
    const method = question.id ? "PATCH" : "POST";
    const suffix = question.id ? `/${question.id}` : "";
    return new Promise<Question>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/questions` +
          suffix,
        method: method,
        data: question,
      })
        .then((response) => {
          const data: Question = response.data;
          this.UPSERT_QUESTION(data);
          resolve(data);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  upsertComment(comment: CommentForm): Promise<Question> {
    const groupId = groupModule.selectedId;
    const method = comment.id ? "PATCH" : "POST";
    const suffix = comment.id ? `/${comment.id}` : "";
    return new Promise<Question>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/comments` +
          suffix,
        method: method,
        data: comment,
      })
        .then((response) => {
          const data: Question = response.data;
          this.UPSERT_QUESTION(data);
          resolve(data);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  deleteComment(comment: Comment): Promise<Question> {
    const groupId = groupModule.selectedId;
    return new Promise<Question>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/comments/${comment.id}`,
        method: "DELETE",
      })
        .then((response) => {
          const data: Question = response.data;
          this.UPSERT_QUESTION(data);
          resolve(data);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  deleteQuestion(question: Question): Promise<AxiosResponse> {
    const groupId = groupModule.selectedId;
    return new Promise<AxiosResponse>((resolve, reject) => {
      this.REQUEST();
      axios({
        url:
          process.env.VUE_APP_API_BASE_URL +
          `groups/${groupId}/questions/${question.id}`,
        method: "DELETE",
      })
        .then((response) => {
          this.REMOVE_QUESTION(question);
          resolve(response);
        })
        .catch((err) => {
          this.ERROR();
          reject(err);
        });
    });
  }

  @Action
  load(questions: Question[]) {
    this.LOAD_QUESTIONS(questions);
  }
}

const instance = getModule(QuestionModule);

export default instance;
