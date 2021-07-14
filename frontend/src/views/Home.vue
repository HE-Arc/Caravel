<template>
  <v-container class="mt-4">
    <v-row v-if="!isLoaded">
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
    <v-row v-else-if="hasGroups">
      <v-col cols="12">
        <div class="text-h4 font-weight-light">{{ $t("groups.mygroups") }}</div>
      </v-col>
      <v-col cols="12">
        <paginate :items="groups" :perPage="5">
          <template #default="{ items }">
            <group-item
              v-for="group in items"
              :group="group"
              :key="group.id"
              :hasJoin="false"
              class="mt-3"
            />
          </template>
        </paginate>
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
import { Vue, Component } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";
import { Group } from "@/types/group";
import GroupItem from "@/components/GroupItem.vue";
import Paginate from "@/components/utility/Paginate.vue";

@Component({
  components: {
    GroupItem,
    Paginate,
  },
})
export default class Home extends Vue {
  get groups(): Group[] {
    return groupModule.groups;
  }

  get hasGroups(): boolean {
    return this.groups.length > 0;
  }

  get isLoaded(): boolean {
    return groupModule.status == "loaded";
  }
}
</script>
