# 🎨 Tailwind CSS + Symfony

Esta aplicación usa **Tailwind CSS Standalone CLI** con Symfony AssetMapper.

## 📦 Instalación

El binario de Tailwind ya está incluido en el proyecto (`./tailwindcss`).

## 🚀 Uso

### Desarrollo (con watch mode)
```bash
./watch-tailwind.sh
```
Esto compila Tailwind automáticamente cuando detecta cambios en templates o CSS.

### Compilar para producción
```bash
./tailwindcss -i ./assets/styles/tailwind.css -o ./public/styles/output.css --minify
```

## 📁 Estructura

- `tailwind.config.js` - Configuración de Tailwind
- `assets/styles/tailwind.css` - Input CSS (con @layer y componentes custom)
- `public/styles/output.css` - Output compilado (auto-generado)
- `templates/**/*.twig` - Templates con clases de Tailwind

## 🎨 Componentes Custom

Se han creado clases reutilizables en `assets/styles/tailwind.css`:

- `.btn-primary` - Botón principal con gradiente
- `.btn-secondary` - Botón secundario
- `.btn-danger` - Botón de eliminación
- `.card` - Tarjeta con hover effect
- `.input-field` - Campo de formulario
- `.label` - Etiqueta de formulario

## ⚙️ Ventajas de este enfoque

✅ **Sin Node.js** - Binario standalone
✅ **Compatible con AssetMapper** - No necesitas Webpack Encore
✅ **Ligero y rápido** - Solo ~3MB el binario
✅ **Fácil deployment** - Commiteas el CSS compilado o lo generas en CI/CD
✅ **Watch mode** - Recompilación automática en desarrollo

## 🔄 Workflow recomendado

1. Abre una terminal y ejecuta `./watch-tailwind.sh`
2. Edita tus templates en `templates/`
3. Los cambios se reflejan automáticamente
4. Antes de commitear, ejecuta el comando de producción

## 📚 Documentación

- [Tailwind CSS](https://tailwindcss.com/docs)
- [Symfony AssetMapper](https://symfony.com/doc/current/frontend/asset_mapper.html)
