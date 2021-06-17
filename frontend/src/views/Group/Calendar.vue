<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-sheet tile height="54" class="d-flex">
          <v-btn icon class="mr-2" @click="$refs.calendar.prev()"
            ><v-icon>mdi-chevron-left</v-icon>
          </v-btn>
          <v-menu bottom right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                <span>{{ $t(`calendar.type.${type}`) }}</span>
                <v-icon right>mdi-menu-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item
                v-for="(key, value) in typeToLabel"
                @click="type = value"
                :key="key"
              >
                <v-list-item-title>{{
                  $t(`calendar.type.${value}`)
                }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
          <v-spacer></v-spacer>
          <span class="text-h4 font-weight-light" v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </span>
          <v-spacer></v-spacer>
          <v-btn class="ml-2" icon @click="$refs.calendar.next()"
            ><v-icon>mdi-chevron-right</v-icon>
          </v-btn>
        </v-sheet>
        <v-sheet :height="type == 'month' ? '800' : undefined">
          <v-calendar
            :weekdays="weekdays"
            v-model="focus"
            :type="type"
            :events="events"
            ref="calendar"
            :interval-count="0"
            @click:more="viewDay"
            @click:event="showEvent"
            @click:date="viewDay"
          ></v-calendar>
        </v-sheet>
      </v-col>
    </v-row>
  </v-container>
</template>

<script lang="ts">
import Vue from "vue";
import Component from "vue-class-component";
import moment, { Moment } from "moment";
import taskModule from "@/store/modules/tasks";
import subjectModule from "@/store/modules/subjects";
import EventTask from "@/types/eventTask";
import { TaskType } from "@/types/helpers";

@Component({
  components: {},
})
export default class GroupCalendar extends Vue {
  focus = moment().calendar();
  weekdays = [6, 0, 1, 2, 3, 4, 5];
  type = "month";
  typeToLabel = {
    month: "Month",
    week: "Week",
    day: "Day",
  };

  get date(): Moment {
    return moment(`${this.focus}T00:00:00`);
  }

  get events(): EventTask[] {
    let events: EventTask[] = [];

    taskModule.tasks.forEach((item) =>
      events.push({
        name: item.title,
        start: moment(item.start_at).toDate(),
        end:
          item.tasktype_id == TaskType.PROJECT.toString()
            ? moment(item.due_at).toDate()
            : undefined,
        color: subjectModule.getSubject(item.subject_id)?.color,
        timed: false,
        id: item.id,
      })
    );

    return events;
  }

  viewDay({ date }: { date: string }): void {
    this.focus = date;
    this.type = "day";
  }

  showEvent({ event }: { event: EventTask }): void {
    this.$router.push({ name: "taskDisplay", params: { task_id: event.id } });
  }

  mounted(): void {
    // force update as title calendar doesnt appear otherwise
    this.$forceUpdate();
  }
}
</script>
