import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import Axios from "axios";
import vuetify from "./plugins/vuetify";
import i18n from "./i18n";
import VueToast from "vue-toast-notification";
import mavonEditor from "mavon-editor";
import "mavon-editor/dist/css/index.css";
import VueTimeago from "vue-timeago";
import moment from "moment";

Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

Vue.use(VueToast, {
  position: "top-right",
});

Vue.use(mavonEditor, {
  language: "fr",
});

moment.locale("fr");

Vue.use(VueTimeago, {
  name: "Timeago", // Component name, `Timeago` by default
  locale: "fr", // Default locale
  // We use `date-fns` under the hood
  // So you can use all locales from it
  locales: {
    fr: require("date-fns/locale/fr"),
    en: require("date-fns/locale/en"),
  },
});

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
