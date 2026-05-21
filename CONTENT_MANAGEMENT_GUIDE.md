# ClinicOS - Content Management System Setup Guide

## 🎉 What's Been Updated

Your clinic booking system is now fully editable! The admin panel now allows you to manage all website content through a user-friendly interface. Here's what's been added:

### ✨ New Features

1. **Homepage Content Management** - Edit all text content on your homepage
2. **Theme & Branding** - Customize colors and fonts throughout the site
3. **Dynamic Services** - Add/edit/delete healthcare services
4. **Features Management** - Manage "Why Choose Us" section (up to 6 cards)
5. **Database-Driven Content** - All content is stored in the database and editable via admin panel

---

## 📋 Database Updates Required

Before using the new features, you need to update your database with the new tables:

### Option 1: Using PHPMyAdmin (Recommended)

1. Open PHPMyAdmin: `http://localhost/phpmyadmin`
2. Select your `clinic` database
3. Click "SQL" tab
4. Copy and paste the contents of `clinic.sql` file (entire file)
5. Click "Go" to execute

### Option 2: Using Command Line

```bash
mysql -u root -p clinic < clinic.sql
```

### Tables Added:

- `site_hero` - Hero section content (title, subtitle, buttons, stats)
- `site_sections` - Generic sections (Ready to Book, How It Works)
- `site_theme` - Color and font settings

---

## 🎨 Admin Panel Features

### 1. **Homepage Content Management**

Location: Admin > Homepage Content

#### Edit Sections:

- **Hero Section**: Title, subtitle, CTA buttons, stats
- **Why Choose Us**: Add up to 6 feature/benefit cards
- **Services**: Add unlimited healthcare services
- **Ready to Book**: CTA section tagline

#### Features:

- Add/Edit/Delete items
- Drag-and-drop sorting with sort order
- Rich text support for titles
- Icon/emoji support for visual appeal

### 2. **Theme & Branding**

Location: Admin > Theme & Branding

#### Customize:

- **Primary Color**: Main brand color (buttons, headers, accents)
- **Secondary Color**: Complementary color for elements
- **Accent Color**: Highlights and special elements
- **Text Color**: Body text color
- **Background Light**: Light background color
- **Primary Font**: Body text font
- **Heading Font**: Heading font

#### Live Preview:

- See changes in real-time as you edit
- Changes apply immediately to the entire website

---

## 📁 Files Added/Modified

### New Files Created:

```
admin/
├── homepage.php           ← New: Homepage content management
└── theme.php              ← New: Theme & branding management

public/api/
├── homepage_api.php       ← New: API for homepage CRUD
└── theme_api.php          ← New: API for theme management

updates.sql               ← New: Database schema additions (backup)
```

### Modified Files:

```
clinic.sql                ← Updated: Added new tables
public/index.php          ← Updated: Database-driven content
components/sidebar-admin.php ← Updated: Added new menu items
public/assets/css/admin.css   ← Updated: Added new styles
```

---

## 🚀 Getting Started

### Step 1: Update Database

1. Open PHPMyAdmin or terminal
2. Execute the updated `clinic.sql` file
3. Verify the new tables exist

### Step 2: Log In to Admin Panel

1. Go to: `http://localhost/clinic-updated/public/admin/login.php`
2. Username: `admin`
3. Password: `password123`

### Step 3: Edit Homepage Content

1. Navigate to **Homepage Content**
2. Edit Hero Section, Features, Services, etc.
3. Click "Save Changes"

### Step 4: Customize Theme

1. Navigate to **Theme & Branding**
2. Adjust colors using color pickers
3. Select fonts from dropdown
4. View live preview
5. Click "Save All Changes"

---

## 📝 Content Structure

### Hero Section

- **Pill Text**: Small label above title (e.g., "Welcome to ClinicOS")
- **Title**: Main headline (supports HTML: `<em>`, `<strong>`, `<span>`)
- **Subtitle**: Descriptive text
- **CTA Buttons**: Primary and secondary action buttons
- **Stats**: Show key metrics (happy patients, satisfaction rate)

### Why Choose Us

- **Max 6 Cards**: Feature/benefit cards highlighting clinic strengths
- **Components**: Icon, Title, Description
- **Editable via Admin**: Add/edit/delete cards easily

### Services

- **Unlimited**: Add as many services as needed
- **Components**: Icon, Badge, Title, Description
- **Sorting**: Control display order

### Other Sections

- **How It Works**: Locked (non-editable) - Shows process steps
- **Ready to Book**: CTA section at bottom with customizable tagline

---

## 🎯 Best Practices

### Content Management

1. ✅ Use clear, concise titles and descriptions
2. ✅ Use appropriate emojis/icons for visual appeal
3. ✅ Keep feature descriptions to 1-2 sentences
4. ✅ Use sort order to control display sequence
5. ✅ Test changes by viewing homepage

