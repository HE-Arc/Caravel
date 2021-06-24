<template>
  <v-menu bottom min-width="200px" rounded offset-y v-if="user">
    <template v-slot:activator="{ on }">
      <v-btn icon x-large v-on="on">
        <v-avatar color="brown" size="36">
          <v-img
            v-if="user != undefined && user.picture"
            :src="user.picture"
          ></v-img>
          <span v-else class="white--text text-h6">{{
            user.name | initials
          }}</span>
        </v-avatar>
      </v-btn>
    </template>
    <v-card>
      <v-list-item-content class="justify-center">
        <div class="mx-auto text-center">
          <v-avatar color="brown" class="mb-2">
            <v-img
              v-if="user != undefined && user.picture"
              :src="user.picture"
            ></v-img>
            <span v-else class="white--text text-h6">{{
              user.name | initials
            }}</span>
          </v-avatar>
          <h3>{{ user.name }}</h3>
          <p class="text-caption mt-1">
            {{ user.email }}
          </p>
          <v-divider class="my-3"></v-divider>
          <v-btn depressed rounded text :to="{ name: 'Profile' }">{{
            $t("appbar.settings")
          }}</v-btn>
          <v-divider class="my-3"></v-divider>
          <v-btn depressed rounded text @click="logout">{{
            $t("appbar.signout")
          }}</v-btn>
        </div>
      </v-list-item-content>
    </v-card>
  </v-menu>
</template>

<script lang="ts">
import { Prop, Component, Vue } from "vue-property-decorator";
import auth from "@/store/modules/user";
import { User } from "@/types/User";

@Component
export default class UserMenu extends Vue {
  @Prop({ default: false }) isTitleHidden?: boolean;
  @Prop({ default: false }) hasDropDown?: boolean;

  get user(): User | undefined {
    return auth.user;
  }

  async logout(): Promise<void> {
    try {
      await auth.logout();
      this.$router.push({ name: "Login" });
      this.$toast.success(this.$t("login.logout").toString());
    } catch {
      this.$toast.error(this.$t("login.failed").toString());
    }
  }
}
</script>
