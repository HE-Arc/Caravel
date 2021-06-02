<template>
  <v-app>
    <v-app-bar app light flat color="white">
      <div class="d-flex align-center">
        <v-img
          alt="Caravel Logo"
          class="shrink mr-2"
          contain
          src="@/assets/logo.png"
          transition="scale-transition"
        />
      </div>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-magnify</v-icon>
      </v-btn>

      <v-btn icon @click="logout" v-if="isLoggedIn">
        <v-icon>mdi-heart</v-icon>
      </v-btn>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
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
import { AuthActions } from "./store/modules/auth";
import { Getter } from "vuex-class";

@Component
export default class App extends Vue {
  @Getter isLoggedIn!: boolean;

  logout(): void {
    this.$store
      .dispatch(AuthActions.LOGOUT)
      .then(() => {
        this.$router.push({ name: "Login" });
        this.$toast.success(this.$t("login.logout").toString());
      })
      .catch(() => this.$toast.error(this.$t("login.failed").toString()));
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
