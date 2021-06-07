// src/store/modules/auth.ts
import { AuthState } from "@/types/authstate";
import { Credentials } from "@/types/helpers";
import { RootState } from "@/types/RootState";
import { User } from "@/types/user";
import axios, { AxiosResponse } from "axios";
import { Module, GetterTree, MutationTree, ActionTree } from "vuex";

export enum AuthMutations {
  REQUEST = "auth_request",
  SUCCESS = "auth_success",
  ERROR = "auth_error",
  LOGOUT = "logout",
  UPDATE = "auth_update",
}

export enum AuthActions {
  LOGIN = "login",
  LOGOUT = "logout",
  UPDATE = "update",
}

interface BagSuccess {
  token: string;
  user: User;
}

const state: AuthState = {
  user: undefined,
  status: "",
  token: "",
};

const getters: GetterTree<AuthState, RootState> = {
  authUser: (state: AuthState): User | undefined => state.user,
  isLoggedIn: (state: AuthState): boolean => !!state.token,
  authStatus: (state: AuthState): string => state.status,
  authToken: (state: AuthState): string => state.token,
};

const mutations: MutationTree<AuthState> = {
  [AuthMutations.REQUEST](state) {
    state.status = "loading";
  },
  [AuthMutations.SUCCESS](state, { token, user }: BagSuccess) {
    state.status = "success";
    state.token = token;
    state.user = user;
  },
  [AuthMutations.UPDATE](state, user: User) {
    state.status = "updated user";
    state.user = user;
  },
  [AuthMutations.ERROR](state) {
    state.status = "error";
  },
  [AuthMutations.LOGOUT](state) {
    state.status = "";
    state.user = undefined;
    state.token = "";
  },
};

const actions: ActionTree<AuthState, RootState> = {
  [AuthActions.LOGIN]({ commit }, data: Credentials): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      commit("auth_request");
      axios({
        url: process.env.VUE_APP_API_BASE_URL + "login",
        data: data,
        method: "POST",
      })
        .then((resp) => {
          const token = resp.data.token;
          const user: User = resp.data.user;
          localStorage.setItem(process.env.VUE_APP_TOKEN_NAME, token);
          axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
          localStorage.setItem(
            process.env.VUE_APP_TOKEN_NAME,
            JSON.stringify(user)
          );
          commit("auth_success", { token, user });
          resolve(resp);
        })
        .catch((err) => {
          commit("auth_error");
          localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
          reject(err);
        });
    });
  },
  [AuthActions.UPDATE]({ commit }, user: User): Promise<void> {
    return new Promise<void>((resolve) => {
      commit(AuthMutations.UPDATE, user);
    });
  },
  [AuthActions.LOGOUT]({ commit }): Promise<void> {
    return new Promise<void>((resolve) => {
      commit(AuthMutations.LOGOUT);
      localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
      delete axios.defaults.headers.common["Authorization"];
      resolve();
    });
  },
};

export const auth: Module<AuthState, RootState> = {
  state,
  getters,
  mutations,
  actions,
};
