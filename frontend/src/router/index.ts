import Vue from "vue";
import VueRouter, { NavigationGuardNext, Route, RouteConfig } from "vue-router";
import auth from "@/store/modules/user";

Vue.use(VueRouter);

const routes: Array<RouteConfig> = [
  {
    path: "/",
    name: "Home",
    component: () => import("../views/Home.vue"),
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
    meta: {
      isAuthNeeded: false,
    },
  },
  {
    path: "/groups",
    name: "GroupSearch",
    component: () => import("../views/GroupSearch.vue"),
  },
  {
    path: "/groups/new",
    name: "GroupNew",
    component: () => import("../views/GroupNew.vue"),
  },
  {
    path: "/groups/:group_id",
    name: "Group",
    component: () => import("../views/GroupContainer.vue"),
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
        path: "subjects",
        name: "subjects",
        component: () => import("../views/Group/Subjects.vue"),
        children: [
          {
            path: "new",
            name: "newSubject",
            component: () => import("../views/Subject/SubjectCreateOrEdit.vue"),
          },
          {
            path: ":subject_id",
            name: "subjectDisplay",
            component: () => import("../views/Subject/SubjectDisplay.vue"),
          },
          {
            path: ":subject_id/edit",
            name: "subjectEdit",
            component: () => import("../views/Subject/SubjectCreateOrEdit.vue"),
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
        ],
      },
    ],
  },
  {
    path: "/profile",
    name: "Profile",
    component: () => import("../views/Profile.vue"),
  },
  {
    path: "/403",
    name: "Forbidden",
    component: () => import("../views/Forbidden.vue"),
    meta: {
      isAuthNeeded: false,
    },
  },
  {
    path: "/getstarted",
    name: "GetStarted",
    component: () => import("../views/GetStarted/GetStarted.vue"),
    meta: {
      isAuthNeeded: false,
    },
  },
  {
    path: "*",
    name: "NotFound",
    component: () => import("../views/PageNotFound.vue"),
    meta: {
      isAuthNeeded: false,
    },
  },
];

const router = new VueRouter({
  mode: "history",
  scrollBehavior: (to) => {
    if (to.hash) {
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve({
            selector: to.hash,
            behavior: "smooth",
            offset: { x: 0, y: 50 },
          });
        }, 0);
      });
    } else {
      return { x: 0, y: 0 };
    }
  },
  routes,
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
  } else {
    next();
  }
});

export default router;
