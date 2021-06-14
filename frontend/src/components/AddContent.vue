<template>
  <div>
    <v-menu offset-y>
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on">
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </template>
      <v-list>
        <template v-for="(item, index) in items">
          <v-list-item
            :key="index"
            @click="follow(item.to)"
            v-if="!item.needGroup || hasGroupRoute"
          >
            <v-list-item-icon>
              <v-icon>{{ $t(`${item.key}.icon`) }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title class="ml-3 pr-3">
              {{ $tc(`${item.key}.label`, 1) }}
            </v-list-item-title>
          </v-list-item>
        </template>
      </v-list>
    </v-menu>
  </div>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
import { Dictionary } from "@/types/helpers";

@Component
export default class AddContent extends Vue {
  get items(): Dictionary<string | boolean>[] {
    return [
      {
        key: "group",
        to: "GroupSearch",
        needGroup: false,
      },
      {
        key: "task",
        to: "newTask",
        needGroup: true,
      },
      {
        key: "subject",
        to: "subjects",
        needGroup: true,
      },
    ];
  }

  get hasGroupRoute(): boolean {
    return this.$route.params.group_id != undefined;
  }

  follow(name: string): void {
    this.$router.push({ name: name });
  }
}
</script>
