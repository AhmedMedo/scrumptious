# Notification Channels Configuration Guide

## 📡 Channel Behavior

### **Default Behavior**
By default, notifications are **ONLY saved to the database** and returned via API endpoints.

- ✅ **Database**: Always enabled (default)
- ❌ **FCM (Push)**: Disabled by default
- ❌ **Email**: Disabled by default

### **Why This Design?**
1. **API-First**: All notifications are accessible via API for mobile/web apps
2. **Opt-In Channels**: FCM and Email are opt-in to avoid unwanted notifications
3. **Flexibility**: You control which channels to use per notification type

---

## 🎯 How to Enable Channels

### **Option 1: Using SendNotificationDto (Programmatic)**

```php
use App\Components\Notification\Data\DTO\SendNotificationDto;
use App\Components\Notification\Data\Enums\NotificationTypeEnum;

$dto = new SendNotificationDto(
    userUuids: $user->uuid,
    type: NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED,
    title: 'Meal Plan Updated',
    body: 'Your meal plan has been customized!',
    data: ['plan_id' => $plan->uuid],
    sendFcm: true,        // Enable FCM push notification
    sendEmail: true,      // Enable email notification
    saveToDatabase: true  // Save to database (default: true)
);

$notificationService->send($dto);
```

### **Option 2: Modify Event Listeners**

Edit the listener to enable specific channels:

```php
// File: SendMealPlanCustomizedNotification.php

public function handle(MealPlanCustomizedEvent $event): void
{
    $dto = new SendNotificationDto(
        userUuids: $event->userUuid,
        type: NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED,
        title: 'Meal Plan Customized',
        body: "Your meal plan '{$event->planName}' has been customized!",
        data: [...],
        sendFcm: true,   // ← Enable FCM
        sendEmail: false, // ← Keep email disabled
    );
    
    $this->notificationService->send($dto);
}
```

---

## 📋 Current Integration Points

### **1. Meal Plan Customization** ✅
**Location**: `app/Components/MealPlanner/Infrastructure/Service/Plan/PlanService.php`

**Trigger**: When `update()` method is called

**Current Channels**: Database only

**To Enable FCM/Email**: Modify `SendMealPlanCustomizedNotification` listener

---

### **2. New Recipe Upload** ✅
**Location**: `app/Components/Recipe/Infrastructure/Service/RecipeService.php`

**Trigger**: When `store()` method is called (new recipe created)

**Current Channels**: Database only

**To Enable FCM/Email**: Modify `SendNewRecipeNotification` listener

---

### **3. Target Reminders** ✅
**Location**: Automated via scheduler

**Trigger**: Daily at midnight via `notifications:send-target-reminders` command

**Current Channels**: Database only

**To Enable FCM/Email**: Modify `SendTargetReminderNotification` listener

---

### **4. Admin Broadcasts** ✅
**Location**: Filament admin panel or programmatic

**Trigger**: When broadcast is scheduled/sent

**Current Channels**: Database only

**To Enable FCM/Email**: Modify `SendAdminBroadcastNotification` listener

---

## 🔧 Recommended Channel Configuration

### **Meal Plan Customization**
```php
sendFcm: true,   // ✅ User expects immediate feedback
sendEmail: false, // ❌ Not urgent enough for email
```

### **Target Reminders**
```php
sendFcm: true,   // ✅ Important reminder
sendEmail: true,  // ✅ Backup channel for engagement
```

### **New Recipe**
```php
sendFcm: false,  // ❌ Not urgent
sendEmail: true,  // ✅ Weekly digest style
```

### **Admin Broadcasts**
```php
sendFcm: true,   // ✅ Important announcements
sendEmail: true,  // ✅ Ensure delivery
```

---

## 📱 API Endpoints (Always Available)

All notifications are accessible via API regardless of channel settings:

```http
GET /api/notifications
GET /api/notifications/unread-count
PATCH /api/notifications/{uuid}/read
PATCH /api/notifications/read-all
DELETE /api/notifications/{uuid}
```

---

## 🎨 Email Templates

Email templates are located in `resources/views/emails/notifications/`:

- ✅ `meal-plan-customized.blade.php`
- ✅ `target-reminder.blade.php`
- ✅ `new-recipe.blade.php`
- ✅ `admin-message.blade.php`

**Note**: Templates are placeholders. Update content as needed.

---

## 🔔 FCM Requirements

For FCM to work:

1. **Firebase Configuration**: Set up in `.env`
   ```env
   FIREBASE_CREDENTIALS=/path/to/credentials.json
   FIREBASE_PROJECT_ID=your-project-id
   ```

2. **Device Token Registration**: Users must register via API
   ```http
   POST /api/notifications/register-device
   {
     "device_token": "fcm_token_here",
     "device_type": "ios|android|web"
   }
   ```

3. **Queue Worker Running**:
   ```bash
   ./vendor/bin/sail artisan queue:work --queue=notifications
   ```

---

## 📊 Example: Enable All Channels for Admin Broadcasts

```php
// File: SendAdminBroadcastNotification.php

public function handle(AdminBroadcastEvent $event): void
{
    $dto = new SendNotificationDto(
        userUuids: $event->userUuids,
        type: NotificationTypeEnum::ADMIN_MESSAGE,
        title: $event->title,
        body: $event->body,
        data: $event->data,
        sendFcm: true,        // ✅ Push notification
        sendEmail: true,      // ✅ Email notification
        saveToDatabase: true  // ✅ Database (always)
    );
    
    $this->notificationService->send($dto);
}
```

---

## 🧪 Testing Channels

### Test Database Only (Default)
```php
$notificationService->sendToUser(
    $user->uuid,
    NotificationTypeEnum::MEAL_PLAN_CUSTOMIZED,
    'Test Title',
    'Test Body'
);

// Check via API
GET /api/notifications
```

### Test All Channels
```php
$dto = new SendNotificationDto(
    userUuids: $user->uuid,
    type: NotificationTypeEnum::ADMIN_MESSAGE,
    title: 'Test All Channels',
    body: 'Testing FCM + Email + Database',
    sendFcm: true,
    sendEmail: true,
    saveToDatabase: true
);

$notificationService->send($dto);
```

---

## ⚙️ Configuration Summary

| Notification Type | Database | FCM | Email | Notes |
|------------------|----------|-----|-------|-------|
| Meal Plan Customized | ✅ Always | ❌ Default | ❌ Default | Modify listener to enable |
| Target Reminder | ✅ Always | ❌ Default | ❌ Default | Modify listener to enable |
| New Recipe | ✅ Always | ❌ Default | ❌ Default | Modify listener to enable |
| Admin Broadcast | ✅ Always | ❌ Default | ❌ Default | Modify listener to enable |

**To change defaults**: Edit the respective listener files in:
`app/Components/Notification/Infrastructure/Listeners/`

---

## 🎉 Summary

✅ **Database notifications**: Always work, accessible via API
✅ **FCM & Email**: Opt-in per notification via `SendNotificationDto`
✅ **Business logic integrated**: Events fire automatically
✅ **Email templates created**: Ready for customization
✅ **Flexible configuration**: Control channels per notification type

**Default behavior ensures no spam** - you explicitly enable FCM/Email when needed!
