<template>
  <v-timeline-item small :color="color">
    <v-row class="pt-1">
      <v-col cols="3" class="text-center">
        <strong>
          {{ perfTimeAt }}
        </strong>
        <div class="text-caption">
          {{ perfDateAt }}
        </div>
      </v-col>
      <v-col>
        <strong>{{ $t(`history.states.${history.message}`) }}</strong>
        <div class="text-caption" v-if="author">
          <v-avatar color="primary" class="profile" size="25">
            <v-img v-if="author.picture" :src="author.picture"></v-img>
          </v-avatar>
          {{ author.name }}
        </div>
        <div class="mt-1"></div>
        <div class="text-caption" v-for="meta in history.meta" :key="meta.key">
          {{ $t("history.change") }}
          {{ $t(`task.fields.${meta.key}`) }}
        </div>
      </v-col>
    </v-row>
  </v-timeline-item>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import memberModule from "@/store/modules/members";
import { Member } from "@/types/member";
import History from "@/types/History";
import moment from "moment";

@Component
export default class HistoryItem extends Vue {
  @Prop() history!: History;

  get author(): Member | undefined {
    return this.history && this.history.user_id
      ? memberModule.getMember(this.history.user_id.toString())
      : undefined;
  }

  get perfTimeAt(): string | undefined {
    return this.history
      ? moment(this.history?.performed_at).format("LT")
      : undefined;
  }

  get perfDateAt(): string | undefined {
    return this.history
      ? moment(this.history?.performed_at).format("Do MMMM")
      : undefined;
  }

  get isCreation(): boolean {
    return this.history && this.history.meta == null;
  }

  get color(): string {
    return this.isCreation ? "warning" : "primary";
  }
}
</script>
