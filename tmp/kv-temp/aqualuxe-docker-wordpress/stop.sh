#!/bin/bash

# AquaLuxe Docker Stop Script

echo "🛑 Stopping AquaLuxe Docker containers..."

# Stop all containers
docker-compose down

echo "✅ All containers stopped successfully!"
echo ""
echo "To start again, run: ./start.sh"
echo "To remove all data, run: docker-compose down -v"
