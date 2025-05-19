import { login } from "@/core/User/Infrastructure/Api/UserApi";

export const loginUser = async (username: string, password: string) => {
  const user = await login(username, password);
  // aquí podrías hacer más lógica: guardar token, transformar respuesta, etc
  return user;
};