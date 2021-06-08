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

interface BagSuccess {
  token: string;
  user: User;
}

interface Credentials {
  mail: string;
  password: string;
}

@Module({
  namespaced: true,
  dynamic: true,
  store,
  name: "auth",
  preserveState: localStorage.getItem("vuex") !== null,
})
class AuthModule extends VuexModule {
  _user: User | undefined = undefined;
  _status = "";
  _token = "";

  get isLoggedIn(): boolean {
    return !!this._token;
  }

  get user(): User | undefined {
    return this._user;
  }

  get token(): string {
    return this._token;
  }

  get status(): string {
    return this._status;
  }

  // MUTATION
  @Mutation
  private REQUEST() {
    this._status = "loading";
  }

  @Mutation
  private SUCCESS({ token, user }: BagSuccess) {
    this._status = "success";
    this._token = token;
    this._user = user;
  }

  @Mutation
  private ERROR() {
    this._status = "error";
  }

  @Mutation
  private DISCONNECT() {
    this._status = "";
    this._user = undefined;
    this._token = "";
  }

  @MutationAction({ mutate: ["_user"] })
  async update(user: User) {
    //api call update
    return { _user: user };
  }

  //ACTIONS
  @Action
  login({ mail, password }: Credentials): Promise<AxiosResponse> {
    this.REQUEST();
    return new Promise<AxiosResponse>((resolve, reject) => {
      axios({
        url: process.env.VUE_APP_API_BASE_URL + "login",
        data: { mail, password },
        method: "POST",
      })
        .then((resp) => {
          const token = resp.data.token;
          const user: User = resp.data.user;

          this.SUCCESS({ token, user });
          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          resolve(resp);
        })
        .catch((err) => {
          this.ERROR();
          localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
          reject(err);
        });
    });
  }

  @Action
  logout(): Promise<void> {
    return new Promise<void>((resolve) => {
      this.DISCONNECT();
      localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
      delete axios.defaults.headers.common["Authorization"];
      resolve();
    });
  }
}

const instance = getModule(AuthModule);

export default instance;
