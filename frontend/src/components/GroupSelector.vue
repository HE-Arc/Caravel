<template>
  <div v-if="group">
    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          v-bind="attrs"
          v-on="on"
          :ripple="false"
          large
          color="primary"
          text
          :disabled="groups.length == 0"
        >
          <v-avatar color="secondary" size="32">
            <v-img v-if="group && group.picture" :src="group.picture"></v-img>
            <span v-else class="white--text text-h7">{{
              group.name | initials
            }}</span>
          </v-avatar>
          <span class="font-weight-bold text-h6 ml-2">
            {{ group.name }}
            <v-icon v-if="groups.length > 1">mdi-menu-down</v-icon>
          </span>
        </v-btn>
      </template>
      <v-list v-if="groups && groups.length > 0">
        <v-list-item
          v-for="(item, index) in groups"
          :key="index"
          @click="navTo(item.id)"
        >
          <v-avatar color="secondary" size="32">
            <v-img v-if="item && item.picture" :src="item.picture"></v-img>
            <span v-else class="white--text text-h7">{{
              item.name | initials
            }}</span>
          </v-avatar>
          <v-list-item-title class="ml-3">{{ item.name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts">
import { Group } from "@/types/group";
import { Component, Vue } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";

@Component
export default class GroupSelector extends Vue {
  get group(): Group | undefined {
    return groupModule.group;
  }

  get groups(): Group[] {
    let groups = groupModule.groups;

    return groups.filter((group) => group.id != this.group?.id);
  }

  async navTo(groupId: string): Promise<void> {
    try {
      await groupModule.selectGroup(groupId);
      this.$router.push({ name: "Group", params: { group_id: groupId } });
    } catch {
      //pass
    }
  }
}
</script>
