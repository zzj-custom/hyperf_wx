FROM zqp113/hyperf
LABEL maintainer="zouzhujia <1844066417@qq.com>"

##
# ---------- env settings ----------
##
# --build-arg timezone=Asia/Shanghai
ARG timezone

ENV TIMEZONE=${timezone:-"Asia/Shanghai"} \
    APP_ENV=dev



# update
RUN set -ex \
    # show php version and extensions
    && php -v \
    && php -m \
    && php --ri swoole \
    #  ---------- some config ----------
    && cd /etc/php/7.4/cli/conf.d \
    && ls -alh \
    # - config PHP
    #
    && { \
        echo "upload_max_filesize=128M"; \
        echo "post_max_size=128M"; \
        echo "memory_limit=1G"; \
        echo "date.timezone=${TIMEZONE}"; \
    } | tee 99_overrides.ini \
    # - config timezone
    && ln -sf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime \
    # 删除无关的包
    && apt autoremove -y \
    && apt-get clean && rm -rf /var/lib/apt/lists/* \
    #添加时区
    && echo "${TIMEZONE}" > /etc/timezone \
    # ---------- clear works ----------
    && echo -e "\033[42;37m Build Completed :).\033[0m\n" \
    # ---------- create working dir ----------
    && mkdir -p /var/www/html \
    && echo -e "\033[42;37m Working Directory Created\033[0m\n"

EXPOSE 80

WORKDIR /var/www/html
