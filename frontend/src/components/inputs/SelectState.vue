<template>
  <v-select
    :items="items"
    :label="$t('inputs.selectstate.label')"
    :placeholder="$t('inputs.selectstate.placeholder')"
    v-bind="$attrs"
    v-on="$listeners"
  >
  </v-select>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue } from "vue-property-decorator";

@Component
export default class SelectState extends Vue {
  get items(): Dictionary<string>[] {
    return [
      {
        value: "1",
        text: this.$t("task.states.open").toString(),
      },
      {
        value: "0",
        text: this.$t("task.states.close").toString(),
      },
    ];
  }
}
</script>
