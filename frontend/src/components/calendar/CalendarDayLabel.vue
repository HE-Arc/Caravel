<template>
  <div>
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <span
          :class="`indicator pattern-${category}`"
          :style="color"
          v-bind="attrs"
          v-on="on"
        ></span>
      </template>
      <span>{{ $t("stats.calendar-wes", [weekScore]) }}</span>
    </v-tooltip>
    <v-tooltip bottom>
      <template v-slot:activator="{ on, attrs }">
        <v-btn
          elevation="0"
          :color="isSelected ? 'primary' : 'transparent'"
          fab
          rounded
          small
          :to="{ name: 'newTask', query: { start_at: props.date } }"
          v-bind="attrs"
          v-on="on"
        >
          {{ dayLabel }}
        </v-btn>
      </template>
      <span>{{ $t("task.create") }}</span>
    </v-tooltip>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import taskModule from "@/store/modules/tasks";
import { Dictionary } from "@/types/helpers";
import moment from "moment";
import interpolate from "color-interpolate";

@Component
export default class CalendarDayLabel extends Vue {
  @Prop() props!: Dictionary<string | number | boolean>;
  gradients = ["#60f720", "#ffd200", "#f72047"];

  get weekScore(): number {
    const start = moment(this.props.date.toString()).startOf("isoWeek");

    const stat = taskModule.stats?.find((item) =>
      start.isSame(moment(item.create_at), "date")
    );

    return stat?.wes ?? 0;
  }

  get isSelected(): boolean {
    return moment(this.props.date.toString()).isSame(moment(), "date");
  }

  get category(): number {
    const median = taskModule.medianWeekScore;
    const sub = median - this.weekScore;

    if (sub > 0) {
      return 1;
    } else if (sub == 0) {
      return 2;
    } else {
      return 3;
    }
  }

  get color(): string {
    const colormap = interpolate(this.gradients);
    const score = this.weekScore / taskModule.medianWeekScore - 0.5;
    const color = colormap(score);
    return "background-color:" + color + ";";
  }

  get dayLabel(): string {
    const date = moment(this.props.date.toString());

    return date.date() == 1 ? date.format("D MMM") : date.format("D");
  }
}
</script>

<style lang="scss" scoped>
@import "@/styles/caravel.scss";

.indicator {
  position: absolute;
  left: 0;
  width: 15px;
  height: 40px;
  top: 0;
  right: 0;
}
</style>
