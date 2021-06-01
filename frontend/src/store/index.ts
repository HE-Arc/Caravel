import Vue from "vue";
import Vuex, { StoreOptions } from "vuex";
import { auth } from "@/store/modules/auth";

Vue.use(Vuex);

const store: StoreOptions<Types.RootState> = {
  state: {
    helloMessage: "test",
  },
  modules: {
    auth,
  },
};

export default new Vuex.Store<Types.RootState>(store);
