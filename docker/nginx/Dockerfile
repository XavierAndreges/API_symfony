FROM nginx:alpine

# Copy Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Create a simple health check page
RUN echo '<html><body><h1>Nginx is running</h1></body></html>' > /usr/share/nginx/html/health.html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"] 