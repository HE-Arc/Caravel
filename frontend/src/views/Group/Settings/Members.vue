<template>
  <v-card v-if="group" flat>
    <v-toolbar flat>
      <v-toolbar-title class="text-h4 font-weight-light">
        {{ $t("group.settings.members") }}
      </v-toolbar-title>
    </v-toolbar>
    <v-card-text>
      <v-list rounded>
        <paginate :items="members" :perPage="10">
          <template #default="{ items }">
            <member-item
              v-for="member in items"
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
                      {{ $t("group.members.give-lead.label") }}
                    </v-btn>
                  </template>
                  <span>
                    {{ $t("group.members.give-lead.help", [member.name]) }}
                  </span>
                </v-tooltip>

                <v-btn color="error" text small @click="kickMember(member)">{{
                  $t("group.members.kick")
                }}</v-btn>
              </div>
            </member-item>
          </template>
        </paginate>

        <v-divider v-if="refused.length > 0"></v-divider>

        <div v-if="refused.length > 0">
          <v-subheader>{{
            $tc("group.members.rejected", refused.length)
          }}</v-subheader>
          <paginate :items="refused" :perPage="5">
            <template #default="{ items }">
              <member-item
                v-for="member in items"
                :key="member.id"
                :member="member"
                :isGroupAdmin="group.user_id == member.id"
              >
                <v-btn
                  color="success"
                  small
                  class="mx-2"
                  @click="changeStatus(member, status.ACCEPTED)"
                  >{{ $t("global.accept") }}</v-btn
                >
              </member-item>
            </template>
          </paginate>
        </div>
      </v-list>
    </v-card-text>
    <confirm-modal ref="confirm" />
  </v-card>
</template>

<script lang="ts">
import { Vue, Ref } from "vue-property-decorator";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import authModule from "@/store/modules/user";
import memberModule from "@/store/modules/members";
import { Group } from "@/types/group";
import { Member } from "@/types/member";
import MemberItem from "@/components/MemberItem.vue";
import { User } from "@/types/user";
import ConfirmModal from "@/components/utility/ConfirmModal.vue";
import Paginate from "@/components/utility/Paginate.vue";
import { GroupStatus } from "@/types/helpers";

@Component({
  components: {
    MemberItem,
    ConfirmModal,
    Paginate,
  },
})
export default class GroupMembers extends Vue {
  @Ref() readonly confirm!: ConfirmModal;

  status = GroupStatus;

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
    } catch (err: any) {
      this.$toast.error(err.response.data.message);
    }
  }

  async kickMember(member: Member): Promise<void> {
    if (!this.group) return;
    const groupId = this.group.id;
    try {
      await memberModule.removeMember({ groupId, member });
      this.$toast.success(this.$t("global.success").toString());
    } catch (err: any) {
      this.$toast.error(err.response.data.message);
    }
  }

  async changeStatus(member: Member, status: number): Promise<void> {
    if (!this.group) return;
    const groupId = this.group.id;
    try {
      await memberModule.changeStatus({ groupId, member, status });
      this.$toast.success(this.$t("global.success").toString());
    } catch (err: any) {
      this.$toast.error(err.response.data.message);
    }
  }
}
</script>
