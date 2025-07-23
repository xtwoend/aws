# AWS Logger API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
Currently, the API endpoints are public. In production, consider implementing API key authentication.

## Endpoints

### 1. Health Check
**GET** `/health`

Check if the API is running properly.

**Response:**
```json
{
    "status": "ok",
    "service": "AWS Dashboard API",
    "timestamp": "2025-07-22T10:30:00.000Z",
    "version": "1.0.0"
}
```

### 2. Receive Single Weather Data
**POST** `/aws-logger/data`

Submit a single weather data record from an AWS Logger device.

**Request Body:**
```json
{
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
}
```

**Required Fields:**
- `terminal_time`: ISO 8601 date string, not in the future
- `device_id`: String (1-255 chars), alphanumeric with underscore/dash only
- `wind_speed`: Number (0-999.99)
- `wind_direction`: Integer (0-360)
- `temperature`: Number (-50 to 100)
- `humidity`: Number (0-100)
- `pressure`: Number (800-1200)
- `rainfall`: Number (0-999.99)
- `solar_radiation`: Number (0-9999.99)

**Optional Fields:**
- `device_location`: String (max 255 chars)
- `par_sensor`: Number (0-9999.99)
- `lat`: Number (-90 to 90)
- `lng`: Number (-180 to 180)

**Success Response (201):**
```json
{
    "status": "success",
    "message": "Data received and stored successfully",
    "data": {
        "id": 123,
        "device_id": "WS001",
        "terminal_time": "2025-07-22T10:30:00.000Z",
        "created_at": "2025-07-22T10:35:00.000Z"
    }
}
```

**Error Response (422):**
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "temperature": ["Temperature must be a number"],
        "device_id": ["Device ID is required"]
    },
    "error_code": "VALIDATION_ERROR"
}
```

### 3. Receive Bulk Weather Data
**POST** `/aws-logger/bulk-data`

Submit multiple weather data records at once (max 100 records).

**Request Body:**
```json
{
    "data": [
        {
            "terminal_time": "2025-07-22T10:30:00Z",
            "device_id": "WS001",
            "temperature": 25.6,
            "humidity": 65.3,
            // ... other required fields
        },
        {
            "terminal_time": "2025-07-22T10:31:00Z",
            "device_id": "WS001",
            "temperature": 25.8,
            "humidity": 64.9,
            // ... other required fields
        }
    ]
}
```

**Success Response (200):**
```json
{
    "status": "success",
    "message": "Bulk data processed: 2 successful, 0 failed",
    "summary": {
        "total_received": 2,
        "success_count": 2,
        "error_count": 0,
        "devices_updated": 1
    }
}
```

### 4. Get Device Status
**GET** `/aws-logger/device/{deviceId}/status`

Get the current status and latest data for a specific device.

**Parameters:**
- `deviceId`: The device ID (e.g., WS001)

**Success Response (200):**
```json
{
    "status": "success",
    "device_id": "WS001",
    "device_info": {
        "name": "Weather Station 1",
        "location": "Jakarta Selatan",
        "type": "AWS Logger",
        "status": true
    },
    "online_status": {
        "is_online": true,
        "last_seen": "2025-07-22T10:30:00.000Z",
        "minutes_since_last_data": 5
    },
    "latest_data": {
        "terminal_time": "2025-07-22T10:30:00.000Z",
        "temperature": 25.6,
        "humidity": 65.3,
        "wind_speed": 5.2,
        "wind_direction": 180,
        "pressure": 1013.25,
        "rainfall": 0.0,
        "solar_radiation": 850.2,
        "location": {
            "lat": -6.200000,
            "lng": 106.816666
        }
    }
}
```

**Device Not Found (404):**
```json
{
    "status": "error",
    "message": "No data found for device",
    "device_id": "WS999"
}
```

## Error Codes

- `VALIDATION_ERROR`: Request validation failed
- `INTERNAL_ERROR`: Server error during single data processing
- `BULK_INTERNAL_ERROR`: Server error during bulk data processing  
- `DEVICE_STATUS_ERROR`: Error retrieving device status

## Data Validation Rules

### Numeric Ranges:
- **Wind Speed**: 0 - 999.99 m/s
- **Wind Direction**: 0 - 360 degrees
- **Temperature**: -50 to 100°C
- **Humidity**: 0 - 100%
- **Pressure**: 800 - 1200 hPa
- **Rainfall**: 0 - 999.99 mm
- **Solar Radiation**: 0 - 9999.99 W/m²
- **PAR Sensor**: 0 - 9999.99 (optional)
- **Latitude**: -90 to 90 degrees (optional)
- **Longitude**: -180 to 180 degrees (optional)

### String Rules:
- **Device ID**: 1-255 characters, alphanumeric + underscore/dash only
- **Device Location**: Max 255 characters (optional)

### Date Rules:
- **Terminal Time**: Valid ISO 8601 date, not in the future

## Testing Examples

### Using curl:

**Single Data:**
```bash
curl -X POST http://localhost:8000/api/aws-logger/data \
  -H "Content-Type: application/json" \
  -d '{
    "terminal_time": "2025-07-22T10:30:00Z",
    "device_id": "WS001",
    "wind_speed": 5.2,
    "wind_direction": 180,
    "temperature": 25.6,
    "humidity": 65.3,
    "pressure": 1013.25,
    "rainfall": 0.0,
    "solar_radiation": 850.2
  }'
```

**Get Device Status:**
```bash
curl http://localhost:8000/api/aws-logger/device/WS001/status
```

**Health Check:**
```bash
curl http://localhost:8000/api/health
```

## Notes

1. All timestamps should be in ISO 8601 format with timezone information
2. Numeric values should be provided as numbers, not strings
3. Device IDs are case-sensitive
4. The API automatically updates device "last seen" timestamps
5. All requests are logged for debugging purposes
6. Bulk operations are limited to 100 records per request for performance
