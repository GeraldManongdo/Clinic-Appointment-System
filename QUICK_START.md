# ⚡ Quick Start Guide - ClinicOS Content Management

## 🚀 5-Minute Setup

### Step 1: Update Database (1 minute)

1. Open **PHPMyAdmin**: `http://localhost/phpmyadmin`
2. Select **clinic** database
3. Click **SQL** tab
4. Open **clinic.sql** file from project folder
5. Copy-paste all contents into SQL editor
6. Click **Go** ✓

### Step 2: Access Admin Panel (1 minute)

1. Go to: `http://localhost/clinic-updated/public/admin/login.php`
2. Username: `admin`
3. Password: `password123`
4. Click **Login** ✓

### Step 3: Edit Homepage (2 minutes)

1. Click **Homepage Content** in left menu
2. Click **Hero Section** tab
3. Edit Title, Subtitle, Button Text
4. Click **Save Changes** ✓

### Step 4: Customize Theme (1 minute)

1. Click **Theme & Branding** in left menu
2. Change primary color (click color picker)
3. Click **Save All Changes** ✓
4. View homepage to see changes!

---

## 📚 Main Features

### ✏️ Edit Hero Section

- Title (with HTML support)
- Subtitle
- Button text and links
- Statistics display

### 🎨 Manage Features (Why Choose Us)

- Add up to 6 feature cards
- Each with icon, title, description
- Easily reorder and delete

### 🏥 Add Services

- Unlimited healthcare services
- Icon, category, title, description
- Control display order

### 🎨 Customize Theme

- Change primary/secondary/accent colors
- Select body and heading fonts
- Live preview as you edit

---

## 🔗 Important Links

| Purpose              | URL                                                      |
| -------------------- | -------------------------------------------------------- |
| **Homepage**         | `http://localhost/clinic-updated/public/`                |
| **Admin Login**      | `http://localhost/clinic-updated/public/admin/login.php` |
| **Homepage Content** | `/admin/homepage.php`                                    |
| **Theme & Branding** | `/admin/theme.php`                                       |

---

## 📝 Common Tasks

### Add a New Service

1. Go to **Homepage Content** → **Services** tab
2. Click **Add Service** button
3. Fill in Icon, Category, Title, Description
4. Click **Save Service** ✓

### Change Site Colors

1. Go to **Theme & Branding** → **Colors** tab
2. Click color box next to "PRIMARY COLOR"
3. Choose your color
4. Click **Save All Changes** ✓

### Update Hero Section

1. Go to **Homepage Content** → **Hero Section** tab
2. Edit any field you want to change
3. Click **Save Changes** ✓

### Add "Why Choose Us" Feature

1. Go to **Homepage Content** → **Why Choose Us** tab
2. Click **Add Feature** button
3. Enter icon (emoji), title, description
4. Click **Save Feature** ✓

---

## 💡 Tips

- 🎯 Use emojis for icons: 👨‍⚕️ ⚕️ 🏥 💊 🩺 ❤️
- 🎨 Test colors in **Preview** tab before saving
- 💾 Always click **Save** after making changes
- 🔄 Refresh homepage to see updates
- 📱 Test on mobile to verify responsive design

---

## ✅ Verification

After setup, verify:

- [ ] Can login to admin panel
- [ ] Can see new menu items
- [ ] Can edit and save hero section
- [ ] Colors change on homepage when theme updated
- [ ] New services appear on homepage

---

## 🆘 Quick Troubleshooting

| Issue                 | Solution                                                     |
| --------------------- | ------------------------------------------------------------ |
| Can't login           | Check username/password, verify database connection          |
| Changes not showing   | Clear browser cache (Ctrl+Shift+Del), hard refresh (Ctrl+F5) |
| Database error        | Verify `clinic.sql` was fully executed in PHPMyAdmin         |
| Styling looks broken  | Check if theme CSS is loading, try different browser         |
| Can't find menu items | Scroll down in admin sidebar if needed                       |

---

## 📞 Need Help?

1. Check the full **CONTENT_MANAGEMENT_GUIDE.md** for detailed documentation
2. Verify database tables exist in PHPMyAdmin
3. Check browser console for JavaScript errors
4. Ensure all database queries executed successfully

---

**Let's go! Your clinic website is now fully editable!** 🎉
