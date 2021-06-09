export interface Group {
  id: string;
  name: string;
  description: string;
  picture: string | undefined;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
}
