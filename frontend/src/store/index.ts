import Vue from "vue";
import Vuex, { StoreOptions } from "vuex";
import { auth } from "@/store/modules/auth";
import { RootState } from "@/types/RootState";
import VuexPersist from "vuex-persist";

Vue.use(Vuex);

const vuexPersist = new VuexPersist({
  key: process.env.VUE_APP_NAME,
  storage: window.localStorage,
});

const store: StoreOptions<RootState> = {
  state: {
    version: "1.0.0",
  },
  modules: {
    auth,
  },
  plugins: [vuexPersist.plugin],
};

export default new Vuex.Store<RootState>(store);
