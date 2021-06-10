<template>
  <v-container fluid>
    <v-row>
      <v-col class="mb-5" cols="12">
        <group-selector />
      </v-col>
      <v-col class="pa-0" cols="12">
        <v-tabs v-model="activeTab" show-arrows class="ml-6">
          <v-tab
            v-for="(item, key) in tabs"
            :key="item + key"
            :to="$router.resolve({ name: key }).href"
          >
            <v-icon class="mr-2">{{ item.icon }}</v-icon>
            {{ $t("group.tabs." + key) }}
            <v-chip
              class="ml-1"
              small
              color="error"
              v-if="item.count != undefined && item.count > 0"
              v-text="item.count"
            />
          </v-tab>
        </v-tabs>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <router-view />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import GroupSelector from "@/components/GroupSelector.vue";
import groupModule from "@/store/modules/groups";
import memberModule from "@/store/modules/members";
import { Group } from "@/types/group";
import { Dictionary } from "@/types/helpers";

@Component({
  components: {
    GroupSelector,
  },
})
export default class GroupContainer extends Vue {
  groupId = this.$route.params.group_id;
  activeTab = "tasks";

  get group(): Group | undefined {
    return groupModule.group;
  }

  get pendingCount(): number {
    return memberModule.pending.length ?? 0;
  }

  // https://stackoverflow.com/questions/49721710/how-to-use-vuetify-tabs-with-vue-router
  get tabs(): Dictionary<Dictionary<string | number>> {
    return {
      tasks: {
        icon: "mdi-format-list-checkbox",
      },
      calendar: {
        icon: "mdi-calendar",
      },
      timeline: {
        icon: "mdi-chart-timeline",
      },
      stats: {
        icon: "mdi-chart-box-outline",
      },
      settings: {
        icon: "mdi-tune",
        count: this.pendingCount,
      },
    };
  }

  async created(): Promise<void> {
    try {
      groupModule.selectGroup(this.groupId);
    } catch (err) {
      this.$toast.error(err.response.data.message);
    }
  }
}
</script>
