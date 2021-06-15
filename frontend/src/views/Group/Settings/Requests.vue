<template>
  <v-card flat>
    <v-toolbar flat>
      <v-toolbar-title class="text-h4 font-weight-light">
        {{ $tc("group.request.pending", members.length) }}
      </v-toolbar-title>
    </v-toolbar>
    <v-card-text v-if="members.length > 0">
      <v-list rounded>
        <member-item
          v-for="member in members"
          :key="member.id"
          :member="member"
        >
          <v-btn
            color="success"
            small
            class="mx-2"
            @click="changeStatus(member, status.ACCEPTED)"
            >{{ $t("global.accept") }}</v-btn
          >
          <v-btn
            color="error"
            small
            @click="changeStatus(member, status.REFUSED)"
            >{{ $t("global.refuse") }}</v-btn
          >
        </member-item>
      </v-list>
    </v-card-text>
    <v-card-text v-else>
      {{ $t("group.request.empty") }}
    </v-card-text>
  </v-card>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import memberModule from "@/store/modules/members";
import authModule from "@/store/modules/auth";
import { Group } from "@/types/group";
import { Member } from "@/types/Member";
import MemberItem from "@/components/MemberItem.vue";
import { User } from "@/types/user";
import { GroupStatus } from "@/types/helpers";

@Component({
  components: {
    MemberItem,
  },
})
export default class GroupMembers extends Vue {
  status = GroupStatus;

  get user(): User | undefined {
    return authModule.user;
  }

  get members(): Member[] {
    return memberModule.pending;
  }

  get group(): Group | undefined {
    return groupModule.group;
  }

  async changeStatus(member: Member, status: number): Promise<void> {
    if (!this.group) return;
    const groupId = this.group.id;
    try {
      await memberModule.changeStatus({ groupId, member, status });
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(err.response.data.message);
    }
  }
}
</script>
