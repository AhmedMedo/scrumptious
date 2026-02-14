# Notification System Implementation - Complete Guide

## 📋 Overview

A comprehensive notification system has been implemented with support for:
- **Firebase Cloud Messaging (FCM)** - Push notifications to mobile/web
- **Email Notifications** - Mailable classes for each notification type
- **Database Storage** - All notifications stored for user history
- **Admin Panel** - Filament resources for managing notifications and broadcasts

## ✅ Implementation Status

### **COMPLETED PHASES**

#### Phase 1: Database & Entity Layer ✅
- ✅ `2026_02_14_192423_create_notifications_table.php`
- ✅ `2026_02_14_192429_create_user_device_tokens_table.php`
- ✅ `2026_02_14_192430_create_admin_broadcasts_table.php`
- ✅ `NotificationEntity`, `UserDeviceTokenEntity`, `AdminBroadcastEntity`
- ✅ `NotificationTypeEnum`, `DeviceTypeEnum`, `BroadcastStatusEnum`

#### Phase 2: Component Architecture ✅
- ✅ Complete DDD-inspired structure
- ✅ DTOs: `NotificationDto`, `SendNotificationDto`
- ✅ Exceptions: `NotificationException`, `FcmException`

#### Phase 3: Core Services ✅
- ✅ `FcmService` - Firebase integration
- ✅ `EmailNotificationService` - Email sending
- ✅ `NotificationService` - Main orchestration
- ✅ Repositories & Queries for all entities
- ✅ Firebase SDK installed (`kreait/laravel-firebase`)

#### Phase 4: HTTP API Layer ✅
- ✅ 6 API endpoints with OpenAPI documentation
- ✅ Request validation classes
- ✅ Routes configured with auth middleware

#### Phase 5: Events & Listeners ✅
- ✅ `MealPlanCustomizedEvent` → `SendMealPlanCustomizedNotification`
- ✅ `TargetReminderEvent` → `SendTargetReminderNotification`
- ✅ `NewRecipeUploadedEvent` → `SendNewRecipeNotification`
- ✅ `AdminBroadcastEvent` → `SendAdminBroadcastNotification`

#### Phase 6: Admin Panel (Filament) ✅
- ✅ `NotificationEntityResource` - View/manage all notifications
- ✅ `AdminBroadcastEntityResource` - Create and send broadcasts

#### Phase 7-8: Jobs & Scheduler ✅
- ✅ `SendFcmNotificationJob`, `SendEmailNotificationJob`
- ✅ `ProcessTargetRemindersJob`, `ProcessScheduledBroadcastsJob`
- ✅ Console commands registered
- ✅ Scheduler configured in `routes/console.php`

#### Phase 9: Service Provider ✅
- ✅ `NotificationServiceProvider` created
- ✅ Registered in `bootstrap/providers.php`
- ✅ All bindings configured

## 🎯 Notification Scenarios Covered

### 1. Customize Meal Plan
**Trigger:** When a meal plan is customized
```php
use App\Components\Notification\Infrastructure\Events\MealPlanCustomizedEvent;

MealPlanCustomizedEvent::dispatch(
    userUuid: $user->uuid,
    planUuid: $plan->uuid,
    planName: $plan->name,
    customizationDetails: []
);
```

### 2. Target Reminder
**Trigger:** Daily scheduler checks targets ending soon
```bash
php artisan notifications:send-target-reminders
```
**Scheduled:** Daily at midnight

### 3. New Recipe Upload
**Trigger:** When a new recipe is created
```php
use App\Components\Notification\Infrastructure\Events\NewRecipeUploadedEvent;

NewRecipeUploadedEvent::dispatch(
    recipeUuid: $recipe->uuid,
    recipeName: $recipe->name,
    recipeDescription: $recipe->description,
    categories: $recipe->categories->pluck('name')->toArray()
);
```

### 4. Admin Broadcast Messages
**Trigger:** From Filament admin panel or programmatically
- Create broadcast in admin panel
- Schedule for later or send immediately
- Target all users or specific users

## 📡 API Endpoints

All endpoints require `Authorization: Bearer {token}` header.

### Device Token Management
```http
POST /api/notifications/register-device
Content-Type: application/json

{
  "device_token": "fcm_token_here",
  "device_type": "ios|android|web",
  "device_name": "iPhone 13"
}
```

### Get Notifications
```http
GET /api/notifications?per_page=20&unread_only=true
```

