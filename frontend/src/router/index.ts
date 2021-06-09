import Vue from "vue";
import VueRouter, { NavigationGuardNext, Route, RouteConfig } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import GroupsSearch from "../views/GroupSearch.vue";
import GroupContainer from "../views/GroupContainer.vue";
import auth from "@/store/modules/auth";

Vue.use(VueRouter);

const ifAuthenticated = (to: Route, from: Route, next: NavigationGuardNext) => {
  if (auth.isLoggedIn) {
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
    path: "/groups",
    name: "GroupSearch",
    component: GroupsSearch,
  },
  {
    path: "/groups/:group_id",
    name: "Group",
    component: GroupContainer,
    redirect: { name: "tasks" },
    children: [
      {
        path: "tasks",
        name: "tasks",
        component: () => import("../views/Group/TaskList.vue"),
      },
      {
        path: "calendar",
        name: "calendar",
        component: () => import("../views/Group/Calendar.vue"),
      },
      {
        path: "timeline",
        name: "timeline",
        component: () => import("../views/Group/Timeline.vue"),
      },
      {
        path: "stats",
        name: "stats",
        component: () => import("../views/Group/Stats.vue"),
      },
      {
        path: "settings",
        name: "settings",
        component: () => import("../views/Group/Settings.vue"),
        redirect: { name: "manage" },
        children: [
          {
            path: "manage",
            name: "manage",
            component: () => import("../views/Group/Settings/Manage.vue"),
          },
          {
            path: "members",
            name: "members",
            component: () => import("../views/Group/Settings/Members.vue"),
          },
          {
            path: "requests",
            name: "requests",
            component: () => import("../views/Group/Settings/Requests.vue"),
          },
        ],
      },
    ],
  },
  {
    path: "/groups/new",
    name: "GroupNew",
    component: () => import("../views/GroupNew.vue"),
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("../views/Profile.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  routes,
});

export default router;
