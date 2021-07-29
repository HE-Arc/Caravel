export default interface Notification {
  id: string;
  type: string;
  data: {
    title: string;
    message: string;
    type: number;
    model: string;
    model_id: string;
    group_id: string;
  };
  read_at: Date | null;
  created_at: Date | null;
  updated_at: Date | null;
}
