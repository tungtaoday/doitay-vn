# THUMBSTACK PLATFORM TEST EXECUTION SCRIPT
# Created by AI Assistant for comprehensive testing

Write-Host "STARTING THUMBSTACK PLATFORM TESTS" -ForegroundColor Green
Write-Host "=================================================" -ForegroundColor Green

# Test Configuration
$BaseUrl = "http://127.0.0.1:8000"
$TestResults = @()
$TotalTests = 0
$PassedTests = 0
$FailedTests = 0

# Test Helper Function
function Test-Endpoint {
    param(
        [string]$TestName,
        [string]$Url,
        [string]$ExpectedStatus = "200",
        [string]$Method = "GET",
        [hashtable]$Headers = @{},
        [string]$Body = $null
    )
    
    $global:TotalTests++
    Write-Host "Testing: $TestName" -ForegroundColor Yellow
    
    try {
        $params = @{
            Uri = $Url
            Method = $Method
            Headers = $Headers
            UseBasicParsing = $true
        }
        
        if ($Body) {
            $params.Body = $Body
            $params.ContentType = "application/json"
        }
        
        $response = Invoke-WebRequest @params -ErrorAction Stop
        
        if ($response.StatusCode -eq $ExpectedStatus) {
            Write-Host "PASS: $TestName" -ForegroundColor Green
            $global:PassedTests++
            $result = @{
                Test = $TestName
                Status = "PASS"
                ResponseCode = $response.StatusCode
                ResponseTime = "N/A"
            }
        } else {
            Write-Host "FAIL: $TestName - Expected $ExpectedStatus but got $($response.StatusCode)" -ForegroundColor Red
            $global:FailedTests++
            $result = @{
                Test = $TestName
                Status = "FAIL"
                ResponseCode = $response.StatusCode
                Error = "Unexpected status code"
            }
        }
    }
    catch {
        Write-Host "FAIL: $TestName - $($_.Exception.Message)" -ForegroundColor Red
        $global:FailedTests++
        $result = @{
            Test = $TestName
            Status = "FAIL"
            Error = $_.Exception.Message
        }
    }
    
    $global:TestResults += $result
    Start-Sleep -Milliseconds 500
}

# TC-001: Basic Website Accessibility Tests
Write-Host "`nTC-001: BASIC WEBSITE ACCESSIBILITY" -ForegroundColor Cyan
Test-Endpoint "Homepage Load" "$BaseUrl"
Test-Endpoint "User Login Page" "$BaseUrl/user/login"
Test-Endpoint "User Register Page" "$BaseUrl/user/register"
Test-Endpoint "Company Search Page" "$BaseUrl/company/all"
Test-Endpoint "Blog Page" "$BaseUrl/blog"

# TC-002: User Registration & Authentication Flow
Write-Host "`nTC-002: AUTHENTICATION SYSTEM" -ForegroundColor Cyan
Test-Endpoint "Registration Form Load" "$BaseUrl/user/register"
Test-Endpoint "Login Form Load" "$BaseUrl/user/login"

# TC-003: Customer Lead Management System
Write-Host "`nTC-003: CUSTOMER LEAD SYSTEM" -ForegroundColor Cyan
Test-Endpoint "Customer Dashboard Access" "$BaseUrl/user/dashboard" "302"
Test-Endpoint "Lead Creation Form Access" "$BaseUrl/user/customer/leads/create" "302"
Test-Endpoint "Lead List Access" "$BaseUrl/user/customer/leads" "302"

# TC-004: Notification System Endpoints
Write-Host "`nTC-004: NOTIFICATION SYSTEM" -ForegroundColor Cyan
Test-Endpoint "Notification Count API" "$BaseUrl/user/notifications/unread-count" "302"

# TC-005: Company & Contractor System
Write-Host "`nTC-005: COMPANY SYSTEM" -ForegroundColor Cyan
Test-Endpoint "Company Search" "$BaseUrl/company"
Test-Endpoint "Company Filter" "$BaseUrl/company/filter"
Test-Endpoint "All Companies" "$BaseUrl/company/all"

# TC-006: Location API Tests
Write-Host "`nTC-006: LOCATION API" -ForegroundColor Cyan
Test-Endpoint "Cities API" "$BaseUrl/localtion/api/cities"
Test-Endpoint "Districts API" "$BaseUrl/localtion/api/districts/01"

# TC-007: Static Pages & Content
Write-Host "`nTC-007: STATIC CONTENT" -ForegroundColor Cyan
Test-Endpoint "About Page" "$BaseUrl/about/doitay"
Test-Endpoint "Contact Page" "$BaseUrl/contact"
Test-Endpoint "Cookie Policy" "$BaseUrl/cookie-policy"

# TC-008: Support Ticket System
Write-Host "`nTC-008: SUPPORT SYSTEM" -ForegroundColor Cyan
Test-Endpoint "Support Ticket Page" "$BaseUrl/ticket"
Test-Endpoint "New Ticket Form" "$BaseUrl/ticket/new"

# Generate Test Report
Write-Host "`nTEST EXECUTION SUMMARY" -ForegroundColor Magenta
Write-Host "=================================" -ForegroundColor Magenta
Write-Host "Total Tests: $TotalTests" -ForegroundColor White
Write-Host "Passed: $PassedTests" -ForegroundColor Green
Write-Host "Failed: $FailedTests" -ForegroundColor Red

$successRate = [math]::Round(($PassedTests / $TotalTests) * 100, 2)
Write-Host "Success Rate: $successRate%" -ForegroundColor $(if($successRate -gt 80) {"Green"} else {"Red"})

# Export detailed results
$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$reportFile = "test_results_$timestamp.json"
$TestResults | ConvertTo-Json -Depth 3 | Out-File $reportFile

Write-Host "`nDetailed results exported to: $reportFile" -ForegroundColor Blue

# Test Status
if ($successRate -gt 90) {
    Write-Host "`nEXCELLENT! Platform is ready for production" -ForegroundColor Green
} elseif ($successRate -gt 80) {
    Write-Host "`nGOOD! Minor issues need attention" -ForegroundColor Yellow
} else {
    Write-Host "`nCRITICAL! Major issues require immediate fixing" -ForegroundColor Red
}

Write-Host "`nTest execution completed!" -ForegroundColor Green 