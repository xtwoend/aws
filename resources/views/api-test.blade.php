@extends('layouts.tabler')

@section('title', 'API Testing')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">AWS Logger</div>
                <h2 class="page-title">API Testing</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <!-- API Documentation -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">API Endpoints</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Available Endpoints:</h4>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <span class="badge bg-success me-2">GET</span>
                                        <code>/api/health</code>
                                        <small class="text-muted d-block">Health check endpoint</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-primary me-2">POST</span>
                                        <code>/api/aws-logger/data</code>
                                        <small class="text-muted d-block">Submit single weather data</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-primary me-2">POST</span>
                                        <code>/api/aws-logger/bulk-data</code>
                                        <small class="text-muted d-block">Submit bulk weather data (max 100 records)</small>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-info me-2">GET</span>
                                        <code>/api/aws-logger/device/{deviceId}/status</code>
                                        <small class="text-muted d-block">Get device status and latest data</small>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <h4>Quick Actions:</h4>
                                <div class="btn-list">
                                    <button class="btn btn-success" onclick="testHealthCheck()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                        Test Health Check
                                    </button>
                                    <button class="btn btn-primary" onclick="testSingleData()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                        Send Test Data
                                    </button>
                                    <button class="btn btn-info" onclick="testDeviceStatus()">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        Check WS001 Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Tester Form -->
            <div class="col-lg-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">API Tester</h3>
                    </div>
                    <div class="card-body">
                        <form id="apiTestForm">
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Method</label>
                                    <select class="form-select" id="method" onchange="updateForm()">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <label class="form-label">Endpoint</label>
                                    <select class="form-select" id="endpoint" onchange="updateForm()">
                                        <option value="/api/health">Health Check</option>
                                        <option value="/api/aws-logger/data">Submit Data</option>
                                        <option value="/api/aws-logger/bulk-data">Submit Bulk Data</option>
                                        <option value="/api/aws-logger/device/WS001/status">Device Status</option>
                                    </select>
                                </div>
                            </div>
                            <div id="requestBodySection" style="display: none;">
                                <label class="form-label">Request Body (JSON)</label>
                                <textarea class="form-control" id="requestBody" rows="10" placeholder="Enter JSON data here..."></textarea>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary" onclick="sendRequest()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-4.5 -7l-7 -4.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                                    Send Request
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="clearResponse()">
                                    Clear Response
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Response Display -->
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Response</h3>
                        <div class="card-actions">
                            <span id="responseStatus" class="badge bg-secondary">Ready</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <pre id="responseBody" class="bg-light p-3 rounded" style="min-height: 200px; max-height: 400px; overflow-y: auto;">No response yet...</pre>
                    </div>
                </div>
            </div>

            <!-- Sample Payloads -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Sample Payloads</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4>Single Data Payload</h4>
                                <pre class="bg-light p-3 rounded"><code>{
  "terminal_time": "2025-07-22T10:30:00Z",
  "device_id": "WS001",
  "device_location": "Weather Station 1",
  "wind_speed": 5.2,
  "wind_direction": 180,
  "temperature": 25.6,
  "humidity": 65.3,
  "pressure": 1013.25,
  "par_sensor": 150.5,
  "rainfall": 0.0,
  "solar_radiation": 850.2,
  "lat": -6.200000,
  "lng": 106.816666
}</code></pre>
                            </div>
                            <div class="col-lg-6">
                                <h4>Bulk Data Payload</h4>
                                <pre class="bg-light p-3 rounded"><code>{
  "data": [
    {
      "terminal_time": "2025-07-22T10:30:00Z",
      "device_id": "WS001",
      "wind_speed": 5.2,
      "wind_direction": 180,
      "temperature": 25.6,
      "humidity": 65.3,
      "pressure": 1013.25,
      "rainfall": 0.0,
      "solar_radiation": 850.2
    },
    {
      "terminal_time": "2025-07-22T10:31:00Z",
      "device_id": "WS001",
      "wind_speed": 5.5,
      "wind_direction": 185,
      "temperature": 25.8,
      "humidity": 64.9,
      "pressure": 1013.30,
      "rainfall": 0.1,
      "solar_radiation": 860.5
    }
  ]
}</code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateForm() {
    const method = document.getElementById('method').value;
    const endpoint = document.getElementById('endpoint').value;
    const requestBodySection = document.getElementById('requestBodySection');
    const requestBody = document.getElementById('requestBody');
    
    // Update method based on endpoint
    if (endpoint.includes('/data') || endpoint.includes('/bulk-data')) {
        document.getElementById('method').value = 'POST';
        requestBodySection.style.display = 'block';
        
        // Set default payload
        if (endpoint.includes('/bulk-data')) {
            requestBody.value = JSON.stringify({
                "data": [
                    {
                        "terminal_time": new Date().toISOString(),
                        "device_id": "WS001",
                        "wind_speed": 5.2,
                        "wind_direction": 180,
                        "temperature": 25.6,
                        "humidity": 65.3,
                        "pressure": 1013.25,
                        "rainfall": 0.0,
                        "solar_radiation": 850.2
                    }
                ]
            }, null, 2);
        } else if (endpoint.includes('/data')) {
            requestBody.value = JSON.stringify({
                "terminal_time": new Date().toISOString(),
                "device_id": "WS001",
                "device_location": "Weather Station 1",
                "wind_speed": 5.2,
                "wind_direction": 180,
                "temperature": 25.6,
                "humidity": 65.3,
                "pressure": 1013.25,
                "par_sensor": 150.5,
                "rainfall": 0.0,
                "solar_radiation": 850.2,
                "lat": -6.200000,
                "lng": 106.816666
            }, null, 2);
        }
    } else {
        document.getElementById('method').value = 'GET';
        requestBodySection.style.display = 'none';
    }
}

