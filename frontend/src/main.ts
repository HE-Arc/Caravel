import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import Axios from "axios";
import vuetify from "./plugins/vuetify";
import i18n from "./i18n";
import VueToast from "vue-toast-notification";

Vue.prototype.$http = Axios;

Vue.config.productionTip = false;

Vue.use(VueToast, {
  position: "top-right",
});

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
