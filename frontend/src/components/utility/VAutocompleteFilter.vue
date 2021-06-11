<template>
  <v-autocomplete
    v-bind="$attrs"
    v-on="$listeners"
    :items="items"
    :label="label"
    :search-input.sync="searchText"
  >
    <template v-for="(_, slot) of $scopedSlots" v-slot:[slot]="scope">
      <slot :name="slot" v-bind="scope" />
    </template>

    <template #prepend-item>
      <v-list-item v-if="canCreate" @click="create">
        <v-list-item-content>
          <v-list-item-title
            v-html="$t('autocomplete.create', [labelLower, searchText])"
          >
            <v-icon dense color="secondary darken-1">mdi-plus</v-icon>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
    <template #no-data>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>{{ $t("autocomplete.nodata") }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
    <template v-slot:append-item v-if="manager && label">
      <v-divider />
      <v-list-item :to="manager" class="mt-2">
        <v-list-item-icon>
          <v-icon color="secondary darken-1">mdi-hammer-wrench</v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
            {{ $t("autocomplete.manage", [labelLower]) }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-autocomplete>
</template>

<script lang="ts">
import { Dictionary } from "@/types/helpers";
import { Component, Vue, Prop, Emit } from "vue-property-decorator";
import { RawLocation } from "vue-router";

@Component
export default class VAutocompleteFilter extends Vue {
  @Prop({ default: null }) label!: string | null;
  @Prop() manager!: RawLocation;
  @Prop({ default: "" }) manageName!: string;
  @Prop() items!: Dictionary<string | number | unknown>[];
  @Prop({ default: true }) checkDuplicate!: boolean;
  searchText: string | null = null;

  get canCreate(): boolean {
    return (
      !(this.searchText == null || this.searchText == "") &&
      ((this.items &&
        !this.items.some((item) => item.text == this.searchText)) ||
        !this.checkDuplicate)
    );
  }

  get labelLower(): string {
    return this.label ? this.label.toLowerCase() : "";
  }

  @Emit()
  create(): string {
    return this.searchText ?? "";
  }
}
</script>
