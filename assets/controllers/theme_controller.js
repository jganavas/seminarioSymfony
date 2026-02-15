import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["lightIcon", "darkIcon"];

    connect() {
        console.log('ThemeController connected');
        // Obtener tema guardado, con fallback a 'light' por defecto
        // Cambiado: ya no usamos getSystemPreference() como fallback
        // para evitar que Safari se quede atascado en modo oscuro
        try {
            const savedTheme = localStorage.getItem("theme");
            const theme = savedTheme || "light"; // Default explícito a light
            this.setTheme(theme);
        } catch (e) {
            // Safari puede bloquear localStorage en modo privado
            console.warn(
                "Could not access localStorage, defaulting to light theme:",
                e,
            );
            this.setTheme("light");
        }
    }

    toggle() {
        const currentTheme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";
        this.setTheme(newTheme);
    }

    setTheme(theme) {
        // Aplicar o remover clase 'dark' al elemento html
        if (theme === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }


        // Guardar preferencia en localStorage (con manejo de errores para Safari)
        try {
            localStorage.setItem("theme", theme);
        } catch (e) {
        }

        // Actualizar icono del botón
        this.updateIcon(theme);
    }

    updateIcon(theme) {
        // Not needed anymore as CSS classes handle visibility based on parent .dark class
        // But we keep the method structure in case we need complex logic later
    }

    getSystemPreference() {
        // Detectar preferencia del sistema operativo
        if (
            window.matchMedia &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
            return "dark";
        }
        return "light";
    }
}
