#!/bin/bash
#
# Seed Framework Installer
# https://github.com/iQ-Global/seed
#
# Usage:
#   curl -sL https://raw.githubusercontent.com/iQ-Global/seed/master/install.sh | bash -s myproject
#   OR
#   bash install.sh myproject
#

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Project name from argument or prompt
PROJECT_NAME="${1:-}"

echo ""
echo -e "${GREEN}🌱 Seed Framework Installer${NC}"
echo ""

# Check requirements
command -v curl >/dev/null 2>&1 || { echo -e "${RED}Error: curl is required but not installed.${NC}" >&2; exit 1; }
command -v unzip >/dev/null 2>&1 || { echo -e "${RED}Error: unzip is required but not installed.${NC}" >&2; exit 1; }
command -v php >/dev/null 2>&1 || { echo -e "${RED}Error: PHP is required but not installed.${NC}" >&2; exit 1; }
command -v composer >/dev/null 2>&1 || { echo -e "${RED}Error: Composer is required but not installed.${NC}" >&2; exit 1; }

# Get project name if not provided
if [ -z "$PROJECT_NAME" ]; then
    echo -n "Project name: "
    read PROJECT_NAME
fi

# Validate project name
if [ -z "$PROJECT_NAME" ]; then
    echo -e "${RED}Error: Project name is required.${NC}"
    exit 1
fi

# Check if directory exists
if [ -d "$PROJECT_NAME" ]; then
    echo -e "${RED}Error: Directory '$PROJECT_NAME' already exists.${NC}"
    exit 1
fi

echo -e "${BLUE}Creating project: ${PROJECT_NAME}${NC}"
echo ""

# Create temp directory
TEMP_DIR=$(mktemp -d)
TEMP_ZIP="$TEMP_DIR/seed.zip"

# Download latest release
echo -e "${YELLOW}Downloading Seed Framework...${NC}"
curl -sL "https://github.com/iQ-Global/seed/archive/refs/heads/master.zip" -o "$TEMP_ZIP"

if [ ! -f "$TEMP_ZIP" ]; then
    echo -e "${RED}Error: Failed to download Seed Framework.${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi
echo -e "${GREEN}  ✓ Downloaded${NC}"

# Extract
echo -e "${YELLOW}Extracting...${NC}"
unzip -q "$TEMP_ZIP" -d "$TEMP_DIR"

# Move to project directory
mv "$TEMP_DIR/seed-master" "$PROJECT_NAME"
echo -e "${GREEN}  ✓ Extracted${NC}"

# Cleanup temp
rm -rf "$TEMP_DIR"

# Enter project directory
cd "$PROJECT_NAME"

# Remove files not needed for new projects
rm -f install.sh 2>/dev/null || true
rm -rf .git 2>/dev/null || true
rm -rf website 2>/dev/null || true
rm -rf dev-docs 2>/dev/null || true
rm -rf tests 2>/dev/null || true
rm -rf ref 2>/dev/null || true

# Create .env from example
if [ -f ".env.example" ]; then
    cp .env.example .env
    echo -e "${GREEN}  ✓ Created .env${NC}"
fi

# Install Composer dependencies
echo -e "${YELLOW}Installing dependencies...${NC}"
composer install --quiet --no-interaction
echo -e "${GREEN}  ✓ Dependencies installed${NC}"

# Initialize git repository
echo -e "${YELLOW}Initializing git repository...${NC}"
git init --quiet
git add .
git commit --quiet -m "Initial commit from Seed Framework"
echo -e "${GREEN}  ✓ Git initialized${NC}"

# Make seed CLI executable
chmod +x seed

# Done!
echo ""
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${GREEN}✓ Project '$PROJECT_NAME' created successfully!${NC}"
echo -e "${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "Next steps:"
echo ""
echo -e "  ${BLUE}cd $PROJECT_NAME${NC}"
echo -e "  ${BLUE}php seed serve${NC}"
echo ""
echo -e "Then open ${YELLOW}http://localhost:8000${NC} in your browser."
echo ""
echo -e "Edit ${YELLOW}.env${NC} to configure your database."
echo ""
echo -e "Documentation: ${BLUE}https://github.com/iQ-Global/seed${NC}"
echo ""