### Theme Customization

1. ✅ Use colors that work well together
2. ✅ Ensure good contrast for readability
3. ✅ Test on mobile devices
4. ✅ Keep font choices professional
5. ✅ Preview before saving

### Editing Tips

- Use `<em>` tags for emphasis in titles
- Use `<strong>` for bold text
- Use `<span class="line-accent">` for accent color highlighting
- Emoji and Unicode characters work great for icons
- Save frequently to avoid data loss

---

## 🔧 API Documentation

### Homepage API

**Endpoint**: `public/api/homepage_api.php`

#### Actions:

- `read_hero` - Get hero section data
- `update_hero` - Update hero section
- `read_features` - Get all features
- `add_feature` - Add new feature
- `update_feature` - Update feature
- `delete_feature` - Delete feature
- `read_services` - Get all services
- `add_service` - Add service
- `update_service` - Update service
- `delete_service` - Delete service
- `read_section` - Get section by key
- `update_section` - Update section

### Theme API

**Endpoint**: `public/api/theme_api.php`

#### Actions:

- `read_all` - Get all theme settings
- `read_by_type` - Get settings by type (color/font)
- `update` - Update theme setting
- `generate_css` - Generate CSS variables from theme

---

## 🌐 Frontend Integration

### Dynamic Content Loading

The homepage (`public/index.php`) now:

1. Connects to database on page load
2. Fetches all content from tables
3. Displays editable content
4. Loads theme CSS dynamically
5. Falls back to defaults if database unavailable

### Theme Application

- Theme settings are converted to CSS variables
- Applied globally across the website
- Update instantly without page reload (when using admin)

---

## 🛠️ Troubleshooting

### Database Connection Issues

**Problem**: "Database connection failed"

- Check `includes/config.php` settings
- Verify MySQL is running
- Confirm database name is `clinic`

### Changes Not Appearing

**Problem**: Updated content not showing on homepage

- Clear browser cache (Ctrl+Shift+Delete)
- Verify database connection
- Check if feature/service is marked as active (is_active = 1)

### Styling Issues

**Problem**: Theme colors not applying

- Hard refresh browser (Ctrl+F5)
- Check browser console for errors
- Verify theme API is accessible

### Admin Pages Not Loading

**Problem**: 404 error when accessing admin pages

- Verify files exist in `admin/` folder
- Check file permissions
- Verify web server is running

---

## 📊 Database Schema

### site_hero

```sql
- id (PK)
- hero_pill_text
- hero_title
- hero_subtitle
- hero_image_path
- cta_button_text
- cta_button_link
- secondary_button_text
- secondary_button_link
- stat1_number
- stat1_label
- stat2_number
- stat2_label
- updated_at
```

### site_sections

```sql
- id (PK)
- section_key (UNIQUE)
- title
- subtitle
- description
- tagline
- is_editable
- updated_at
```

### site_theme

```sql
- id (PK)
- theme_key (UNIQUE)
- theme_value
- theme_type (color|font|spacing|other)
- updated_at
```

---

## 🔒 Security Notes

- All user inputs are sanitized using htmlspecialchars()
- Database queries use prepared statements (PDO)
- Authentication is required to access admin pages
- File uploads are not enabled (prevent potential issues)

---

## 📞 Support

For issues or questions:

1. Check the troubleshooting section above
2. Verify database tables exist
3. Check browser console for errors
4. Ensure all files are in correct locations
5. Restart web server if needed

---

## ✅ Verification Checklist

Before considering setup complete:

- [ ] Database updated successfully
- [ ] Can login to admin panel
- [ ] Can see new menu items (Homepage Content, Theme & Branding)
- [ ] Can edit and save hero section
- [ ] Can add/edit features
- [ ] Can add/edit services
- [ ] Can change theme colors
- [ ] Theme changes appear on homepage
- [ ] Homepage shows database content (not static)
- [ ] Navigation and footer still work normally

---

## 🎓 Next Steps

1. **Customize Your Clinic Info**
   - Update hero section with your clinic name
   - Add your clinic's unique value propositions
   - Set appropriate colors matching your brand

2. **Add Your Services**
   - List all services your clinic offers
   - Use appropriate icons
   - Write compelling descriptions

3. **Set Your Brand Theme**
   - Choose primary color matching your logo/brand
   - Select complementary secondary color
   - Choose professional fonts

4. **Configure Features**
   - Add reasons patients should choose your clinic
   - Highlight unique strengths
   - Maximum 6 items for best design

5. **Test Everything**
   - View homepage to verify all changes
   - Test on mobile devices
   - Check all links and buttons work
   - Verify theme colors are applied

---

**Version**: 1.0  
**Last Updated**: May 20, 2026  
**Compatibility**: PHP 7.4+, MySQL 5.7+
