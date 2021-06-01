// src/store/modules/auth.ts
import axios from "axios";
import { Module, GetterTree, MutationTree, ActionTree } from "vuex";

interface bag {
  token: Types.RootState;
  user: Types.User;
}

const state: Types.AuthState = {
  user: null,
  status: "",
  token: localStorage.getItem(process.env.VUE_APP_TOKEN_NAME) || "",
};

const getters: GetterTree<Types.AuthState, Types.RootState> = {
  authUser: (state: Types.AuthState): Types.User | null => state.user,
  isLoggedIn: (state: Types.AuthState): Boolean => !!state.token,
  authStatus: (state: Types.AuthState): String => state.status,
};

const mutations: MutationTree<Types.AuthState> = {
  auth_request(state){
    state.status = "loading";
  },
  auth_success(state, {token, user} ) {
    state.status = "success";
    state.token = token;
    state.user = user;
  },
  auth_error(state) {
    state.status = "error";
  },
  logout(state) {
    state.status = "";
    state.user = null;
    state.token = "";
  }

};

const actions: ActionTree<Types.AuthState, Types.RootState> = {

}

export const auth: Module<Types.AuthState, Types.RootState> = {
  state,
  getters,
  mutations
}