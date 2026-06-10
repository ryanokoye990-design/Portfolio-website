# Backend Security & Setup Documentation

## Overview
Your portfolio website backend has been improved with better security practices and functionality.

## Security Features Implemented

### 1. Input Validation & Sanitization ✅
- All user inputs are validated before processing
- XSS protection through HTML entity encoding
- Email validation using PHP's `filter_var()`
- Message length validation (10-5000 characters)

### 2. SQL Injection Prevention ✅
- All database queries use prepared statements
- Input parameters are bound using parameterized queries
- No direct string concatenation in SQL queries

### 3. CORS Configuration ✅
- Configured to allow all origins for development
- Can be easily restricted to specific domains in production
- Configured in `backend/config/cors.php`

### 4. Error Handling ✅
- Errors logged to server logs for debugging
- Generic error messages returned to frontend
- HTTP status codes properly implemented

### 5. Request Logging ✅
- All API requests are logged to `backend/logs/api.log`
- Includes timestamp, IP, endpoint, and status code

### 6. Configuration Management ✅
- Sensitive credentials can be stored in `.env` file
- `.env` file excluded from git repository

## File Structure

```
backend/
├── config/
│   ├── db.php              # Database connection
│   └── cors.php            # CORS configuration
├── api/
│   ├── get_projects.php    # Fetch projects API
│   └── send_message.php    # Send message API
├── utils/
│   └── security.php        # Security utility functions
├── setup/
│   └── database.sql        # Database schema
├── logs/                   # API logs (auto-created)
├── .env.example            # Environment variables template
├── .gitignore              # Git ignore file
└── SECURITY.md             # This file
```

## Setup Instructions

### 1. Database Setup
```bash
# Using MySQL command line
mysql -u root -p < backend/setup/database.sql

# Or import through phpMyAdmin:
# 1. Go to phpMyAdmin
# 2. Click "Import"
# 3. Select backend/setup/database.sql
# 4. Click "Go"
```

### 2. Environment Configuration (Optional)
```bash
# Copy the example environment file
cp backend/.env.example backend/.env

# Edit with your actual credentials
nano backend/.env
```

### 3. Run Locally
```bash
# Using PHP built-in server
cd your-project-root
php -S localhost:8000

# Website will be available at http://localhost:8000
```

## API Endpoints

### Get Projects
- **Endpoint**: `/backend/api/get_projects.php`
- **Method**: GET
- **Response**: JSON array of projects
```json
[
  {
    "id": 1,
    "title": "Project Title",
    "description": "Project Description",
    "image": "image_url",
    "link": "project_link"
  }
]
```

### Send Message
- **Endpoint**: `/backend/api/send_message.php`
- **Method**: POST
- **Content-Type**: application/x-www-form-urlencoded or application/json
- **Parameters**:
  - `name` (required): Sender's name
  - `email` (required): Sender's email
  - `message` (required): Message text (10-5000 characters)

**Response**:
```json
{
  "success": true,
  "message": "Message sent successfully"
}
```

## JavaScript Integration

Update your `js/script.js` to use the new APIs:

```javascript
// Fetch projects
fetch('backend/api/get_projects.php')
  .then(response => response.json())
  .then(projects => {
    // Display projects
    console.log(projects);
  });

// Send message
const formData = new FormData();
formData.append('name', 'John Doe');
formData.append('email', 'john@example.com');
formData.append('message', 'Your message here');

fetch('backend/api/send_message.php', {
  method: 'POST',
  body: formData
})
.then(response => response.json())
.then(data => {
  console.log(data);
});
```

## Database Schema

### projects table
- `id`: Auto-increment primary key
- `title`: Project title (255 chars)
- `description`: Project description
- `image`: Image URL
- `link`: Project link
- `published`: Boolean flag (1 = published, 0 = hidden)
- `created_at`: Timestamp
- `updated_at`: Last updated timestamp

### messages table
- `id`: Auto-increment primary key
- `name`: Sender name (255 chars)
- `email`: Sender email (255 chars)
- `message`: Message content
- `is_read`: Boolean flag (0 = unread, 1 = read)
- `created_at`: Timestamp

## Troubleshooting

### "Database connection failed" error
- Check MySQL is running
- Verify database credentials in `.env` or `db.php`
- Ensure database `portfolio_db` exists

### CORS errors in browser console
- CORS is configured to allow all origins
- If you need to restrict domains, edit `backend/config/cors.php`

### API returns 500 error
- Check `backend/logs/api.log` for detailed error messages
- Ensure all required POST parameters are sent
- Verify database tables exist

### Logs directory permission error
```bash
mkdir -p backend/logs
chmod 755 backend/logs
```

## Best Practices

1. Always use prepared statements for database queries
2. Validate and sanitize all user inputs
3. Use the utility functions from `backend/utils/security.php`
4. Regularly check API logs for errors
5. Keep your PHP version updated
6. Use HTTPS in production

## Security Notes for Production

When deploying to production:
1. Change database passwords
2. Restrict CORS to only your domain
3. Enable HTTPS
4. Consider adding authentication
5. Set up proper error logging
6. Regularly backup your database
7. Update all PHP dependencies

## Support & Debugging

For debugging, check:
- `backend/logs/api.log` - API request logs
- PHP error logs (server-specific location)
- Browser console for client-side errors
- Network tab in browser DevTools

## Questions?
Refer to the inline comments in each PHP file for detailed explanations of the code.
