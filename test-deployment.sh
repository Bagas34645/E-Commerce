#!/bin/bash

# E-Commerce Nginx Deployment Test Script
# This script tests all functionality after nginx deployment

echo "🧪 E-Commerce Nginx Deployment Test"
echo "===================================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

PASSED=0
FAILED=0
BASE_URL="http://localhost"

print_test() {
    echo -e "\n[TEST] $1"
}

print_pass() {
    echo -e "${GREEN}✅ PASS:${NC} $1"
    ((PASSED++))
}

print_fail() {
    echo -e "${RED}❌ FAIL:${NC} $1"
    ((FAILED++))
}

print_warn() {
    echo -e "${YELLOW}⚠️  WARN:${NC} $1"
}

# Test 1: Basic Connectivity
test_basic_connectivity() {
    print_test "Basic Connectivity"
    
    status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/")
    if [ "$status" = "200" ]; then
        print_pass "Home page accessible (HTTP $status)"
    else
        print_fail "Home page not accessible (HTTP $status)"
    fi
}

# Test 2: PHP Processing
test_php_processing() {
    print_test "PHP Processing"
    
    response=$(curl -s "$BASE_URL/index.php")
    if echo "$response" | grep -q "<!DOCTYPE"; then
        print_pass "PHP files are being processed correctly"
    else
        print_fail "PHP files are not being processed"
    fi
}

# Test 3: Clean URLs
test_clean_urls() {
    print_test "Clean URLs (URL Rewriting)"
    
    # Test common pages
    pages=("about" "menu" "contact" "cart")
    
    for page in "${pages[@]}"; do
        status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/$page")
        if [ "$status" = "200" ]; then
            print_pass "Clean URL /$page works (HTTP $status)"
        else
            print_fail "Clean URL /$page failed (HTTP $status)"
        fi
    done
}

# Test 4: Static Files
test_static_files() {
    print_test "Static Files Serving"
    
    # Test CSS
    status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/css/style.css")
    if [ "$status" = "200" ]; then
        print_pass "CSS files served correctly"
    else
        print_fail "CSS files not served (HTTP $status)"
    fi
    
    # Test JS
    status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/js/script.js")
    if [ "$status" = "200" ]; then
        print_pass "JavaScript files served correctly"
    else
        print_fail "JavaScript files not served (HTTP $status)"
    fi
    
    # Test Images
    status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/images/home-img-1.jpg")
    if [ "$status" = "200" ]; then
        print_pass "Image files served correctly"
    else
        print_fail "Image files not served (HTTP $status)"
    fi
}

# Test 5: Security Headers
test_security_headers() {
    print_test "Security Headers"
    
    headers=$(curl -s -I "$BASE_URL/")
    
    if echo "$headers" | grep -q "X-Frame-Options"; then
        print_pass "X-Frame-Options header present"
    else
        print_fail "X-Frame-Options header missing"
    fi
    
    if echo "$headers" | grep -q "X-Content-Type-Options"; then
        print_pass "X-Content-Type-Options header present"
    else
        print_fail "X-Content-Type-Options header missing"
    fi
    
    if echo "$headers" | grep -q "X-XSS-Protection"; then
        print_pass "X-XSS-Protection header present"
    else
        print_fail "X-XSS-Protection header missing"
    fi
}

# Test 6: File Access Restrictions
test_security_restrictions() {
    print_test "Security Restrictions"
    
    # Test access to sensitive files
    sensitive_files=("config/config.php" "nginx.conf" "README.md" "DEPLOYMENT.md")
    
    for file in "${sensitive_files[@]}"; do
        status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/$file")
        if [ "$status" = "403" ] || [ "$status" = "404" ]; then
            print_pass "Access to $file properly restricted (HTTP $status)"
        else
            print_fail "Access to $file not restricted (HTTP $status)"
        fi
    done
}

# Test 7: Admin Panel Access
test_admin_panel() {
    print_test "Admin Panel Access"
    
    status=$(curl -s -o /dev/null -w "%{http_code}" "$BASE_URL/admin/")
    if [ "$status" = "200" ]; then
        print_pass "Admin panel accessible"
    else
        print_fail "Admin panel not accessible (HTTP $status)"
    fi
}

# Test 8: Upload Directory
test_upload_directory() {
    print_test "Upload Directory Permissions"
    
    upload_dir="/var/www/html/E-Commerce/uploaded_img"
    if [ -d "$upload_dir" ]; then
        permissions=$(stat -c "%a" "$upload_dir" 2>/dev/null)
        if [ "$permissions" = "777" ] || [ "$permissions" = "755" ]; then
            print_pass "Upload directory has correct permissions ($permissions)"
        else
            print_warn "Upload directory permissions might be restrictive ($permissions)"
        fi
    else
        print_fail "Upload directory not found"
    fi
}

# Test 9: Database Connection
test_database_connection() {
    print_test "Database Connection"
    
    # Try to access a page that would require database
    response=$(curl -s "$BASE_URL/menu.php" 2>/dev/null)
    if echo "$response" | grep -q -i "error\|warning\|fatal"; then
        print_fail "Possible database connection error detected"
    else
        print_pass "No obvious database connection errors"
    fi
}

# Test 10: GZIP Compression
test_gzip_compression() {
    print_test "GZIP Compression"
    
    headers=$(curl -s -H "Accept-Encoding: gzip" -I "$BASE_URL/css/style.css")
    if echo "$headers" | grep -q "Content-Encoding.*gzip"; then
        print_pass "GZIP compression enabled"
    else
        print_warn "GZIP compression not detected"
    fi
}

# Test 11: Performance Check
test_performance() {
    print_test "Performance Check"
    
    # Test response time
    time_total=$(curl -s -o /dev/null -w "%{time_total}" "$BASE_URL/")
    time_ms=$(echo "$time_total * 1000" | bc 2>/dev/null | cut -d. -f1)
    
    if [ -n "$time_ms" ]; then
        if [ "$time_ms" -lt 1000 ]; then
            print_pass "Response time good (${time_ms}ms)"
        elif [ "$time_ms" -lt 3000 ]; then
            print_warn "Response time acceptable (${time_ms}ms)"
        else
            print_fail "Response time slow (${time_ms}ms)"
        fi
    else
        print_warn "Could not measure response time"
    fi
}

# Run all tests
main() {
    echo "Starting comprehensive deployment tests..."
    echo "Testing URL: $BASE_URL"
    echo
    
    test_basic_connectivity
    test_php_processing
    test_clean_urls
    test_static_files
    test_security_headers
    test_security_restrictions
    test_admin_panel
    test_upload_directory
    test_database_connection
    test_gzip_compression
    test_performance
    
    echo
    echo "=================================="
    echo "Test Results Summary"
    echo "=================================="
    echo -e "${GREEN}Passed: $PASSED${NC}"
    echo -e "${RED}Failed: $FAILED${NC}"
    echo
    
    if [ $FAILED -eq 0 ]; then
        echo -e "${GREEN}🎉 All tests passed! Your E-Commerce application is working correctly with Nginx.${NC}"
        exit 0
    else
        echo -e "${RED}⚠️  Some tests failed. Please check the issues above.${NC}"
        exit 1
    fi
}

# Check if curl is available
if ! command -v curl &> /dev/null; then
    echo "Error: curl is required but not installed."
    echo "Please install curl: sudo apt install curl"
    exit 1
fi

# Run tests
main
