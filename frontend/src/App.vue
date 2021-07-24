<template>
  <v-app>
    <nprogress-container></nprogress-container>
    <v-app-bar app color="white" flat>
      <div class="d-flex align-center">
        <router-link to="/">
          <v-img
            alt="Caravel Logo"
            class="shrink mr-2"
            contain
            src="@/assets/logo.png"
            transition="scale-transition"
          />
        </router-link>
      </div>
      <quick-search
        class="ml-2 d-none d-md-flex"
        v-if="isLoggedIn"
      ></quick-search>

      <v-spacer></v-spacer>

      <add-content v-if="isLoggedIn"></add-content>

      <notificatons v-if="isLoggedIn"></notificatons>

      <user-menu class="mr-3" v-if="isLoggedIn"></user-menu>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
    <footer-app />
  </v-app>
</template>

<script lang="ts">
import Vue from "vue";
import "vue-toast-notification/dist/theme-sugar.css";
import Component from "vue-class-component";
import UserMenu from "./components/UserMenu.vue";
import AddContent from "./components/AddContent.vue";
import auth from "@/store/modules/user";
import QuickSearch from "@/components/QuickSearch.vue";
import Notificatons from "@/components/Notifications.vue";
import groupModule from "@/store/modules/groups";
import { Watch } from "vue-property-decorator";
import NprogressContainer from "@/components/utility/NprogressContainer.vue";
import FooterApp from "@/views/Footer.vue";

@Component({
  components: {
    UserMenu,
    AddContent,
    QuickSearch,
    Notificatons,
    NprogressContainer,
    FooterApp,
  },
})
export default class App extends Vue {
  loaded = false;

  get isLoggedIn(): boolean {
    return auth.isLoggedIn;
  }

  created(): void {
    this.init();
  }

  async init(): Promise<void> {
    if (this.isLoggedIn && !this.loaded) {
      await groupModule.loadGroups();
    }
    this.loaded = true;
  }

  @Watch("isLoggedIn")
  logInChange(): void {
    this.init();
  }
}
</script>

<style scoped>
header.theme--light::v-deep .v-toolbar__content {
  border-bottom: 1px solid #f3f3f3;
}
</style>

<style>
p.v-toast__text {
  font-family: "Roboto";
  font-weight: 300;
}
</style>
