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
import userModule from "@/store/modules/user";
import { Group } from "@/types/group";
import GroupCard from "@/components/GroupCard.vue";

@Component({
  components: {
    GroupCard,
  },
})
export default class Home extends Vue {
  get groups(): Group[] {
    if (!userModule.user) return [];
    return userModule.user?.groups_available;
  }
}
</script>
