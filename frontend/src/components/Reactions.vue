<template>
  <v-row>
    <v-col>
      <span>
        <v-tooltip
          bottom
          v-for="reaction in reactions"
          :key="`dense-${reaction.type}`"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-chip
              :outlined="!reaction.hasReact"
              @click="react(reaction.type)"
              class="mr-2"
              :color="reaction.hasReact ? 'primary' : 'default'"
              v-bind="attrs"
              v-on="on"
            >
              {{ $t(`reactions.types.${reaction.type}.icon`) }}
              {{ reaction.count }}
            </v-chip>
          </template>
          <span>{{ $t(`reactions.types.${reaction.type}.label`) }}</span>
        </v-tooltip>
      </span>

      <v-menu offset-y :close-on-content-click="false">
        <template v-slot:activator="{ on, attrs }">
          <v-chip outlined v-bind="attrs" v-on="on">
            <v-icon size="28">mdi-dots-horizontal</v-icon>
          </v-chip>
        </template>
        <v-card elevation="16" class="mx-auto">
          <v-card-text>
            <reaction-item
              v-for="i in 6"
              :key="`full-${i}`"
              :type="i"
              @click="react(i)"
              :isActive="isIncluded(i)"
            ></reaction-item>
          </v-card-text>
        </v-card>
      </v-menu>
    </v-col>
  </v-row>
</template>

<script lang="ts">
import { Component, Vue, Prop } from "vue-property-decorator";
import Reaction from "@/types/Reaction";
import { Task } from "@/types/task";
import ReactionItem from "@/components/task/ReactionItem.vue";
import taskModule from "@/store/modules/tasks";

@Component({
  components: {
    ReactionItem,
  },
})
export default class Reactions extends Vue {
  @Prop() task!: Task;
  @Prop({ default: false }) dense!: boolean;

  get reactions(): Reaction[] {
    return this.task.reactions_list;
  }

  get activeReactions(): number[] {
    return this.reactions
      .filter((item) => item.hasReact)
      .map((item) => item.type);
  }

  isIncluded(type: number): boolean {
    return this.activeReactions.includes(type);
  }

  react(type: number): void {
    const taskId: string = this.task.id;
    taskModule.postReaction({ taskId, type });
  }
}
</script>
