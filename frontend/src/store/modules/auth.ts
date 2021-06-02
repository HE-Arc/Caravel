// src/store/modules/auth.ts
import axios, { AxiosResponse } from "axios";
import { Module, GetterTree, MutationTree, ActionTree } from "vuex";

export enum AuthMutations {
  REQUEST = "auth_request",
  SUCCESS = "auth_success",
  ERROR = "auth_error",
  LOGOUT = "logout",
}

export enum AuthActions {
  LOGIN = "login",
  LOGOUT = "logout",
}

interface BagSuccess {
  token: string;
  user: Types.User;
}

const state: Types.AuthState = {
  user: undefined,
  status: "",
  token: localStorage.getItem(process.env.VUE_APP_TOKEN_NAME) || "",
};

const getters: GetterTree<Types.AuthState, Types.RootState> = {
  authUser: (state: Types.AuthState): Types.User | undefined => state.user,
  isLoggedIn: (state: Types.AuthState): boolean => !!state.token,
  authStatus: (state: Types.AuthState): string => state.status,
};

const mutations: MutationTree<Types.AuthState> = {
  [AuthMutations.REQUEST](state) {
    state.status = "loading";
  },
  [AuthMutations.SUCCESS](state, { token, user }: BagSuccess) {
    state.status = "success";
    state.token = token;
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

const actions: ActionTree<Types.AuthState, Types.RootState> = {
  [AuthActions.LOGIN](
    { commit },
    data: Types.Credentials
  ): Promise<AxiosResponse> {
    return new Promise((resolve, reject) => {
      commit("auth_request");
      axios({
        url: process.env.VUE_APP_API_BASE_URL + "login",
        data: data,
        method: "POST",
      })
        .then((resp) => {
          const token = resp.data.token;
          const user: Types.User = resp.data.user;
          localStorage.setItem(process.env.VUE_APP_TOKEN_NAME, token);
          axios.defaults.headers.common["Authorization"] = token;
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
  [AuthActions.LOGOUT]({ commit }): Promise<void> {
    return new Promise<void>((resolve) => {
      commit("logout");
      localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
      delete axios.defaults.headers.common["Authorization"];
      resolve();
    });
  },
};

export const auth: Module<Types.AuthState, Types.RootState> = {
  state,
  getters,
  mutations,
  actions,
};
