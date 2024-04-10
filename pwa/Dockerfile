#syntax=docker/dockerfile:1.7@sha256:dbbd5e059e8a07ff7ea6233b213b36aa516b4c53c645f1817a4dd18b83cbea56


# Versions
FROM node:20-alpine@sha256:7e227295e96f5b00aa79555ae166f50610940d888fc2e321cf36304cbd17d7d6 AS node_upstream


# Base stage for dev and build
FROM node_upstream AS base

# Check https://github.com/nodejs/docker-node/tree/b4117f9333da4138b03a546ec926ef50a31506c3#nodealpine to understand why libc6-compat might be needed.
# hadolint ignore=DL3018
RUN apk add --no-cache libc6-compat

WORKDIR /srv/app

RUN corepack enable && \
	corepack prepare --activate pnpm@latest && \
	pnpm config -g set store-dir /.pnpm-store

# Development image
FROM base as dev

EXPOSE 5173
ENV PORT 5173
ENV HOSTNAME localhost

CMD ["sh", "-c", "pnpm install; pnpm dev --host"]


FROM base AS builder

COPY --link pnpm-lock.yaml ./
# RUN pnpm fetch

COPY --link . .

RUN	pnpm install \
	&& pnpm run build

# Production image, copy all the files and run next
FROM node_upstream AS prod

WORKDIR /srv/app

ENV NODE_ENV production

RUN addgroup --system --gid 1001 nodejs; \
	adduser --system --uid 1001 nodejs

COPY --from=builder --link /srv/app ./

USER nodejs

EXPOSE 5173

ENV PORT 5173
ENV HOSTNAME "0.0.0.0"

CMD ["npm", "run", "start", "--host"]
