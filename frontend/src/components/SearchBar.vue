<template>
  <v-text-field
    :label="$t('search.label')"
    prepend-inner-icon="mdi-magnify"
    dense
    hide-details="true"
    clearable
    solo-inverted
    flat
    color="#5e72e4"
    background-color="#5e72e422"
    class="elevation-0 search-bar"
  ></v-text-field>
</template>

<script lang="ts">
import { Prop, Component, Vue } from "vue-property-decorator";

@Component
export default class SearchBar extends Vue {
  @Prop({ default: false }) hasFilter?: boolean;
}
</script>

<style lang="scss" scoped>
.search-bar::v-deep {
  .v-icon,
  .v-text-field__slot .v-label,
  input {
    color: #5e72e4 !important;
  }
}
</style>