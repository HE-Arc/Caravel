export default interface EventTask {
  name: string;
  start: Date;
  end: Date | undefined;
  color: string | undefined;
  timed: boolean;
  id: string;
}
