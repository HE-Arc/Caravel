<template>
  <v-container class="mt-8">
    <v-row>
      <v-col cols="12" md="4">
        <v-card dark class="text-center">
          <v-container>
            <v-row>
              <v-col cols="12" class="align-center">
                <avatar-upload
                  @handleResponse="handleUpload"
                  :token="authToken"
                  :upload-url="uploadURL"
                  :picture="authUser.picture"
                  :name="authUser.name"
                />
              </v-col>
              <v-col>
                <v-list-item color="rgba(0, 0, 0, .4)" dark>
                  <v-list-item-content>
                    <v-list-item-title class="text-h6">
                      {{ authUser.name }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      {{
                        authUser.isTeacher
                          ? $t("global.roles.teacher")
                          : $t("global.roles.student")
                      }}
                    </v-list-item-subtitle>
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>
          </v-container>
        </v-card>
      </v-col>
      <v-col cols="12" md="8">
        <v-card>
          <v-card-title> {{ $t("profile.info") }} </v-card-title>
          <v-card-text class="ml-2">
            <v-text-field
              :value="authUser.name"
              :label="$t('profile.name')"
              filled
              disabled
            ></v-text-field>
            <v-text-field
              :value="authUser.email"
              :label="$t('profile.email')"
              filled
              disabled
            ></v-text-field>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import { User } from "@/types/user";
import Vue from "vue";
import Component from "vue-class-component";
import AvatarCropper from "vue-avatar-cropper";
import auth from "@/store/modules/auth";
import AvatarUpload from "@/components/AvatarUpload.vue";

@Component({
  components: {
    AvatarCropper,
    AvatarUpload,
  },
})
export default class Profile extends Vue {
  showCrop = false;

  get authToken(): string {
    return auth.token;
  }

  get authUser(): User | undefined {
    return auth.user;
  }

  get getPicture(): string {
    return this.authUser ? this.authUser.picture : "";
  }

  get uploadURL(): string {
    return process.env.VUE_APP_API_BASE_URL + "profile";
  }

  get initials(): string {
    if (auth.user === undefined) return "";

    let split = auth.user?.name.split(" ");
    let name =
      split.length > 1
        ? split[0].charAt(0) + split[split.length - 1].charAt(0)
        : auth.user?.name.charAt(0) + ".";

    return name.toUpperCase();
  }

  handleUpload(user: User): void {
    auth.update(user);
  }
}
</script>
