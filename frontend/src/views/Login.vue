<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex xs12 sm6 md3>
        <v-card
          class="elevation-2 d-flex flex-column"
          min-height="400"
          max-width="500px"
        >
          <v-toolbar
            dark
            class="toolbar-login d-flex flex-column"
            :tile="false"
            flat
            max-height="115px"
          >
            <v-img
              alt="Caravel logo"
              class="shrink mr-2"
              contain
              src="@/assets/white2.png"
              transition="scale-transition"
            />
          </v-toolbar>
          <v-container class="mt-3">
            <v-form @submit.prevent="login">
              <v-card-text>
                <v-text-field
                  prepend-icon="mdi-account"
                  name="mail"
                  :label="$t('login.mail.label')"
                  :placeholder="$t('login.mail.placerholder')"
                  type="text"
                  v-model="username"
                  outlined
                ></v-text-field>
                <v-text-field
                  id="password"
                  prepend-icon="mdi-lock"
                  name="password"
                  v-model="password"
                  :label="$t('login.password')"
                  type="password"
                  outlined
                ></v-text-field>
              </v-card-text>
              <v-spacer></v-spacer>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" type="submit" :loading="loading">{{
                  $t("login.connexion")
                }}</v-btn>
              </v-card-actions>
            </v-form>
          </v-container>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import auth from "@/store/modules/user";
import groupModule from "@/store/modules/groups";

@Component
export default class Login extends Vue {
  username = "";
  password = "";
  isLoading = false;

  async login(): Promise<void> {
    let mail = this.username;
    let password = this.password;
    this.isLoading = true;

    try {
      await auth.login({ mail, password });
      await groupModule.loadGroups();
      this.$toast.success(this.$t("login.logged-in").toString());
      if (this.$route.query.redirect)
        this.$router.push(this.$route.query.redirect.toString());
      else this.$router.push({ name: "Home" });
    } catch {
      auth.logout();
      this.$toast.error(this.$t("login.failed").toString());
    } finally {
      this.isLoading = false;
    }
  }

  get loading(): boolean {
    return this.isLoading;
  }
}
</script>

<style lang="scss" scoped>
.toolbar-login {
  background: linear-gradient(329deg, #c58af5 0%, rgba(92, 25, 146, 0.98) 100%);
  &::v-deep > div {
    justify-content: center;
    height: 100% !important;
  }
}
</style>
