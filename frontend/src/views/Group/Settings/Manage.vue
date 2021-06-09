<template>
  <v-card v-if="group">
    <v-card-title>{{ $t("group.tabs.settings") }}</v-card-title>
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
      />
    </v-card-text>
    <v-card-actions class="text-right align-right">
      <v-btn text color="error">Annuler</v-btn>
      <v-spacer></v-spacer>
      <v-btn text color="primary" @click="updateGroup">{{
        $t("global.save")
      }}</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { Group } from "@/types/group";
import Vue from "vue";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import AvatarUpload from "@/components/AvatarUpload.vue";
import { Dictionary } from "@/types/helpers";

@Component({
  components: {
    AvatarUpload,
  },
})
export default class GroupManage extends Vue {
  errors: Dictionary<string | string[]> = {};

  get group(): Group | undefined {
    return JSON.parse(JSON.stringify(groupModule.group));
  }

  get uploadURL(): string {
    return process.env.VUE_APP_API_BASE_URL + "groups/" + this.group?.id;
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
    } catch (err) {
      this.errors = err.response.data.errors;
      this.$toast.error(this.$t("global.error_form").toString());
    }
  }
}
</script>
