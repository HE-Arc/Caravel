export interface Group {
  id: string;
  name: string;
  description: string;
  picture: string;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
}
