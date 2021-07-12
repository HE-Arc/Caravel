import { Data } from "./data";
import GroupStat from "./GroupStat";

export interface Group extends Data {
  name: string;
  description: string;
  picture: string | undefined;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
  stats: GroupStat[];
  metadata: {
    members: number;
    subjects: number;
    tasks: number;
    wes: {
      current: number;
      min: number;
      max: number;
      median: number;
    }
  }
}
