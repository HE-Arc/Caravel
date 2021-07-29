<template>
  <v-autocomplete
    dense
    :items="items"
    solo
    clearable
    v-model="filter"
    v-bind="$attrs"
    v-on="$listeners"
  >
    <template v-for="(index, name) in $scopedSlots" v-slot:[name]="data">
      <slot :name="name" v-bind="data"></slot>
    </template>
  </v-autocomplete>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue, Prop, VModel } from "vue-property-decorator";

@Component
export default class FilterList extends Vue {
  @VModel({ type: String }) filter!: string;
  @Prop() items!: Dictionary<Dictionary<string>>;
  @Prop() name!: string;
}
</script>
