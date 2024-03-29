import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import Axios, { AxiosError } from "axios";
import vuetify from "./plugins/vuetify";
import i18n from "./i18n";
import VueToast from "vue-toast-notification";
import "mavon-editor/dist/css/index.css";
import VueTimeago from "vue-timeago";
import moment from "moment";
import "moment/locale/fr";
import "@/filters";
import firebase from "@/fcm";
import NProgress from "vue-nprogress";
import { NavigationGuardNext, Route } from "vue-router";
import userModule from "@/store/modules/user";

Axios.defaults.withCredentials = true;

Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

Vue.use(VueToast, {
  position: "top-right",
});

Vue.use(NProgress);

Vue.mixin({
  computed: {
    $messaging: () => firebase.messaging(),
  },
});

moment.locale("fr");

Vue.use(VueTimeago, {
  name: "Timeago", // Component name, `Timeago` by default
  locale: "fr", // Default locale
  locales: {
    fr: require("date-fns/locale/fr"),
    en: require("date-fns/locale/en"),
  },
});

const nprogress = new NProgress();

router.beforeEach((to: Route, from: Route, next: NavigationGuardNext) => {
  if (to.path) {
    nprogress.start();
  }
  next();
});

router.afterEach(() => {
  nprogress.done();
});

Axios.interceptors.response.use(
  (reponse) => reponse,
  (error: AxiosError) => {
    switch (error.response?.status) {
      case 401:
        if (router.currentRoute.name != "Login") {
          userModule.logout();
          router.push({
            name: "Login",
            query: { redirect: router.currentRoute.fullPath },
          });
        }
        break;
      case 403:
        if (router.currentRoute.name != "Forbidden")
          router.replace({ name: "Forbidden" });
        break;
    }
    return Promise.reject(error);
  }
);

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
