import { Data } from "./data";

export default interface GroupStat extends Data {
    create_at: Date | undefined,
    wes: number,
}