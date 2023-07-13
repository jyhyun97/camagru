FROM ubuntu:latest

# 설정
ENV DEBIAN_FRONTEND="noninteractive"
ENV TZ=Asia/Seoul
ENV HOSTNAME=localhost

# 서비스 설치
RUN apt-get update
RUN apt-get install -y git vim wget nginx php php8.1-fpm mariadb-server php8.1-mysql php8.1-gd openssl unzip sudo
RUN echo "postfix postfix/mailname string camagru.com" | debconf-set-selections && \
    echo "postfix postfix/main_mailer_type string 'Internet Site'" | debconf-set-selections && \
    apt-get install -y postfix

#폴더 생성
RUN mkdir /home/ubuntu
RUN mkdir /home/ubuntu/camagru
RUN mkdir /home/ubuntu/camagru/img
RUN mkdir /home/ubuntu/camagru/app
RUN mkdir /home/ubuntu/camagru/app/bootstrap
RUN mkdir /etc/postfix/ssl

# 부트스트랩 설치
RUN wget -O bootstrap.zip "https://github.com/twbs/bootstrap/releases/download/v3.3.2/bootstrap-3.3.2-dist.zip"
RUN unzip bootstrap.zip -d bootstrap
RUN mv bootstrap/bootstrap-3.3.2-dist/* home/ubuntu/camagru/app/bootstrap/
RUN rm -rf bootstrap && rm -rf bootstrap.zip

# 주요 파일 복사
COPY app /home/ubuntu/camagru/app
COPY .env /home/ubuntu/camagru
COPY index.php /home/ubuntu/camagru
COPY sticky /home/ubuntu/camagru/sticky
COPY setting/dump.sql /home/ubuntu/camagru
COPY setting/img.tar /home/ubuntu/camagru/img
COPY setting/init.sh /home/ubuntu/camagru
COPY setting/default /etc/nginx/sites-available
COPY setting/php.ini /etc/php/8.1/cli
COPY setting/main.cf /etc/postfix
COPY setting/sasl_passwd /etc/postfix/sasl

#img 압축 해제
RUN tar -xvf /home/ubuntu/camagru/img/img.tar -C /home/ubuntu/camagru/
RUN rm -rf /home/ubuntu/camagru/img/img.tar
RUN chown www-data:www-data /home/ubuntu/camagru/img/

# postfix 세팅
WORKDIR /etc/postfix/ssl
RUN openssl req -newkey rsa:4096 -days 365 -nodes -x509 -subj "/C=KR/ST=Seoul/L=Seoul/O=42Seoul/OU=gam/CN=localhost" -keyout camagru.key -out camagru.crt
RUN openssl pkcs12 -export -in camagru.crt -inkey camagru.key -out camagru.p12 -passout pass:
RUN openssl pkcs12 -in camagru.p12 -nodes -out camagru.pem -passin pass:

WORKDIR /etc/postfix/sasl
RUN postmap sasl_passwd

EXPOSE 80

# mariadb 세팅 (db 접속 정보 설정, dump.sql 적용)은 init.sh에서 해줄게요
ENTRYPOINT ["/bin/bash", "-c", "/home/ubuntu/camagru/init.sh & nginx -g 'daemon off;'"]