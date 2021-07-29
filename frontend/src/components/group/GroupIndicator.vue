<template>
  <v-card flat :width="width" v-if="hasValues">
    <v-sheet class="text-center px-5">
      <v-tooltip bottom>
        <template v-slot:activator="{ on, attrs }">
          <span class="text-h5 font-weight-black" v-bind="attrs" v-on="on">
            {{ currentValue }}
            <strong class="text-caption font-weight-thin">{{
              $t("stats.unit")
            }}</strong>
          </span>
        </template>
        <span>{{ $t("stats.wes-current") }}</span>
      </v-tooltip>

      <router-link :to="{ name: 'stats' }">
        <v-sparkline
          :key="String(avg)"
          :smooth="16"
          :gradient="['#f72047', '#ffd200', '#1feaea']"
          :value="values"
          auto-draw
          auto-line-width
          stroke-linecap="round"
          v-bind="$attrs"
          v-on="$listeners"
        ></v-sparkline>
      </router-link>
    </v-sheet>
  </v-card>
</template>

<script lang="ts">
import { Vue, Component, Prop } from "vue-property-decorator";
import groupModule from "@/store/modules/groups";
import taskModule from "@/store/modules/tasks";
import { Group } from "@/types/group";
import moment from "moment";
import GroupStat from "@/types/GroupStat";

@Component
export default class GroupIndicator extends Vue {
  @Prop({ default: null }) width!: string | null;
  @Prop({ default: 5 }) showNext!: number;

  get group(): Group | undefined {
    return groupModule.group;
  }

  get currentValue(): number {
    return taskModule.currentWeekScore ?? 0;
  }

  get stats(): GroupStat[] {
    if (!taskModule.stats) return [];
    return taskModule.stats
      .filter((item) =>
        moment().startOf("isoWeek").isSameOrBefore(moment(item.create_at))
      )
      .slice(0, this.showNext);
  }

  get values(): number[] {
    return this.stats.map((item) => item.wes);
  }

  get labels(): string[] {
    return this.stats.map((item) => moment(item.create_at).format("D MMM"));
  }

  get hasValues(): boolean {
    return this.values.length > 0;
  }

  get avg(): number {
    return taskModule.medianWeekScore;
  }
}
</script>
