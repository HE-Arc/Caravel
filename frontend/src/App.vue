<template>
  <v-app>
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
      <search-bar class="ml-2 d-none d-md-flex" v-if="isLoggedIn"></search-bar>

      <v-spacer></v-spacer>

      <add-content v-if="isLoggedIn"></add-content>

      <v-btn icon v-if="isLoggedIn">
        <v-icon>mdi-bell-ring</v-icon>
      </v-btn>

      <user-menu class="mr-3" v-if="isLoggedIn"></user-menu>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts">
import Vue from "vue";
import "vue-toast-notification/dist/theme-sugar.css";
import Component from "vue-class-component";
import UserMenu from "./components/UserMenu.vue";
import AddContent from "./components/AddContent.vue";
import SearchBar from "./components/SearchBar.vue";
import auth from "@/store/modules/auth";

@Component({
  components: {
    UserMenu,
    AddContent,
    SearchBar,
  },
})
export default class App extends Vue {
  get isLoggedIn(): boolean {
    return auth.isLoggedIn;
  }

  get token(): string {
    return auth.token;
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
