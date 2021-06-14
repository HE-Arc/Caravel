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
        readonly
        v-bind="$attrs"
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
