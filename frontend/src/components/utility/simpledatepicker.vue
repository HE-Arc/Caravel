<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    :nudge-right="40"
    transition="scale-transition"
    offset-y
    min-width="auto"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        prepend-inner-icon="mdi-calendar"
        v-bind="$attrs"
        hint="YYYY-MM-DD format"
        :rules="dateRules"
        persistent-hint
        v-on="on"
        v-model="date"
      ></v-text-field>
    </template>
    <v-date-picker
      :max="max"
      v-model="date"
      @input="menu = false"
    ></v-date-picker>
  </v-menu>
</template>

<script lang="ts">
import moment from "moment";
import { Vue, Component, Prop } from "vue-property-decorator";

@Component
export default class SimpleDatePicker extends Vue {
  @Prop({ default: null }) max!: string | null;
  @Prop({ default: moment().toISOString().substr(0, 10) }) value!: string;
  menu = false;
  dateRules = [
    (v) =>
      /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/.test(v) ||
      "Invalid date",
  ];

  get toDayDate(): string {
    return moment().toISOString().substr(0, 10);
  }

  get date(): string {
    return this.value;
  }

  set date(value: string) {
    this.$emit("input", value);
  }
}
</script>
