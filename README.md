# Portfolio Website

A modern, responsive portfolio website built with HTML5, CSS3, JavaScript frontend and PHP backend.

## Features

- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Modern UI**: Beautiful gradient designs and smooth animations
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Backend**: PHP with MySQL database
- **Dynamic Projects**: Load projects from database
- **Contact Form**: Send messages with validation and database storage
- **Mobile Menu**: Hamburger menu for mobile navigation

## Project Structure

```
portfolio-website/
├── index.html                 # Main HTML file
├── css/
│   └── style.css             # Styling
├── js/
│   └── script.js             # Frontend JavaScript
├── backend/
│   ├── config/
│   │   └── db.php            # Database configuration
│   ├── api/
│   │   ├── get_projects.php  # Get projects API
│   │   └── send_message.php  # Send message API
│   └── setup/
│       └── database.sql      # Database schema
└── README.md
```

## Setup Instructions

### 1. Database Setup

- Open phpMyAdmin or your MySQL client
- Run the SQL from `backend/setup/database.sql` to create the database and tables
- Or import the SQL file directly

### 2. Update Database Credentials

Edit `backend/config/db.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASSWORD', 'your_password');
define('DB_NAME', 'portfolio_db');
```

### 3. Email Configuration (Optional)

To enable email notifications when messages are received:
- Uncomment the mail() function in `backend/api/send_message.php`
- Update the email address in the `$to` variable

### 4. Run Locally

```bash
# Using PHP built-in server
php -S localhost:8000

# Or use Apache/Nginx
```

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Backend**: PHP 7.0+
- **Database**: MySQL 5.7+
- **Icons**: Font Awesome 6
- **Design Pattern**: Responsive Mobile-First

## Customization

### Add Projects

Insert new projects directly into the database:

```sql
INSERT INTO projects (title, description, image, link, published)
VALUES ('Project Title', 'Project Description', 'image_url', 'project_link', 1);
```

### Modify Styling

Edit `css/style.css` to customize colors, fonts, and layouts.

### Update Content

Edit `index.html` to update personal information, about section, and skills.

## Browser Support

- Chrome (Latest)
- Firefox (Latest)
- Safari (Latest)
- Edge (Latest)
- Mobile browsers

## License

This project is open source and available under the MIT License.

## Author

Ryan Okoye - Full Stack Developer
