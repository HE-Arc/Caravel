<template>
  <div>
    <v-menu offset-y min-width="100px">
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </template>
      <v-list>
        <v-list-item v-for="(item, index) in items" :key="index">
          <v-btn depressed rounded text :to="item.to">{{ item.title }}</v-btn>
        </v-list-item>
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";
import { Dictionary } from "@/types/helpers";

@Component
export default class AddContent extends Vue {
  get items(): Dictionary<string>[] {
    return [
      {
        title: "Group",
        to: this.$router.resolve({ name: "GroupSearch" }).href,
      },
      {
        title: "Task",
        to: this.$router.resolve({
          name: "newTask",
          params: { group_id: groupModule.selectedId },
        }).href,
      },
      {
        title: "Subject",
        to: this.$router.resolve({ name: "GroupSearch" }).href,
      },
    ];
  }
}
</script>
