// src/store/modules/auth.ts
import axios from "axios";
import { Commit } from "vuex";

interface AuthState {
  user: Types.User | null;
  status: string;
  token: string;
}

const state: AuthState = {
  user: null,
  status: "",
  token: localStorage.getItem(process.env.VUE_APP_TOKEN_NAME) || "",
};

// getters

const getters = {
  authUser: (state: AuthState) => state.user,
  isLoggedIn: (state: AuthState) => !!state.token,
  authStatus: (state: AuthState) => state.status,
};

const mutations = {
  auth_request(state: AuthState) {
    state.status = "loading";
  },
  auth_success(state: AuthState, token: string, user: Types.User) {
    state.status = "success";
    state.token = token;
    state.user = user;
  },
  auth_error(state: AuthState) {
    state.status = "error";
  },
  logout(state: AuthState) {
    state.status = "";
    state.token = "";
  },
};

// actions
const actions = {
  login({ commit }: { commit: Commit }, user: any) {
    return new Promise((resolve, reject) => {
      commit("auth_request");
      axios({
        url: process.env.VUE_APP_API_BASE_URL + "login",
        data: user,
        method: "POST",
      })
        .then((resp) => {
          const token = resp.data.token;
          const user: Types.User | any = <Types.User>resp.data.user;
          console.log(user);
          localStorage.setItem(process.env.VUE_APP_TOKEN_NAME, token);
          axios.defaults.headers.common["Authorization"] = token;
          commit("auth_success", token, user);
          resolve(resp);
        })
        .catch((err) => {
          commit("auth_error");
          localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
          reject(err);
        });
    });
  },
  logout({ commit }: { commit: Commit }) {
    return new Promise<void>((resolve, reject) => {
      commit("logout");
      localStorage.removeItem(process.env.VUE_APP_TOKEN_NAME);
      delete axios.defaults.headers.common["Authorization"];
      resolve();
    });
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};
