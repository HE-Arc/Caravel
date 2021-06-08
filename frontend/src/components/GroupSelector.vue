<template>
  <div>
    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          v-bind="attrs"
          v-on="on"
          :ripple="false"
          large
          color="primary"
          text
        >
          <v-avatar color="secondary" size="32">
            <v-img
              v-if="group && group.picture"
              :src="group.picture_full"
            ></v-img>
            <span v-else class="white--text text-h7">{{
              initials(group.name)
            }}</span>
          </v-avatar>
          <span class="font-weight-bold ml-2">
            {{ group.name }}
            <v-icon>mdi-menu-down</v-icon>
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
            <v-img v-if="item && item.picture" :src="item.picture_full"></v-img>
            <span v-else class="white--text text-h7">{{
              initials(item.name)
            }}</span>
          </v-avatar>
          <v-list-item-title class="ml-3">{{ item.name }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts">
//import main from "@/store/modules/main";
import { Group } from "@/types/group";
import { Component, Vue } from "vue-property-decorator";
import main from "@/store/modules/main";
import auth from "@/store/modules/auth";

@Component
export default class GroupSelector extends Vue {
  get group(): Group | undefined {
    return main.group;
  }

  get groups(): Group[] {
    return auth.user ? auth.user.groups_available : [];
  }

  initials(groupName: string): string {
    let name = groupName ? groupName.replace(/[^a-zA-Z]/gi, "") : "";
    return name.substring(0, 2).toUpperCase();
  }

  navTo(groupId: string): void {
    main.loadGroup(groupId).then(() => {
      this.$router.push({ name: "Group", params: { group_id: groupId } });
    });
  }
}
</script>
