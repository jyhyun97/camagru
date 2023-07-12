FROM ubuntu:latest

# 설정
ENV DEBIAN_FRONTEND="noninteractive"
ENV TZ=Asia/Seoul

# 서비스 설치
RUN apt-get update
RUN apt-get install -y git vim wget nginx php php8.1-fpm mariadb-server php8.1-mysql php8.1-gd openssl postfix unzip sudo

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
COPY setting/dump.sql /home/ubuntu/camagru
COPY setting/img.tar /home/ubuntu/camagru/img/img.tar
COPY setting/init.sh /home/ubuntu/camagru
COPY setting/default /etc/nginx/sites-available/default
COPY setting/php.ini /etc/php/8.1/cli/php.ini
COPY setting/main.cf /etc/postfix/main.cf

#img 압축 해제
RUN tar -xvf /home/ubuntu/camagru/img/img.tar -C /home/ubuntu/camagru/
RUN rm -rf /home/ubuntu/camagru/img/img.tar

# postfix 세팅 (openssl 세팅, sasl_passwd 세팅.. sasl_passwd)
# RUN openssl req -newkey rsa:4096 -days 365 -nodes -x509 -subj "/C=KR/ST=Seoul/L=Seoul/O=42Seoul/OU=gam/CN=localhost" -keyout localhost.dev.key -out localhost.dev.crt

EXPOSE 80

# mariadb 세팅 (db 접속 정보 설정, dump.sql 적용)은 init.sh에서 해줄게요
CMD ["/bin/bash", "source /home/ubuntu/camagru/init.sh"]