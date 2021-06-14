<template>
  <v-container>
    <v-row v-if="group">
      <v-col cols="12" sm="4" md="3" lg="2">
        <v-list nav dense outlined rounded>
          <v-list-item-group color="primary">
            <v-list-item
              v-for="(item, key) in items"
              :key="key"
              v-model="activeTab"
              :to="
                $router.resolve({ name: key, params: { group_id: group.id } })
                  .href
              "
            >
              <v-list-item-icon>
                <v-icon v-text="item.icon"></v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title>
                  {{ $t("group.settings." + key) }}
                  <v-chip
                    x-small
                    color="error"
                    v-if="item.count != undefined && item.count > 0"
                    v-text="item.count"
                  />
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-col>
      <v-col cols="12" sm="8" md="9" lg="10">
        <router-view />
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col cols="12">
        <v-progress-circular
          indeterminate
          color="primary"
        ></v-progress-circular>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import main from "@/store/modules/groups";
import { Group } from "@/types/group";
import GroupManage from "@/views/Group/Settings/Manage.vue";
import memberModule from "@/store/modules/members";
import { Dictionary } from "@/types/helpers";

@Component({
  components: {
    GroupManage,
  },
})
export default class GroupSettings extends Vue {
  get group(): Group | undefined {
    return main.group;
  }

  get pendingCount(): number {
    return memberModule.pending.length ?? 0;
  }

  activeTab = "";
  get items(): Dictionary<Dictionary<string | number>> {
    return {
      manage: {
        icon: "mdi-cog",
      },
      members: {
        icon: "mdi-account-group",
      },
      requests: {
        icon: "mdi-account-multiple-plus",
        count: this.pendingCount,
      },
      subjects: {
        icon: this.$t("subject.icon").toString(),
      },
    };
  }
}
</script>
