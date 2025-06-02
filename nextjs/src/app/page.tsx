import Image from "next/image";
import "@/presentation/styles/landingpage/index.css";

export default function Home() {
  return (
    <div>
      <main className="main-container">
        <div className="left-section">
          <h1>¡Bienvenido a Daily Motion!</h1>
          <p>Registra todas tus actividades y lleva un control completo.</p>
          {/* <div className="auth-links">
            {" "}
            <p>
              ¿No tienes cuenta? <a href="/register">Regístrate aquí.</a>
            </p>
            <p>
              ¿Ya tenías una cuenta anteriormente?{" "}
              <a href="/login">Inicia Sesión aquí.</a>
            </p>
          </div> */}
          {/* This div is for the buttons */}
          <div className="auth-buttons">
            <a href="/register" className="btn primary-btn">
              Regístrate ahora
            </a>
            <a href="/login" className="btn secondary-btn">
              Inicia Sesión
            </a>
          </div>
        </div>

        <div className="right-section">
          <div className="single-collage-image-container">
            <Image
              src="/landingpage/collage.png"
              alt="Collage de Actividades"
              layout="fill"
              objectFit="cover"
              priority
            />
          </div>
        </div>
      </main>

      <footer className="footer">
        <a href="/about-us" className="">
          Sobre nosotros
        </a>
        <p>|</p>
        <a href="/contact" className="">
          Contacto
        </a>
        <p>|</p>
        <a href="/contact" className="">
          Política de cookies
        </a>
      </footer>
    </div>
  );
}
