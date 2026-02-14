import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["icon"];

    connect() {
        // Obtener tema: 1) localStorage (preferencia manual), 2) sistema, 3) fallback a 'light'
        const savedTheme = localStorage.getItem("theme");
        const theme = savedTheme || this.getSystemPreference();
        this.setTheme(theme);
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

        // Guardar preferencia en localStorage
        localStorage.setItem("theme", theme);

        // Actualizar icono del botón
        this.updateIcon(theme);
    }

    updateIcon(theme) {
        // Si hay targets de icono disponibles, actualizarlos
        if (this.hasIconTarget) {
            this.iconTargets.forEach((icon) => {
                // ☀️ en modo oscuro (indica "cambiar a claro")
                // 🌙 en modo claro (indica "cambiar a oscuro")
                icon.textContent = theme === "dark" ? "☀️" : "🌙";
            });
        }
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
