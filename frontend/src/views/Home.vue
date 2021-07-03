<template>
  <v-container class="mt-4">
    <v-row v-if="hasGroups">
      <v-col cols="12">
        <div class="text-h4 font-weight-light">{{ $t("groups.mygroups") }}</div>
      </v-col>
      <v-col v-for="(group, i) in groups" :key="i" cols="12">
        <group-item :group="group" :hasJoin="false" />
      </v-col>
    </v-row>
    <v-row v-else>
      <v-col cols="12" class="text-center" offset-md="3" md="6">
        <v-card flat>
          <v-card-title class="justify-center text-h3 font-weight-thin my-6">
            {{ $t("home.title") }}
          </v-card-title>
          <v-card-text>
            <p class="text-justify">
              {{ $t("home.message") }}
            </p>
            <p>
              {{ $t("home.submessage") }}
            </p>
          </v-card-text>
          <v-card-actions class="justify-center">
            <v-btn text color="primary" :to="{ name: 'GroupSearch' }">{{
              $t("groups.search-title")
            }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import userModule from "@/store/modules/user";
import groupModule from "@/store/modules/groups";
import { Group } from "@/types/group";
import GroupCard from "@/components/GroupCard.vue";
import GroupItem from "@/components/GroupItem.vue";

@Component({
  components: {
    GroupCard,
    GroupItem,
  },
})
export default class Home extends Vue {
  e1 = 1;

  get groups(): Group[] {
    if (!userModule.user) return [];
    return groupModule.groups;
  }

  get hasGroups(): boolean {
    return this.groups.length > 0;
  }
}
</script>
