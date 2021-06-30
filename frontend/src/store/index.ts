import Vue from "vue";
import Vuex from "vuex";
import VuexPersist from "vuex-persist";
import { config } from "vuex-module-decorators";
// Set rawError to true by default on all @Action decorators
config.rawError = true;

Vue.use(Vuex);

const vuexPersist = new VuexPersist({
  key: process.env.VUE_APP_VUEX_VERSION_NAME,
  storage: window.localStorage,
});

export default new Vuex.Store({
  state: {},
  modules: {},
  plugins: [vuexPersist.plugin],
});
