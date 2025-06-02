"use client";
import { useRef, useState } from "react";
import { useRouter } from "next/navigation";

import Link from "next/link";

import styles from "@/presentation/styles/sign/sign-card.module.css";

export default function VerifyEmailPage() {
  const [code, setCode] = useState(["", "", "", ""]);

  const inputRefs = useRef<Array<HTMLInputElement | null>>([]);

  const [errorMessage, setErrorMessage] = useState("");

  const router = useRouter();

  const handleChange = (index: number, value: string) => {
    if (!/^\d?$/.test(value)) return;

    const newCode = [...code];
    newCode[index] = value;
    setCode(newCode);

    if (value && index < 3) {
      inputRefs.current[index + 1]?.focus();
    }
  };

  const handleKeyDown = (index: number, e: React.KeyboardEvent) => {
    if (e.key === "Backspace" && !code[index] && index > 0) {
      inputRefs.current[index - 1]?.focus();
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    const verificationCode = code.join("");

    if (verificationCode.length < 4) return;

    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/verify-email/${verificationCode}`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: null,
        credentials: "include",
      }
    );

    console.log(response.json());

    if (response.status === 204) {
      router.push("/app");
    } else if (response.status === 401) {
      setErrorMessage("El código de email no es válido.");
    } else {
      setErrorMessage("Algo ha salido mal. Inténtalo más tarde.");
    }
  };

  return (
    <div className={styles.container}>
      <form className={styles.card} onSubmit={handleSubmit}>
        <h2 className={styles.title}>Verifica tu correo</h2>

        <p className={styles.text}>
          Hemos enviado un código de 4 dígitos a tu correo. Introdúcelo a
          continuación.
        </p>

        <div style={{ display: "flex", gap: "1rem", justifyContent: "center" }}>
          {code.map((digit, i) => (
            <input
              key={i}
              type="text"
              maxLength={1}
              value={digit}
              className={styles.input}
              onChange={(e) => handleChange(i, e.target.value)}
              onKeyDown={(e) => handleKeyDown(i, e)}
              ref={(el) => {
                inputRefs.current[i] = el;
              }}
              inputMode="numeric"
              autoComplete="one-time-code"
              style={{
                width: "3rem",
                textAlign: "center",
                fontSize: "1.5rem",
              }}
              required
            />
          ))}
        </div>

        {errorMessage && <p className={styles["error-text"]}>{errorMessage}</p>}

        <button
          type="submit"
          className={styles.button}
          style={{ marginTop: "1.5rem" }}
        >
          Verificar Email
        </button>

        <p className={styles.text}>
          ¿No has recibido ningún correo?{" "}
          <Link href="/email-code/again" className={styles.link}>
            Enviar uno nuevo
          </Link>
        </p>
      </form>
    </div>
  );
}
