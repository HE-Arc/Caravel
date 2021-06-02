import Vue from "vue";
import Vuex, { StoreOptions } from "vuex";
import { auth } from "@/store/modules/auth";
import { RootState } from "@/types/RootState";

Vue.use(Vuex);

const store: StoreOptions<RootState> = {
  state: {
    version: "1.0.0",
  },
  modules: {
    auth,
  },
};

export default new Vuex.Store<RootState>(store);