async function sendRequest() {
    const method = document.getElementById('method').value;
    const endpoint = document.getElementById('endpoint').value;
    const requestBody = document.getElementById('requestBody').value;
    const responseStatus = document.getElementById('responseStatus');
    const responseBody = document.getElementById('responseBody');
    
    responseStatus.textContent = 'Loading...';
    responseStatus.className = 'badge bg-warning';
    
    try {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            }
        };
        
        if (method === 'POST' && requestBody) {
            options.body = requestBody;
        }
        
        const response = await fetch(endpoint, options);
        const data = await response.json();
        
        // Update status
        if (response.ok) {
            responseStatus.textContent = `${response.status} Success`;
            responseStatus.className = 'badge bg-success';
        } else {
            responseStatus.textContent = `${response.status} Error`;
            responseStatus.className = 'badge bg-danger';
        }
        
        // Display response
        responseBody.textContent = JSON.stringify(data, null, 2);
        
    } catch (error) {
        responseStatus.textContent = 'Network Error';
        responseStatus.className = 'badge bg-danger';
        responseBody.textContent = 'Error: ' + error.message;
    }
}

function clearResponse() {
    document.getElementById('responseStatus').textContent = 'Ready';
    document.getElementById('responseStatus').className = 'badge bg-secondary';
    document.getElementById('responseBody').textContent = 'No response yet...';
}

function testHealthCheck() {
    document.getElementById('method').value = 'GET';
    document.getElementById('endpoint').value = '/api/health';
    updateForm();
    sendRequest();
}

function testSingleData() {
    document.getElementById('method').value = 'POST';
    document.getElementById('endpoint').value = '/api/aws-logger/data';
    updateForm();
    sendRequest();
}

function testDeviceStatus() {
    document.getElementById('method').value = 'GET';
    document.getElementById('endpoint').value = '/api/aws-logger/device/WS001/status';
    updateForm();
    sendRequest();
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    updateForm();
});
</script>
@endpush