### Get Unread Count
```http
GET /api/notifications/unread-count
```

### Mark as Read
```http
PATCH /api/notifications/{uuid}/read
```

### Mark All as Read
```http
PATCH /api/notifications/read-all
```

### Delete Notification
```http
DELETE /api/notifications/{uuid}
```

## 🔧 Configuration Required

### 1. Firebase Setup

Create a Firebase project and download the service account JSON file.

**Update `.env`:**
```env
FIREBASE_CREDENTIALS=/path/to/firebase-credentials.json
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_DATABASE_URL=https://your-project.firebaseio.com
FIREBASE_STORAGE_BUCKET=your-project.appspot.com

NOTIFICATION_QUEUE=notifications
NOTIFICATION_RETENTION_DAYS=90
```

### 2. Queue Configuration

Ensure queue worker is running:
```bash
./vendor/bin/sail artisan queue:work --queue=notifications
```

### 3. Scheduler

Add to your cron:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

Or in Docker:
```bash
./vendor/bin/sail artisan schedule:work
```

## 📂 File Structure

```
app/Components/Notification/
├── Application/
│   ├── Query/
│   │   ├── NotificationQueryInterface.php
│   │   └── UserDeviceTokenQueryInterface.php
│   ├── Repository/
│   │   ├── NotificationRepositoryInterface.php
│   │   └── UserDeviceTokenRepositoryInterface.php
│   └── Service/
│       ├── NotificationServiceInterface.php
│       ├── FcmServiceInterface.php
│       └── EmailNotificationServiceInterface.php
├── Data/
│   ├── DTO/
│   │   ├── NotificationDto.php
│   │   └── SendNotificationDto.php
│   ├── Entity/
│   │   ├── NotificationEntity.php
│   │   ├── UserDeviceTokenEntity.php
│   │   └── AdminBroadcastEntity.php
│   └── Enums/
│       ├── NotificationTypeEnum.php
│       ├── DeviceTypeEnum.php
│       └── BroadcastStatusEnum.php
├── Domain/
│   └── Exception/
│       ├── NotificationException.php
│       └── FcmException.php
├── Infrastructure/
│   ├── Console/Commands/
│   │   ├── SendTargetRemindersCommand.php
│   │   ├── ProcessScheduledBroadcastsCommand.php
│   │   └── CleanOldNotificationsCommand.php
│   ├── Events/
│   │   ├── MealPlanCustomizedEvent.php
│   │   ├── TargetReminderEvent.php
│   │   ├── NewRecipeUploadedEvent.php
│   │   └── AdminBroadcastEvent.php
│   ├── Http/
│   │   ├── Handler/
│   │   │   ├── RegisterDeviceTokenHandler.php
│   │   │   ├── GetNotificationsHandler.php
│   │   │   ├── MarkAsReadHandler.php
│   │   │   ├── MarkAllAsReadHandler.php
│   │   │   ├── GetUnreadCountHandler.php
│   │   │   └── DeleteNotificationHandler.php
│   │   └── Request/
│   │       ├── RegisterDeviceTokenRequest.php
│   │       ├── GetNotificationsRequest.php
│   │       └── MarkAsReadRequest.php
│   ├── Jobs/
│   │   ├── SendFcmNotificationJob.php
│   │   ├── SendEmailNotificationJob.php
│   │   ├── ProcessTargetRemindersJob.php
│   │   └── ProcessScheduledBroadcastsJob.php
│   ├── Listeners/
│   │   ├── SendMealPlanCustomizedNotification.php
│   │   ├── SendTargetReminderNotification.php
│   │   ├── SendNewRecipeNotification.php
│   │   └── SendAdminBroadcastNotification.php
│   ├── Mail/
│   │   ├── MealPlanCustomizedMail.php
│   │   ├── TargetReminderMail.php
│   │   ├── NewRecipeMail.php
│   │   └── AdminMessageMail.php
│   ├── Query/
│   │   ├── NotificationQuery.php
│   │   └── UserDeviceTokenQuery.php
│   ├── Repository/
│   │   ├── NotificationRepository.php
│   │   └── UserDeviceTokenRepository.php
│   ├── Service/
│   │   ├── NotificationService.php
│   │   ├── FcmService.php
│   │   └── EmailNotificationService.php
│   └── ServiceProvider/
│       └── NotificationServiceProvider.php
└── Resource/
    └── routes.php
```

