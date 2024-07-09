#syntax=docker/dockerfile:1.7@sha256:a57df69d0ea827fb7266491f2813635de6f17269be881f696fbfdf2d83dda33e


# Versions
FROM node:20-alpine@sha256:692b5239da8cf4b4b5ea25d966d83e2911f389d9fef88ac9ffbe76b2d48ef135 AS node_upstream


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
