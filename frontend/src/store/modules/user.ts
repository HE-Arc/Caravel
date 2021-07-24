// src/store/modules/auth.ts
import { User } from "@/types/user";
import axios, { AxiosResponse } from "axios";
import {
  VuexModule,
  Module,
  Mutation,
  Action,
  getModule,
  MutationAction,
} from "vuex-module-decorators";
import store from "@/store";
import Notification from "@/types/notification";
import Vue from "vue";

interface Credentials {
  mail: string;
  password: string;
}

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "auth",
  preserveState:
    localStorage.getItem(process.env.VUE_APP_VUEX_VERSION_NAME) !== null,
})
class UserModule extends VuexModule {
  _user: User | undefined = undefined;
  _status = "";
  _fcm_token = "";

  get isLoggedIn(): boolean {
    return this._status == "success";
  }

  get isTeacher(): boolean {
    return this._user ? this._user?.isTeacher : false;
  }

  get user(): User | undefined {
    return this._user;
  }

  get status(): string {
    return this._status;
  }

  get notifications(): Notification[] {
    return this._user ? this._user.notifications : [];
  }

  // MUTATION
  @Mutation
  REQUEST() {
    this._status = "loading";
  }

  @Mutation
  SUCCESS(user: User) {
    this._user = user;
    this._status = "success";
  }

  @Mutation
  ERROR() {
    this._status = "error";
  }

  @Mutation
  DISCONNECT() {
    this._status = "";
    this._user = undefined;
  }

  @Mutation
  UPDATE_FCM(token: string) {
    this._fcm_token = token;
  }

  @Mutation
  UPDATE_NOTIFS(notifs: Notification[]) {
    if (!this._user) return;
    Vue.set(this._user, "notifications", notifs);
  }

  @MutationAction({ mutate: ["_user"] })
  async update(user: User) {
    //api call update
    return { _user: user };
  }

  @Action
  async addFcmToken(fcm: string) {
    if (!fcm || fcm == "") return;
    try {
      await axios({
        url: process.env.VUE_APP_API_BASE_URL + "profile/fcmToken",
        data: { fcm },
        method: "POST",
      });
      this.UPDATE_FCM(fcm);
    } catch (err) {
      console.log(err);
    }
  }

  @Action
  async removeFcmToken() {
    if (this._fcm_token) {
      try {
        await axios({
          url: process.env.VUE_APP_API_BASE_URL + "profile/fcmToken",
          data: { fcm: this._fcm_token },
          method: "DELETE",
        });
        this.UPDATE_FCM("");
      } catch (err) {
        console.log(err);
      }
    }
  }

  //ACTIONS
  /**
   * This function allow to login into the Laravel backend Sanctum
   * @param data Array<string>
   */
  @Action
  async login({ mail, password }: Credentials): Promise<void> {
    this.REQUEST();

    await axios.get(process.env.VUE_APP_API_BASE_URL + "sanctum/csrf-cookie");

    const response: AxiosResponse = await axios({
      url: process.env.VUE_APP_API_BASE_URL + "login",
      data: { mail, password },
      method: "POST",
    });

    const user: User = response.data.user;

    this.SUCCESS(user);
  }

  @Action
  async logout(): Promise<void> {
    await this.removeFcmToken();
    this.DISCONNECT();
    localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
  }

  @Action
  async loadNotifications(): Promise<void> {
    if (!this.isLoggedIn) return;

    const resp = await axios({
      url: process.env.VUE_APP_API_BASE_URL + "profile/notifications",
    });

    const notifs: Notification[] = resp.data;

    this.UPDATE_NOTIFS(notifs);
  }

  @Action
  async markAsRead(notif: Notification[]): Promise<void> {
    if (!this.isLoggedIn) return;

    const listIds = notif.map((item) => item.id);

    await axios({
      url: process.env.VUE_APP_API_BASE_URL + "profile/markAsRead",
      method: "POST",
      data: {
        notifs: listIds,
      },
    });
    await this.loadNotifications();
  }
}

const instance = getModule(UserModule);

export default instance;
