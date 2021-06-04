import { Group } from "./group";

export interface GroupState {
  groups: Group[]
  status: string;
  selected: Group | undefined;
}
