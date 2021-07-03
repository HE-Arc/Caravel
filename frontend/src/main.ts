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
import "@/filters";
import messaging from "../firebase";
import NProgress from "vue-nprogress";
import { NavigationGuardNext, Route } from "vue-router";

Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

Vue.use(VueToast, {
  position: "top-right",
});

Vue.use(NProgress);

Vue.use(mavonEditor, {
  language: "fr",
});

Vue.mixin({
  computed: {
    $messaging: () => messaging.messaging(),
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

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
