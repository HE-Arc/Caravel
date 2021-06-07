<template>
  <v-container class="mt-8">
    <avatar-cropper
      @uploaded="handleUploaded"
      requestMethod="POST"
      :labels="labels"
      trigger="#pick-avatar"
      upload-form-name="picture"
      :upload-headers="{
        Authorization: `Bearer ${authToken}`,
        Accept: 'application/json',
      }"
      :upload-url="uploadURL"
      :upload-form-data="{
        _method: 'PATCH',
      }"
    />
    <v-row>
      <v-col cols="12" md="4">
        <v-card dark class="text-center">
          <v-container>
            <v-row>
              <v-col cols="12" class="align-center">
                <button id="pick-avatar">
                  <v-avatar color="brown" class="profile" size="164">
                    <v-img
                      v-if="authUser.picture"
                      :src="authUser.picture_full"
                    ></v-img>
                    <span v-else class="white--text text-h6">{{
                      initials
                    }}</span>
                  </v-avatar>
                </button>
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
import { Getter } from "vuex-class";
import AvatarCropper from "vue-avatar-cropper";
import { Dictionary } from "vue-router/types/router";
import { AuthActions } from "@/store/modules/auth";

@Component({
  components: {
    AvatarCropper,
  },
})
export default class Profile extends Vue {
  @Getter authUser!: User;
  @Getter authToken!: string;
  showCrop = false;

  get labels(): Dictionary<string> {
    return {
      submit: this.$t("global.submit").toString(),
      cancel: this.$t("global.cancel").toString(),
    };
  }

  get getPicture(): string {
    return this.authUser.picture ? this.authUser.picture_full : "";
  }

  get uploadURL(): string {
    return process.env.VUE_APP_API_BASE_URL + "profile";
  }

  get initials(): string {
    if (this.authUser === undefined) return "";

    let split = this.authUser?.name.split(" ");
    let name =
      split.length > 1
        ? split[0].charAt(0) + split[split.length - 1].charAt(0)
        : this.authUser?.name.charAt(0) + ".";

    return name.toUpperCase();
  }

  changeFile(): void {
    console.log("0test");
  }

  handleUploaded(user: User): void {
    this.$store.dispatch(AuthActions.UPDATE, user);
  }
}
</script>
