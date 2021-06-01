<template>
  <v-app>
    <v-main>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm6 md4>
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
                <v-card-text>
                  <v-form>
                    <v-text-field
                      prepend-icon="mdi-account"
                      name="mail"
                      :label="$t('login.username')"
                      type="text"
                      v-model="username"
                    ></v-text-field>
                    <v-text-field
                      id="password"
                      prepend-icon="mdi-lock"
                      name="password"
                      v-model="password"
                      :label="$t('login.password')"
                      type="password"
                    ></v-text-field>
                  </v-form>
                </v-card-text>
                <v-spacer></v-spacer>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="primary" @click="login">{{
                    $t("login.connexion")
                  }}</v-btn>
                </v-card-actions>
              </v-container>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";

@Component
export default class Login extends Vue {
  username = "";
  password = "";

  login(): void {
    let mail = this.username;
    let password = this.password;
    this.$store
      .dispatch("login", { mail, password })
      .then(() => this.$router.push({ name: "Home" }))
      .catch((err) => console.log(err));
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