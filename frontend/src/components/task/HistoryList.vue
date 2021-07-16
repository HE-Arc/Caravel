<template>
  <span class="text-center" v-if="hasHistories">
    <v-menu offset-y max-height="600">
      <template v-slot:activator="{ on, attrs }">
        <span v-bind="attrs" v-on="on">
          {{ $t("history.label") }}
          <v-icon>mdi-menu-down</v-icon>
        </span>
      </template>
      <v-card class="mx-auto" width="400">
        <v-card-text class="py-0">
          <v-timeline dense align-top>
            <history-item
              v-for="history in histories"
              :key="history.id"
              :history="history"
            />
          </v-timeline>
        </v-card-text>
      </v-card>
    </v-menu>
  </span>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import HistoryItem from "@/components/task/HistoryItem.vue";

@Component({
  components: {
    HistoryItem,
  },
})
export default class HistoryList extends Vue {
  @Prop() histories!: History[];
  @Prop({ default: true }) separator!: boolean;

  get hasHistories(): boolean {
    return this.histories.length > 0;
  }
}
</script>
