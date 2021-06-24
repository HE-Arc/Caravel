<template>
  <v-container>
    <v-row>
      <v-col v-for="(group, i) in groups" :key="i" cols="12" sm="4" lg="3">
        <group-card :group="group" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import groupModule from "@/store/modules/groups";
import { Group } from "@/types/Group";
import GroupCard from "@/components/GroupCard.vue";

@Component({
  components: {
    GroupCard,
  },
})
export default class Home extends Vue {
  get groups(): Group[] {
    return groupModule.groups;
  }
}
</script>
