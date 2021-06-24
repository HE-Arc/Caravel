// test/setup.js
import registerRequireContextHook from "babel-plugin-require-context-hook/register";
registerRequireContextHook();
import Vue from "vue";
import Vuetify from "vuetify";

Vue.use(Vuetify);
