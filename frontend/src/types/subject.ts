import { Data } from "./Data";

export interface Subject extends Data {
  id: string;
  name: string;
  color: string;
  ects: number;
}
