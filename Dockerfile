# Etapa de construção
FROM node:18 AS builder

WORKDIR /app
COPY . /app/

# Instalar dependências
RUN npm install
RUN npm ci

# Garantir permissões para o Vite
RUN chmod +x ./node_modules/.bin/vite

# Construir o projeto
RUN npm run build

# Configurar usuário
USER root

# Início do servidor
CMD ["node", "dist/server.js"]
