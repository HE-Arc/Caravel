<template>
  <v-text-field
    :label="$t('groups.search-title')"
    prepend-inner-icon="mdi-magnify"
    dense
    hide-details="true"
    clearable
    solo-inverted
    flat
    color="#5e72e4"
    background-color="#5e72e422"
    class="elevation-0 search-bar"
    autocomplete="off"
    @keydown.enter="search"
    v-model="text"
  ></v-text-field>
</template>

<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
@Component
export default class QuickSearch extends Vue {
  text = "";

  search(): void {
    this.$router.push({ name: "GroupSearch", query: { text: this.text } });
    this.text = "";
  }
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
