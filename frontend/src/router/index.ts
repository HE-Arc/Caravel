import Vue from "vue";
import VueRouter, { NavigationGuardNext, Route, RouteConfig } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import GroupsSearch from "../views/GroupSearch.vue";
import GroupContainer from "../views/GroupContainer.vue";
import auth from "@/store/modules/user";

Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
    meta: {
      isAuthNeeded: false,
    },
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
        children: [
          {
            name: "newTask",
            path: "new",
            component: () => import("../views/Task/TaskCreateOrEdit.vue"),
          },
          {
            path: ":task_id",
            name: "taskDisplay",
            component: () => import("../views/Task/TaskDisplay.vue"),
          },
          {
            path: ":task_id/edit",
            name: "taskEdit",
            component: () => import("../views/Task/TaskCreateOrEdit.vue"),
          },
        ],
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
          {
            path: "subjects",
            name: "subjects",
            component: () => import("../views/Group/Settings/Subjects.vue"),
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
  {
    path: "*",
    name: "NotFound",
    component: () => import("../views/PageNotFound.vue"),
  },
];

const router = new VueRouter({
  mode: "history",
  routes,
  scrollBehavior: function (to) {
    if (to.hash) {
      return {
        selector: to.hash,
        offset: { x: 0, y: 50 },
      };
    }
  },
});

router.beforeEach((to: Route, from: Route, next: NavigationGuardNext) => {
  if (
    !auth.isLoggedIn &&
    (to.meta.isAuthNeeded == undefined || to.meta.isAuthNeeded)
  ) {
    next({
      path: "/login",
      query: {
        redirect: to.fullPath,
      },
    });
  }
  next();
});

export default router;
