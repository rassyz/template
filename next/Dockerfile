FROM node:22-alpine

WORKDIR /app

RUN apk add --no-cache bash libc6-compat

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 3000

ENTRYPOINT ["docker-entrypoint.sh"]

CMD ["npm", "run", "dev", "--", "--hostname", "0.0.0.0"]
