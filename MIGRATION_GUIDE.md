# 🔄 Migration Commands Guide

## migrate:fresh Command

The `migrate:fresh` command provides a complete database reset functionality, similar to Laravel's fresh migration feature.

### 🎯 What it does:

1. **🗑️ Drops all tables** - Completely removes all existing database tables
2. **🔄 Re-runs migrations** - Executes all migration files from scratch
3. **🌱 Optional seeding** - Can run database seeders after migrations

### 🚀 Usage Examples:

```bash
# Basic fresh migration - drops all tables and re-runs migrations
php retrina migrate:fresh

# Fresh migration with seeders - also creates demo users
php retrina migrate:fresh --seed

# Force run in production (use with extreme caution!)
php retrina migrate:fresh --force
```

### 🛡️ Safety Features:

- **Production Protection**: Requires `--force` flag in production environments
- **Multi-Database Support**: Works with MySQL, SQLite, and PostgreSQL
- **Foreign Key Handling**: Properly disables/enables foreign key constraints
- **Error Handling**: Graceful error messages and rollback on failure

### 📊 Expected Output:

```
🗑️  Dropping all tables...
   Dropping table: users
   Dropping table: migrations
🔄 Running fresh migrations...
✅ Running migration: CreateUsersTable
✅ Fresh migration completed successfully!
🌱 Running database seeders... (if --seed used)
🧑‍💼 Creating admin and user accounts...
   ✅ Admin user created (admin/admin123)
   ✅ Regular user created (user/user123)
✅ Seeders completed successfully!
```

### ⚠️ Important Notes:

- **DATA LOSS**: This command will destroy ALL existing data
- **Development Use**: Primarily intended for development environments
- **Backup First**: Always backup production data before using `--force`
- **Clean Slate**: Perfect for testing with fresh, known data state

### 🎯 Perfect for:

- 🔧 Development environment resets
- 🧪 Testing with clean data
- 📝 Applying new migration structures
- 🎭 Setting up demo environments
- 🔄 Fixing migration conflicts

### 🔗 Related Commands:

```bash
# Regular migration (non-destructive)
php retrina migrate

# Create new migration
php retrina make:migration create_posts_table

# Run only seeders
php retrina db:seed

# Create new seeder
php retrina make:seeder PostSeeder
```

### 💡 Pro Tips:

1. Use `--seed` flag to get a ready-to-use database with demo users
2. Run this command when you change migration structure
3. Great for onboarding new developers - gives them a clean database
4. Use in CI/CD pipelines for testing with fresh data

---

## 🧑‍💼 Demo Users (when using --seed):

After running `migrate:fresh --seed`, you'll have:

**Admin User:**
- Username: `admin`
- Password: `admin123`
- Email: `admin@retrina.local`
- Role: `admin`

**Regular User:**
- Username: `user`
- Password: `user123`
- Email: `user@retrina.local`
- Role: `user`

Both accounts are active and email-verified, ready for testing your authentication system! 