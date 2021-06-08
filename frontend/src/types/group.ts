export interface Group {
  id: string;
  name: string;
  description: string;
  picture: string;
  picture_full: string;
  isPrivate: boolean;
  user_id: string;
  status: number | undefined;
}
