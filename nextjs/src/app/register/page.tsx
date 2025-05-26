"use client";
import styles from "@/presentation/styles/sign/sign-card.module.css";
import Link from "next/link";
import { useState } from "react";
import { useRouter } from "next/navigation";

export default function RegisterPage() {
  const router = useRouter();

  const [name, setName] = useState("");
  const [usertag, setUsertag] = useState("");
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");

  const [nameError, setNameError] = useState("");
  const [usertagError, setUsertagError] = useState("");
  const [emailError, setEmailError] = useState("");
  const [passwordError, setPasswordError] = useState("");
  const [confirmPasswordError, setConfirmPasswordError] = useState("");
  const [generalError, setGeneralError] = useState("");

  const passwordRegex =
    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{12,128}$/;

  const validateFields = () => {
    let valid = true;

    if (password !== confirmPassword) {
      setConfirmPasswordError("Las contraseñas tienen que coincidir.");
      valid = false;
    } else {
      setConfirmPasswordError("");
    }

    if (name.length < 5 || name.length > 100) {
      setNameError("El nombre debe tener entre 5 y 100 caracteres.");
      valid = false;
    } else {
      setNameError("");
    }

    if (usertag.length < 3 || usertag.length > 20) {
      setUsertagError("El usuario debe tener entre 3 y 20 caracteres.");
      valid = false;
    } else {
      setUsertagError("");
    }

    if (email.length < 6 || email.length > 255) {
      setEmailError("El correo debe tener entre 6 y 255 caracteres.");
      valid = false;
    } else {
      setEmailError("");
    }

    if (!passwordRegex.test(password)) {
      setPasswordError(
        "La contraseña debe tener entre 12 y 128 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos."
      );
      valid = false;
    } else {
      setPasswordError("");
    }

    return valid;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setGeneralError("");

    if (!validateFields()) return;

    setUsertagError("");
    setEmailError("");

    const response = await fetch(
      `${process.env.NEXT_PUBLIC_API_URL}/auth/register`,
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, usertag, email, password }),
        credentials: "include",
      }
    );

    if (response.status === 204) {
      router.push("/verify-email");
    } else if (response.status === 400) {
      setGeneralError("No te saltes mi formulario mamón");
      const data = await response.json();
      const fields = data.fields || {};

      if (fields["name"]) {
        setNameError("El nombre debe tener entre 5 y 100 caracteres.");
      }
      if (fields["usertag"]) {
        setUsertagError("El usuario debe tener entre 3 y 20 caracteres.");
      }
      if (fields["email"]) {
        setEmailError("El correo debe tener entre 6 y 255 caracteres.");
      }
      if (fields["password"]) {
        setPasswordError(
          "La contraseña debe tener entre 12 y 128 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos."
        );
      }
    } else if (response.status === 409) {
      const data = await response.json();
      const fields = data.fields || {};

      if (fields["usertag"]) {
        setUsertagError("Este nombre de usuario ya está en uso.");
      }

      if (fields["email"]) {
        setEmailError("Este correo electrónico ya está registrado.");
      }
    } else {
      setGeneralError("Algo ha salido mal. Inténtalo más tarde.");
    }
  };

  return (
    <div className={styles.container}>
      <form className={styles.card} onSubmit={handleSubmit}>
        <h2 className={styles.title}>Registrarse</h2>

        {/* NOMBRE */}
        <input
          type="text"
          placeholder="Nombre"
          value={name}
          className={`${styles.input} ${
            nameError ? styles["bad-input"] : name ? styles["good-input"] : ""
          }`}
          onChange={(e) => setName(e.target.value)}
          required
        />
        {name && nameError && (
          <p className={styles["error-text"]}>{nameError}</p>
        )}

        {/* USERTAG */}
        <input
          type="text"
          placeholder="Usuario"
          value={usertag}
          className={`${styles.input} ${
            usertagError
              ? styles["bad-input"]
              : usertag
              ? styles["good-input"]
              : ""
          }`}
          onChange={(e) => setUsertag(e.target.value)}
          required
        />
        {usertag && usertagError && (
          <p className={styles["error-text"]}>{usertagError}</p>
        )}

        {/* EMAIL */}
        <input
          type="email"
          placeholder="Correo electrónico"
          value={email}
          className={`${styles.input} ${
            emailError ? styles["bad-input"] : email ? styles["good-input"] : ""
          }`}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        {email && emailError && (
          <p className={styles["error-text"]}>{emailError}</p>
        )}

        {/* PASSWORD */}
        <input
          type="password"
          placeholder="Contraseña"
          value={password}
          className={`${styles.input} ${
            passwordError
              ? styles["bad-input"]
              : password
              ? styles["good-input"]
              : ""
          }`}
          onChange={(e) => setPassword(e.target.value)}
          required
        />

        {/* CONFIRM PASSWORD */}
        {password && passwordError && (
          <p className={styles["error-text"]}>{passwordError}</p>
        )}
        <input
          type="password"
          placeholder="Confirmar Contraseña"
          value={confirmPassword}
          className={`${styles.input} ${
            passwordError
              ? styles["bad-input"]
              : password
              ? styles["good-input"]
              : ""
          }`}
          onChange={(e) => setConfirmPassword(e.target.value)}
          required
        />
        {confirmPassword && confirmPasswordError && (
          <p className={styles["error-text"]}>{confirmPasswordError}</p>
        )}

        {generalError && <p className={styles["error-text"]}>{generalError}</p>}

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
