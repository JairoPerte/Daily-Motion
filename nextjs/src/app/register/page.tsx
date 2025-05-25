"use client";
import styles from "@/presentation/styles/sign/sign-card.module.css";
import Link from "next/link";
import { useState } from "react";

export default function RegisterPage() {
  const [name, setName] = useState("");
  const [usertag, setUsertag] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/register`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, usertag, email, password }),
      }
    );

    const data = await response.json();
    console.log(data);
  };

  return (
    <div className={styles.container}>
      <form className={styles.card} onSubmit={handleSubmit}>
        <h2 className={styles.title}>Registrarse</h2>
        <input
          type="text"
          placeholder="Nombre"
          value={name}
          className={styles.input}
          onChange={(e) => setName(e.target.value)}
          required
        />
        <input
          type="text"
          placeholder="Nombre de usuario"
          value={usertag}
          className={styles.input}
          onChange={(e) => setUsertag(e.target.value)}
          required
        />
        <input
          type="email"
          placeholder="Correo electrónico"
          value={email}
          className={styles.input}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          placeholder="Contraseña"
          value={password}
          className={styles.input}
          onChange={(e) => setPassword(e.target.value)}
          required
        />
        <button className={styles.button} type="submit">
          Crear cuenta
        </button>
        <p className={styles.text}>
          ¿Ya tienes una cuenta?{" "}
          <Link href="/login" className={styles.link}>
            Inicia sesión aquí
          </Link>
        </p>
      </form>
    </div>
  );
}
