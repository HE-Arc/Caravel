<template>
  <v-container v-if="isLoaded">
    <v-row>
      <v-col cols="12" md="8">
        <v-card class="mx-auto text-center">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.trend-next") }}</v-card-title
          >
          <v-card-text class="px-5">
            <group-line-chart />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="4">
        <v-card class="mx-auto text-center mb-5">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.score.current") }}</v-card-title
          >
          <v-card-text class="text-h2 px-5"> {{ currentScore }} </v-card-text>
        </v-card>
        <v-card class="mx-auto text-center mb-5">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.score.median") }}</v-card-title
          >
          <v-card-text class="text-h2 px-5"> {{ medianScore }} </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="8">
        <v-card class="mx-auto text-center" color="default">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.trend-all") }}</v-card-title
          >
          <v-card-text class="px-5">
            <group-line-chart :fromNow="false" :showNext="undefined" />
          </v-card-text>
        </v-card>
      </v-col>
      <v-col cols="12" md="4">
        <v-card class="mx-auto text-center mb-5">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.score.max") }}</v-card-title
          >
          <v-card-text class="text-h2 px-5"> {{ maxScore }} </v-card-text>
        </v-card>
        <v-card class="mx-auto text-center mb-5">
          <v-card-title
            class="text-h4 font-weight-thin justify-center"
            color="rgba(0, 0, 0, .12)"
            >{{ $t("stats.score.min") }}</v-card-title
          >
          <v-card-text class="text-h2 px-5"> {{ minScore }} </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import GroupLineChart from "@/components/group/GroupLineChart.vue";
import taskModule from "@/store/modules/tasks";

@Component({
  components: {
    GroupLineChart,
  },
})
export default class GroupStats extends Vue {
  get isLoaded(): boolean {
    return taskModule.status == "loaded";
  }

  get medianScore(): number {
    return taskModule.medianWeekScore;
  }

  get minScore(): number {
    return taskModule.minMaxWeekScore[0];
  }

  get currentScore(): number {
    return taskModule.currentWeekScore ?? 0;
  }

  get maxScore(): number {
    return taskModule.minMaxWeekScore[1];
  }
}
</script>
