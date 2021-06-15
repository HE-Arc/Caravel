<template>
  <v-card v-if="group" flat>
    <v-toolbar flat>
      <v-toolbar-title class="text-h4 font-weight-light">
        {{ $t("group.settings.members") }}
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
      <v-list rounded>
        <member-item
          v-for="member in members"
          :key="member.id"
          :member="member"
        >
          <v-chip small class="text-h7" v-if="user.id == member.id">
            {{ $t("global.itsme") }}
          </v-chip>
          <div v-else-if="group.user_id == user.id">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="primary"
                  text
                  small
                  v-bind="attrs"
                  v-on="on"
                  @click="changeLeader(member)"
                >
                  {{ $t("group.members.give_lead.label") }}
                </v-btn>
              </template>
              <span>
                {{ $t("group.members.give_lead.help", [member.name]) }}
              </span>
            </v-tooltip>

            <v-btn color="error" text small @click="kickMember(member)">{{
              $t("group.members.kick")
            }}</v-btn>
          </div>
        </member-item>

        <v-divider v-if="refused.length > 0"></v-divider>

        <div v-if="refused.length > 0">
          <v-subheader>{{
            $tc("group.members.rejected", refused.length)
          }}</v-subheader>
          <member-item
            v-for="member in refused"
            :key="member.id"
            :member="member"
            :isGroupAdmin="group.user_id == member.id"
          />
        </div>
      </v-list>
    </v-card-text>
  </v-card>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import authModule from "@/store/modules/auth";
import memberModule from "@/store/modules/members";
import { Group } from "@/types/group";
import { Member } from "@/types/Member";
import MemberItem from "@/components/MemberItem.vue";
import { User } from "@/types/user";

@Component({
  components: {
    MemberItem,
  },
})
export default class GroupMembers extends Vue {
  get user(): User | undefined {
    return authModule.user;
  }

  get members(): Member[] {
    return memberModule.accepted;
  }

  get refused(): Member[] {
    return memberModule.refused;
  }

  get group(): Group | undefined {
    return groupModule.group;
  }

  async changeLeader(member: Member): Promise<void> {
    if (!this.group) return;
    const group = JSON.parse(JSON.stringify(this.group)); // quick deep copy
    group.user_id = member.id;
    try {
      await groupModule.updateGroup(group);
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(err.response.data.message);
    }
  }

  async kickMember(member: Member): Promise<void> {
    if (!this.group) return;
    const groupId = this.group.id;
    try {
      await memberModule.removeMember({ groupId, member });
      this.$toast.success(this.$t("global.success").toString());
    } catch (err) {
      this.$toast.error(err.response.data.message);
    }
  }

  async leaveGroup(): Promise<void> {
    if (!this.group) return;
    const group = this.group;
    try {
      await groupModule.leave(group);
      this.$router.push({ name: "Home" });
    } catch (err) {
      this.$toast.error(err.response.data.message);
    }
  }
}
</script>
