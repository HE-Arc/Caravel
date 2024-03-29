<script lang="ts">
import { Component, Prop, Mixins } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";
import taskModule from "@/store/modules/tasks";
import { Group } from "@/types/group";
import moment from "moment";
import GroupStat from "@/types/GroupStat";
import { Line, mixins } from "vue-chartjs-typescript";

@Component({
  extends: Line,
  mixins: [mixins.reactiveProp],
})
export default class GroupLineChart extends Mixins(mixins.reactiveProp, Line) {
  @Prop({ default: undefined }) showNext: number | undefined;
  @Prop({ default: true }) fromNow!: boolean;
  @Prop({ default: undefined }) fontColor!: string;

  get group(): Group | undefined {
    return groupModule.group;
  }

  get currentValue(): number {
    return taskModule.currentWeekScore ?? 0;
  }

  get stats(): GroupStat[] {
    if (!taskModule.stats) return [];
    return taskModule.stats
      .filter(
        (item) =>
          !this.fromNow ||
          moment().startOf("isoWeek").isSameOrBefore(moment(item.create_at))
      )
      .slice(0, this.showNext);
  }

  get values(): number[] {
    return this.stats.map((item) => item.wes);
  }

  get labels(): string[] {
    return this.stats.map((item) => {
      const date = moment(item.create_at);
      return this.$t("stats.week-of") + date.format("D MMM YY");
    });
  }

  get hasValues(): boolean {
    return this.values.length > 1;
  }

  get avg(): number {
    return taskModule.medianWeekScore;
  }

  public renderChart!: (chartData: unknown, options?: unknown) => void;

  mounted(): void {
    this.renderChart(
      {
        labels: this.labels,
        datasets: [
          {
            label: this.$t("stats.wes"),
            backgroundColor: "#9feda3",
            data: this.values,
          },
        ],
      },
      {
        tooltips: {
          mode: "index",
          intersect: false,
        },
        hover: {
          mode: "index",
          intersect: false,
        },
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [
            {
              gridLines: {
                drawOnChartArea: false,
              },
            },
          ],
          yAxes: [
            {
              gridLines: {
                drawOnChartArea: false,
              },
            },
          ],
        },
      }
    );
  }
}
</script>
