FROM alpine:3.19

# Install Nginx
RUN apk --no-cache --update add \
   curl \
   nginx \
   tzdata \
   && rm -rf /var/cache/apk/*

ENV TZ=Asia/Jakarta

WORKDIR /var/www/html

COPY ./nginx.conf /etc/nginx/http.d/default.conf

# Port yang akan digunakan oleh Nginx
EXPOSE 80

# Command untuk menjalankan Nginx pada background
CMD ["nginx", "-g", "daemon off;"]