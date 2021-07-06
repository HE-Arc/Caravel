<template>
  <div>
    <avatar-cropper
      @uploaded="handleUploaded"
      requestMethod="POST"
      :labels="labels"
      trigger="#pick-avatar"
      upload-form-name="picture"
      :upload-headers="{
        Accept: 'application/json',
        'X-XSRF-TOKEN': xsrfToken,
      }"
      :upload-url="uploadUrl"
      :upload-form-data="{
        _method: 'PATCH',
      }"
      :withCredentials="true"
    />
    <button id="pick-avatar">
      <v-avatar color="primary" class="profile" :size="size">
        <v-img v-if="picture" :src="picture"></v-img>
        <span v-else class="white--text text-h6">{{ name | initials }}</span>
      </v-avatar>
    </button>
  </div>
</template>

<script lang="ts">
import { Prop, Component, Vue, Emit } from "vue-property-decorator";
import AvatarCropper from "vue-avatar-cropper";
import { Dictionary } from "@/types/helpers";
import userModule from "@/store/modules/user";

@Component({
  components: {
    AvatarCropper,
  },
})
export default class AvatarUpload extends Vue {
  @Prop({ default: "" }) uploadUrl!: string;
  @Prop({ default: undefined }) token!: string | undefined;
  @Prop({ default: 164 }) size!: number;
  @Prop({ default: undefined }) picture: string | undefined;
  @Prop({ default: "" }) name!: string;
  response = "";

  get labels(): Dictionary<string> {
    return {
      submit: this.$t("global.submit").toString(),
      cancel: this.$t("global.cancel").toString(),
    };
  }

  get xsrfToken(): string {
    return userModule.token;
  }

  handleUploaded(data: string): void {
    this.response = data;
    this.handleResponse();
  }

  @Emit("handleResponse")
  handleResponse(): string {
    return this.response;
  }
}
</script>

<style lang="scss">
//fix dark vuetify theme bug
.avatar-cropper-btn {
  color: rgb(34, 34, 34);
}
</style>
