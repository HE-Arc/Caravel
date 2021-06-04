<template>
  <v-app>
    <v-app-bar color="white" flat>
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
      <search-bar class="ml-2" v-if="isLoggedIn"></search-bar>

      <v-spacer></v-spacer>

      <add-content v-if="isLoggedIn"></add-content>

      <v-btn icon v-if="isLoggedIn">
        <v-icon>mdi-bell-ring</v-icon>
      </v-btn>

      <user-icon class="mr-3" v-if="isLoggedIn"></user-icon>
    </v-app-bar>

    <v-main class="pa-0">
      <router-view />
    </v-main>
  </v-app>
</template>

<script lang="ts">
import Vue from "vue";
import "vue-toast-notification/dist/theme-sugar.css";
import Component from "vue-class-component";
import axios from "axios";
import { Getter } from "vuex-class";
import UserIcon from "./components/UserIcon.vue";
import AddContent from "./components/AddContent.vue";
import SearchBar from "./components/SearchBar.vue";

@Component({
  components: {
    UserIcon,
    AddContent,
    SearchBar,
  },
})
export default class App extends Vue {
  @Getter isLoggedIn!: boolean;
  @Getter authToken!: string;

  mounted(): void {
    axios.defaults.headers.common["Authorization"] = `Bearer ${this.authToken}`;
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
