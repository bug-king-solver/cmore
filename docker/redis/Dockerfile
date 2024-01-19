FROM redis:latest@sha256:21cc29024ab0a6cc3456155f7cc7456a82775e4625368e4f9671c84a0f3951fa

LABEL maintainer="Mahmoud Zalt <mahmoud@zalt.me>"

## For security settings uncomment, make the dir, copy conf, and also start with the conf, to use it
# RUN mkdir -p /usr/local/etc/redis
# COPY redis.conf /usr/local/etc/redis/redis.conf

RUN echo "vm.overcommit_memory=1" >> /etc/sysctl.conf && \
echo 'vm.swappiness=0' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_sack=1' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_timestamps=1' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_window_scaling=1' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_congestion_control=1' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_syncookies=1' >> /etc/sysctl.conf && \
echo 'vm.ipv4.tcp_tw_recycle=1' >> /etc/sysctl.conf


VOLUME /data

EXPOSE 6379

#CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
CMD ["redis-server"]
