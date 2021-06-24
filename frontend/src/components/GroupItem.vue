<template>
  <v-card elevation="2" color="lightPurple">
    <div class="d-flex flex-no-wrap justify-space-between">
      <div>
        <v-card-title class="text-h5" v-text="group.name"></v-card-title>

        <v-card-subtitle
          style="height: 50px; text-overflow: ellipsis"
          class="text-justify"
          v-text="group.description"
        ></v-card-subtitle>

        <v-card-actions class="mb-2">
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
            @click="askJoin"
          >
            {{ $t("groups.join") }}
          </v-btn>
        </v-card-actions>
      </div>

      <v-avatar class="ma-3" size="125" tile v-if="group.picture">
        <v-img :src="group.picture"></v-img>
      </v-avatar>
    </div>
  </v-card>
</template>

<script lang="ts">
import { Group } from "@/types/Group";
import { Component, Prop, Vue } from "vue-property-decorator";
import { GroupStatus } from "@/types/helpers";
import axios from "axios";

@Component
export default class GroupItem extends Vue {
  @Prop() group!: Group;
  status = GroupStatus;
  isLoading = false;

  askJoin(): void {
    this.isLoading = true;
    axios({
      url: process.env.VUE_APP_API_BASE_URL + `groups/${this.group.id}/members`,
      method: "POST",
    })
      .then(() => {
        this.$toast.info(this.$t("groups.ask").toString());
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