## 🚀 Usage Examples

### Send Notification Programmatically

```php
use App\Components\Notification\Application\Service\NotificationServiceInterface;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;

$notificationService = app(NotificationServiceInterface::class);

// Send to single user
$notificationService->sendToUser(
    userUuid: $user->uuid,
    type: NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED,
    title: 'Meal Plan Updated',
    body: 'Your meal plan has been customized!',
    data: ['plan_id' => $plan->uuid]
);

// Send to multiple users
$notificationService->sendToMultipleUsers(
    userUuids: [$user1->uuid, $user2->uuid],
    type: NotificationTypeEnum::NEW_RECIPE,
    title: 'New Recipe Available',
    body: 'Check out our new recipe!',
    data: ['recipe_id' => $recipe->uuid]
);
```

### Using Events (Recommended)

```php
// In your service/controller
use App\Components\Notification\Infrastructure\Events\MealPlanCustomizedEvent;

MealPlanCustomizedEvent::dispatch(
    userUuid: $user->uuid,
    planUuid: $plan->uuid,
    planName: $plan->name,
    customizationDetails: $details
);
```

### Admin Broadcast from Code

```php
use App\Components\Notification\Data\Entity\AdminBroadcastEntity;
use App\Components\Auth\Data\Entity\UserEntity;

$broadcast = AdminBroadcastEntity::create([
    'admin_uuid' => auth()->user()->uuid,
    'title' => 'System Maintenance',
    'body' => 'The system will be down for maintenance.',
    'target_type' => 'all',
    'status' => 'scheduled',
    'scheduled_at' => now()->addHours(2),
]);
```

## 📧 Email Templates

Email templates need to be created in `resources/views/emails/notifications/`:

- `meal-plan-customized.blade.php`
- `target-reminder.blade.php`
- `new-recipe.blade.php`
- `admin-message.blade.php`

**Example template:**
```blade
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>
    <h1>Hello {{ $userName }}!</h1>
    <p>{{ $body }}</p>
    
    @if($data)
        <p>Additional details:</p>
        <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
    @endif
</body>
</html>
```

## 🧪 Testing

### Run Migrations
```bash
./vendor/bin/sail artisan migrate
```

### Test Commands
```bash
# Send target reminders
./vendor/bin/sail artisan notifications:send-target-reminders

# Process scheduled broadcasts
./vendor/bin/sail artisan notifications:process-scheduled-broadcasts

# Clean old notifications
./vendor/bin/sail artisan notifications:clean-old-notifications --days=90
```

### Test API Endpoints
```bash
# Register device token
curl -X POST http://localhost:8085/api/notifications/register-device \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"device_token":"test_token","device_type":"android"}'

# Get notifications
curl -X GET http://localhost:8085/api/notifications \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🔍 Monitoring & Logs

All notification operations are logged. Check logs for:
- FCM send success/failures
- Email send success/failures
- Event processing
- Job execution

```bash
./vendor/bin/sail artisan pail
```

## 📊 Admin Panel Access

Navigate to: `http://localhost:8085/admin`

**Notification Management:**
- View all notifications
- Filter by type, read status
- Delete notifications

**Broadcast Management:**
- Create new broadcasts
- Schedule for later
- Target all users or specific users
- View send statistics

## ⚠️ Important Notes

1. **Firebase Credentials:** Ensure Firebase credentials file is properly configured
2. **Queue Worker:** Must be running for async notifications
3. **Scheduler:** Must be configured for automated tasks
4. **Email Templates:** Create blade templates for each notification type
5. **Device Tokens:** Users must register their device tokens via API

## 🎉 Next Steps

1. ✅ Run migrations
2. ✅ Configure Firebase credentials
3. ✅ Start queue worker
4. ✅ Create email templates
5. ✅ Test API endpoints
6. ✅ Integrate event dispatching in existing code
7. ✅ Configure scheduler cron job

## 📝 Migration Commands

```bash
# Run migrations
./vendor/bin/sail artisan migrate

# Rollback if needed
./vendor/bin/sail artisan migrate:rollback --step=3

# Fresh migration (WARNING: destroys data)
./vendor/bin/sail artisan migrate:fresh
```

---

**Implementation Date:** February 14, 2026
**Laravel Version:** 11.x
**PHP Version:** 8.3
**Status:** ✅ Complete and Ready for Testing
