#!/bin/bash
# Script para ejecutar Tailwind en modo watch durante el desarrollo

echo "🎨 Iniciando Tailwind CSS en modo watch..."
./tailwindcss -i ./tailwind-source.css -o ./public/styles/output.css --watch
