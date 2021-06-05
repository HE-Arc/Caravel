import Vue from "vue";
import VueRouter, { NavigationGuardNext, Route, RouteConfig } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import GroupsSearch from "../views/GroupSearch.vue";
import store from "../store";

Vue.use(VueRouter);

const ifAuthenticated = (to: Route, from: Route, next: NavigationGuardNext) => {
  if (store.getters.isLoggedIn) {
    next();
    return;
  }
  next("/login");
};

const routes: Array<RouteConfig> = [
  {
    path: "/",
    name: "Home",
    component: Home,
    beforeEnter: ifAuthenticated,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/groups/search",
    name: "Groups",
    component: GroupsSearch,
  },
  {
    path: "/groups/new",
    name: "GroupNew",
    component: () => import("../views/GroupNew.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  routes,
});

export default router;
