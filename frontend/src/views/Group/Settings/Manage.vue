<template>
  <v-card v-if="group" flat>
    <v-toolbar flat>
      <v-toolbar-title class="text-h4 font-weight-light">
        {{ $t("group.tabs.settings") }}
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn
        v-if="group.user_id != user.id"
        color="error"
        small
        @click="leaveGroup"
      >
        <v-icon>mdi-logout</v-icon>
        {{ $t("global.quit") }}
      </v-btn>
    </v-toolbar>
    <v-card-text>
      <avatar-upload
        @handleResponse="handleUpload"
        :upload-url="uploadURL"
        :picture="group.picture"
        :name="group.name"
        :size="128"
      />
    </v-card-text>
    <v-card-text>
      <v-text-field
        :label="$t('groups.create.name.label')"
        :placeholder="$t('groups.create.name.placeholder')"
        v-model="group.name"
        :messages="$t('groups.create.name.help')"
        autocomplete="off"
        :error-messages="errors.name"
        @input="errors.name = []"
      />
    </v-card-text>
    <v-card-text>
      <v-textarea
        :label="$t('groups.create.description.label')"
        :placeholder="$t('groups.create.description.placeholder')"
        v-model="group.description"
        :messages="$t('groups.create.description.help')"
        autocomplete="off"
        :error-messages="errors.description"
        @input="errors.description = []"
      />
    </v-card-text>
    <v-card-actions class="text-right align-right">
      <v-spacer></v-spacer>
      <v-btn text color="success" @click="updateGroup">{{
        $t("global.save")
      }}</v-btn>
    </v-card-actions>
    <v-divider v-if="isAuthorGroup" class="my-8" />
    <v-card-actions>
      <v-btn text color="error" @click="delGroup" v-if="isAuthorGroup">{{
        $t("group.delete")
      }}</v-btn>
    </v-card-actions>
    <confirm-modal ref="confirm" />
  </v-card>
</template>

<script lang="ts">
import { Group } from "@/types/group";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import AvatarUpload from "@/components/utility/AvatarUpload.vue";
import userModule from "@/store/modules/user";
import { Dictionary } from "@/types/helpers";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";
import { Ref, Vue } from "vue-property-decorator";
import { User } from "@/types/user";
import authModule from "@/store/modules/user";

@Component({
  components: {
    AvatarUpload,
    ConfirmModal,
  },
})
export default class GroupManage extends Vue {
  errors: Dictionary<string | string[]> = {};
  @Ref() readonly confirm!: ConfirmModal;

  get group(): Group | undefined {
    return JSON.parse(JSON.stringify(groupModule.group));
  }

  get user(): User | undefined {
    return authModule.user;
  }

  get uploadURL(): string {
    return process.env.VUE_APP_API_BASE_URL + "groups/" + this.group?.id;
  }

  get isAuthorGroup(): boolean {
    if (!userModule.user || !this.group) return false;
    return userModule.user.id == this.group.user_id;
  }

  handleUpload(group: Group): void {
    if (groupModule.group) {
      groupModule.group.picture = group.picture;
    }
  }

  async updateGroup(): Promise<void> {
    if (!this.group) return;

    try {
      await groupModule.updateGroup(this.group);
      this.$toast.success(this.$t("global.success").toString());
      this.errors = {};
    } catch (err: any) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error-form").toString());
    }
  }

  async delGroup(): Promise<void> {
    const title = this.$t("group.dialog.confirm.title").toString();
    const message = this.$t("group.dialog.confirm.message").toString();
    const reply = await this.confirm.open(title, message, {});
    if (reply) {
      if (this.group) {
        try {
          await groupModule.removeGroup(this.group.id);
          this.$toast.success(this.$t("global.success").toString());
          this.$router.push({ name: "Home" });
        } catch (err) {
          this.$toast.success(this.$t("global.errors.unknown").toString());
        }
      }
    }
  }

  async leaveGroup(): Promise<void> {
    const title = this.$t("group.dialog.leave.title").toString();
    const message = this.$t("group.dialog.leave.message").toString();
    const reply = await this.confirm.open(title, message, {});
    if (reply) {
      if (!this.group) return;
      const group = this.group;
      try {
        this.$router.push({ name: "Home" });
        await groupModule.leave(group);
      } catch (err: any) {
        this.$toast.error(err.response.data.message);
      }
    }
  }
}
</script>
