# Build stage
FROM node:latest AS build-stage
WORKDIR /app

# Copy package files first for caching
COPY package*.json ./

RUN npm install

COPY . .

#COMMENT OUT
EXPOSE 5173
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
#COMMENT OUT

# #UNCOMMENT
# RUN npm run stage

# Production stage
# FROM nginx:alpine
# COPY --from=build-stage /app/dist /usr/share/nginx/html
# EXPOSE 80
# #UNCOMMENT