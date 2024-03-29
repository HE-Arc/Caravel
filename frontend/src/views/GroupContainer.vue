<template>
  <div>
    <v-container fluid v-show="isLoaded">
      <v-row align="center" v-if="isLoaded">
        <v-col class="mb-5 mt-4" cols="">
          <v-row align="center">
            <group-selector class="ml-2" />
            <v-spacer></v-spacer>
            <group-indicator class="mr-5 hidden-sm-and-down" :width="150" />
          </v-row>
        </v-col>
        <v-col class="pa-0" cols="12">
          <v-tabs v-model="activeTab" show-arrows class="main-tab">
            <v-tab
              v-for="(item, key, index) in tabs"
              :key="key + (item.count != undefined ? item.count : '')"
              :to="$router.resolve({ name: key }).href"
              v-bind:class="{ 'first-tab': index == 0 }"
            >
              <v-icon class="mr-2">{{ item.icon }}</v-icon>
              <span class="hidden-sm-and-down">
                {{ $t("group.tabs." + key) }}
              </span>

              <v-chip
                class="ml-1"
                small
                :color="item.color"
                v-if="item.count != undefined && item.count > 0"
                v-text="item.count"
              />
            </v-tab>
          </v-tabs>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" v-show="isLoaded">
          <router-view />
        </v-col>
        <v-col cols="12">
          <div class="text-center" v-show="!isLoaded">
            <v-progress-circular
              :size="70"
              :width="7"
              color="primary"
              indeterminate
              class="mt-5"
            ></v-progress-circular>
          </div>
        </v-col>
      </v-row>
    </v-container>
    <div class="text-center" v-show="!isLoaded">
      <v-progress-circular
        :size="70"
        :width="7"
        color="primary"
        indeterminate
        class="mt-5"
      ></v-progress-circular>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import GroupSelector from "@/components/group/GroupSelector.vue";
import groupModule from "@/store/modules/groups";
import taskModule from "@/store/modules/tasks";
import memberModule from "@/store/modules/members";
import { Group } from "@/types/group";
import { Dictionary } from "@/types/helpers";
import GroupIndicator from "@/components/group/GroupIndicator.vue";

@Component({
  components: {
    GroupSelector,
    GroupIndicator,
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

  get tasksCount(): number {
    return taskModule.tasksFuture.length ?? 0;
  }

  get isGroupLoaded(): boolean {
    return groupModule.status == "loaded";
  }

  get isTasksLoaded(): boolean {
    return taskModule.status == "loaded";
  }

  get isLoaded(): boolean {
    return this.isGroupLoaded && this.isTasksLoaded;
  }

  // https://stackoverflow.com/questions/49721710/how-to-use-vuetify-tabs-with-vue-router
  get tabs(): Dictionary<Dictionary<string | number>> {
    return {
      tasks: {
        icon: this.$t("task.icon").toString(),
        count: this.tasksCount,
        color: "",
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
        color: "error",
      },
    };
  }

  async created(): Promise<void> {
    try {
      await groupModule.selectGroup(this.groupId);
    } catch (err) {
      if (err.response.status == 404) {
        this.$router.replace({ name: "NotFound" });
      } else {
        this.$toast.error(err.response.data.message);
      }
    }
  }
}
</script>

<style lang="scss">
.main-tab {
  border-bottom: 1px solid rgba(151, 151, 151, 0.411);
  & .first-tab {
    margin-left: 20px;
  }
}
</style>
