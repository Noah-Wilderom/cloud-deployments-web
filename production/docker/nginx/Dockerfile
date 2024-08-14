FROM node:20-alpine AS assets-build

WORKDIR /var/www/html

COPY . .

RUN npm ci
RUN npm run build

FROM nginx:alpine AS nginx

COPY --from=assets-build /var/www/html var/www/html/
