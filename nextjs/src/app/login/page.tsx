"use client";
import styles from "@/presentation/styles/sign/sign-card.module.css";
import Link from "next/link";
import { useState } from "react";
import { useRouter } from "next/navigation";

export default function LoginPage() {
  const router = useRouter();

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const [emailClass, setEmailClass] = useState(styles.input);
  const [passwordClass, setPasswordClass] = useState(styles.input);

  const [emailError, setEmailError] = useState("");
  const [passwordError, setPasswordError] = useState("");
  const [generalError, setGeneralError] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    setGeneralError("");

    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/login`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
        credentials: "include",
      }
    );

    console.log(response.json());

    if (response.status == 204) {
      router.push("/app");
    } else {
      if (response.status == 404) {
        // Email no existe
        setEmailClass(`${styles.input} ${styles["bad-input"]}`);
        setEmailError("Este email no está registrado.");
        // limpia
        setPasswordClass(styles.input);
        setPasswordError("");
      } else if (response.status == 401) {
        // Contraseña incorrecta
        setPasswordClass(`${styles.input} ${styles["bad-input"]}`);
        setPasswordError("La contraseña es incorrecta.");
        //email detectado
        setEmailClass(`${styles.input} ${styles["good-input"]}`);
        //limpia
        setEmailError("");
      } else {
        // Error desconocido, p.e. bd error o mail error
        setGeneralError("Algo ha ido mal. Intenta de nuevo más tarde.");
        //limpia
        setEmailClass(styles.input);
        setPasswordClass(styles.input);
        setEmailError("");
        setPasswordError("");
      }
    }
  };

  return (
    <div className={styles.container}>
      <form className={styles.card} onSubmit={handleSubmit}>
        <h2 className={styles.title}>Iniciar sesión</h2>

        <input
          id="email"
          type="email"
          placeholder="Correo electrónico"
          value={email}
          className={emailClass}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        {emailError && <p className={styles["error-text"]}>{emailError}</p>}

        <input
          id="password"
          type="password"
          placeholder="Contraseña"
          value={password}
          className={passwordClass}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        {passwordError && (
          <p className={styles["error-text"]}>{passwordError}</p>
        )}

        {generalError && <p className={styles["error-text"]}>{generalError}</p>}

        <button type="submit" className={styles.button}>
          Iniciar sesión
        </button>
        <p className={styles.text}>
          ¿No tienes una cuenta?{" "}
          <Link href="/register" className={styles.link}>
            Crea una aquí
          </Link>
        </p>
      </form>
    </div>
  );
}
