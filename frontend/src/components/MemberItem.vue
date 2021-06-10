<template>
  <v-list-item>
    <v-avatar class="profile mr-3" color="primary" size="40">
      <v-img
        v-if="member.picture"
        :alt="`${member.name} avatar`"
        :src="member.picture"
      ></v-img>
      <span v-else class="white--text text-h6">{{ initials }}</span>
    </v-avatar>

    <v-list-item-content>
      <v-list-item-title>
        {{ member.name }}
        <v-icon v-if="isAdmin" color="yellow darken-4">mdi-crown</v-icon>
      </v-list-item-title>
    </v-list-item-content>

    <v-list-item-action-text>
      <slot></slot>
    </v-list-item-action-text>
  </v-list-item>
</template>

<script lang="ts">
import { Member } from "@/types/Member";
import authModule from "@/store/modules/auth";
import groupModule from "@/store/modules/groups";
import { Component, Vue, Prop } from "vue-property-decorator";
import { User } from "@/types/user";

@Component
export default class MemberItem extends Vue {
  @Prop() member!: Member;

  get loggedUser(): User | undefined {
    return authModule.user;
  }

  get isAdmin(): boolean {
    if (!groupModule.group) return false;
    return groupModule.group.user_id === this.member.id;
  }

  get initials(): string {
    if (this.member.name === undefined) return "";

    let split = this.member.name.split(" ");
    let name = "";

    if (split.length > 1) {
      name = split[0].charAt(0) + split[split.length - 1].charAt(0);
    } else {
      name = this.member.name.replace(/[^a-zA-Z]/gi, "").substring(0, 2);
    }

    return name.toUpperCase();
  }
}
</script>
