import HistoryMeta from "./HistoryMeta";

export default interface History {
    id: number;
    model_type: string;
    model_id: number;
    user_id: number;
    message: string;
    meta: HistoryMeta[];
    performed_at: Date;
}