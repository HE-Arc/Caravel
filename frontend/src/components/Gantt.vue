<template>
  <div>
    <v-card-text>
      <v-slider
        v-model="zoom"
        min="18"
        max="25"
        step="1"
        ticks="always"
        tick-size="4"
        @change="updateZoom"
        :label="$t('timeline.zoom')"
      ></v-slider>
    </v-card-text>
    <div class="gstc-wrapper" ref="gstc"></div>
  </div>
</template>

<script lang="ts">
import { Ref, Vue } from "vue-property-decorator";
import Component from "vue-class-component";
import GSTC, {
  GSTCResult,
  Rows,
  Items,
} from "gantt-schedule-timeline-calendar";
import { Plugin as TimelinePointer } from "gantt-schedule-timeline-calendar/dist/plugins/timeline-pointer.esm.min.js";
import { Plugin as CalendarScroll } from "gantt-schedule-timeline-calendar/dist/plugins/calendar-scroll.esm.min.js";
import State from "gantt-schedule-timeline-calendar/node_modules/deep-state-observer";
import "gantt-schedule-timeline-calendar/dist/style.css";
import { TaskType } from "@/types/helpers";
import frch from "gantt-schedule-timeline-calendar/node_modules/dayjs/locale/fr-ch";
import { Task } from "@/types/task";
import taskModule from "@/store/modules/tasks";
import subjectModule from "@/store/modules/subjects";
import { Subject } from "@/types/subject";
import TinyColor from "tinycolor2";

@Component
export default class Gantt extends Vue {
  @Ref("gstc") wrapper!: HTMLElement;
  gstc!: GSTCResult;
  state!: State;
  zoom = 23;

  get tasks(): Task[] {
    return taskModule.tasksFuture;
  }

  get subjects(): Set<Subject> {
    return new Set(
      this.tasks
        .map((item) => subjectModule.getSubject(item.subject_id))
        .filter(Boolean)
    ) as Set<Subject>;
  }

  get generateRows(): Rows {
    const rows = {};
    if (this.tasks.length == 0) return rows;

    Array.from(this.subjects).forEach((item) => {
      const id = GSTC.api.GSTCID(item.id);
      rows[id] = {
        id: item.id,
        label: item.name,
        subject: item,
      };
    });

    return rows;
  }

  get generateItems(): Items {
    const items = {};
    if (this.tasks.length == 0) return items;

    this.tasks.forEach((task) => {
      const id = GSTC.api.GSTCID(task.id);
      const rowId = GSTC.api.GSTCID(task.subject_id);
      const subject = subjectModule.getSubject(task.subject_id);
      const color = subject ? subject.color : "#efefef";
      const textColor = this.isTextDark(color);

      items[id] = {
        id,
        label: task.title,
        rowId,
        type: task.tasktype_id,
        task_id: task.id,
        style: {
          "background-color": color,
          color: textColor,
        },
        classNames: ["item-clickable"],
        time: {
          start: GSTC.api
            .date(
              task.tasktype_id == TaskType.PROJECT.toString()
                ? task.start_at
                : task.due_at
            )
            .startOf("day")
            .valueOf(),
          end: GSTC.api.date(task.due_at).endOf("day").valueOf(),
        },
      };
    });

    return items;
  }

  mounted(): void {
    const label = this.$tc("subject.label", this.subjects.size);
    const config = {
      licenseKey:
        "====BEGIN LICENSE KEY====\nkKGnTfBkOCgV00pm13Vabkb/bcfVNAoD0FrXNS635ifpJqX/5rTTayzbheZ7npsRUZyIJ5UyFDXbNfDIlJ4eXUE9UUfVmBWPxXxxW8fEp/03qobtNcFwHbihYZDJyLSRvFCXasmNptQMa9CKS2PmMIAZmZ5XPj1ryi9v29ckZIsYhMJXpYUsH+YctfbyV2Jl8tg4l83xcaSYdKoEh6JiZkaoXJ97ko6BfdC3Gig8wG71AGbnqTllnKQQod63h0w3Gx806bCp5AsSlkq2Ey/WvHySh9pGKtJMEwcU1w/QwYcj0SjMlM5PBhXI6j8SQ28/ctbE/mzCcTnBypj/jLqhWQ==||U2FsdGVkX19vvIAkay65lgC/zNBsd/W0fGC6EO7VmCV1iPIZ3VwvjndlINzfyqsg4M1NahoqQ0cLXLKtsBJUgTBdj788jdMpvT4+YHYtQSg=\nO8B0mG0CaVhDFHESjjVTzIKoPahj4RgYqZBmZwjiJxpWe5JQH0hIOHys28UgdnM03Jmh7yURSpuWfqbGyG2ZfpoitCJji6rz/w4VRiaSnYrtPf8U44X/U6G9u0ph/VypuVCh6Yc8/QYrmhnBO11olfBRsNYvWQU80oWDyK6MxfOKcklBEeWfPCeR+LpvsQYZgRJOD/uGcldO/qA5niok4B4RfByd3DGPcXyElGcjfb6uAK9atW7/CwLEOvu5x03IxtXgkEdWdQSOvFX7aZiqMXiIn+yPXjowC0bidrxrqglV8E9Ph7xDRMVCSwUSbhPjlGb4IFxjH9RJSCxKb34s4A==\n====END LICENSE KEY====",
      plugins: [TimelinePointer(), CalendarScroll()],
      locale: frch,
      list: {
        columns: {
          data: {
            [GSTC.api.GSTCID("label")]: {
              id: GSTC.api.GSTCID("label"),
              width: 200,
              data: "label",
              header: {
                content: label,
              },
            },
          },
        },
        rows: this.generateRows,
      },
      scroll: {
        horizontal: {
          precise: false,
        },
      },
      chart: {
        items: this.generateItems,
        grid: {},
      },
      slots: {
        // item content slot that will show circle with letter next to item label
        "chart-timeline-items-row-item": {
          content: [
            (vido, props) => {
              const { onChange, html } = vido;
              let icon = this.$t(
                `task.types.${props.item.type}.icon`
              ).toString();
              onChange((newProps) => {
                if (newProps.item) {
                  props = newProps;
                  icon = this.$t(
                    `task.types.${props.item.type}.icon`
                  ).toString();
                }
              });

              return (content) =>
                html`<div
                    class="item-clickable"
                    style="width:24px;height:24px;background:${props.item
                      .imgColor};border-radius:100%;text-align:center;line-height:24px;font-weight:bold;margin-right:10px;"
                  >
                    <i class="v-icon notranslate small mdi ${icon}"></i>
                  </div>
                  ${content}`;
            },
          ],
        },
      },
      actions: {
        "chart-timeline-items-row-item": [
          (element, data) => {
            element.addEventListener("click", () => {
              this.$router.push({
                name: "taskDisplay",
                params: { task_id: data.item.task_id },
              });
            });
          },
        ],
      },
    };

    this.state = GSTC.api.stateFromConfig(config);
    this.state.update("config.chart.time.zoom", this.zoom);

    const state = this.state;

    this.gstc = GSTC({
      element: this.wrapper,
      state,
    });
  }

  updateZoom(): void {
    this.state.update("config.chart.time.zoom", this.zoom);
  }

  isTextDark(subColor: string): boolean {
    const color = new TinyColor(subColor);
    return color.getLuminance() < 0.228;
  }
}
</script>

<style lang="scss">
.gstc-component {
  margin: 0;
  padding: 0;
}
.toolbox {
  padding: 10px;
}

.item-clickable {
  cursor: pointer;
  :hover {
    opacity: 0.8;
  }
}
</style>
