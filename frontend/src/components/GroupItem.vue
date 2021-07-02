<template>
  <v-card
    elevation="2"
    :to="
      isMember ? { name: 'Group', params: { group_id: group.id } } : undefined
    "
  >
    <div class="d-flex">
      <div>
        <v-card-title class="text-h5" v-text="group.name"></v-card-title>
        <v-card-subtitle class="text-subtitle2 text-justify">
          <span class="mr-3">
            <v-icon>mdi-account-group</v-icon>
            {{ group.metadata.members }}
          </span>
          <span class="mr-3">
            <v-icon>{{ $t("task.icon") }}</v-icon>
            {{ group.metadata.tasks }}
          </span>
          <span>
            <v-icon>{{ $t("subject.icon") }}</v-icon>
            {{ group.metadata.subjects }}
          </span>
        </v-card-subtitle>

        <v-card-text class="text-justify">
          {{ group.description | limit(200) }}
        </v-card-text>

        <v-card-actions class="mb-2" v-if="hasJoin">
          <v-btn
            v-if="group.status == status.PENDING"
            class="ml-2 mt-5"
            small
            color="warning"
            disabled
          >
            {{ $t("groups.pending") }}
          </v-btn>
          <v-btn
            v-else-if="group.status == status.REFUSED"
            class="ml-2 mt-5"
            small
            color="error"
            disabled
          >
            {{ $t("groups.refused") }}
          </v-btn>
          <v-btn
            v-else
            class="ml-2 mt-5"
            small
            color="success"
            :loading="isLoading"
            @click.prevent="askJoin"
          >
            {{ $t("groups.join") }}
          </v-btn>
        </v-card-actions>
      </div>
      <v-spacer></v-spacer>
      <v-avatar class="ma-3" size="125" tile v-if="group.picture">
        <v-img :src="group.picture"></v-img>
      </v-avatar>
    </div>
  </v-card>
</template>

<script lang="ts">
import { Group } from "@/types/group";
import { Component, Prop, Vue } from "vue-property-decorator";
import { GroupStatus } from "@/types/helpers";
import axios from "axios";
import groupModule from "@/store/modules/groups";

@Component
export default class GroupItem extends Vue {
  @Prop() group!: Group;
  @Prop({ default: true }) hasJoin!: boolean;
  status = GroupStatus;
  isLoading = false;

  get isMember(): boolean {
    return groupModule.groups.some((item) => item.id == this.group.id);
  }

  askJoin(): void {
    this.isLoading = true;
    axios({
      url: process.env.VUE_APP_API_BASE_URL + `groups/${this.group.id}/members`,
      method: "POST",
    })
      .then(() => {
        this.$toast.info(this.$t("groups.ask").toString());
        groupModule.loadGroups();
      })
      .catch((err) => {
        this.$toast.error(err.response.data.message);
      })
      .finally(() => {
        this.group.status = this.status.PENDING;
      });
  }
}
</script>
