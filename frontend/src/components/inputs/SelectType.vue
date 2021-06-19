<template>
  <v-select
    :items="types"
    :label="$t('task.form.type.label')"
    :placeholder="$t('task.form.type.placeholder')"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-slot:item="{ item }">
      <v-icon v-text="item.icon" class="mr-3" />
      {{ item.text }}
    </template>
    <template v-slot:selection="{ item }">
      <v-icon v-text="item.icon" class="mr-3" small />
      {{ item.text }}
    </template>
  </v-select>
</template>

<script lang="ts">
import { Dictionary, TaskType } from "@/types/helpers";
import { Component, Vue } from "vue-property-decorator";

@Component
export default class SelectType extends Vue {
  get types(): Dictionary<string | number>[] {
    return Object.values(TaskType)
      .filter((key) => Number.isInteger(key))
      .map((key) => ({
        value: key.toString(),
        text: this.$tc(`task.types.${key}.label`, 0).toString(),
        icon: this.$t(`task.types.${key}.icon`).toString(),
      }));
  }
}
</script>
