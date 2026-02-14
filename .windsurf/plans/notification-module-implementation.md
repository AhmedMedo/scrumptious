# Notification Module Implementation Plan

Implement a comprehensive notification system with FCM (Firebase Cloud Messaging) and email support, following Laravel best practices and the existing DDD-inspired architecture pattern.

## Project Analysis Summary

The project uses:
- **Laravel 11** with **PHP 8.2+**
- **Docker** (Laravel Sail) for development
- **DDD-inspired architecture** with Components organized as:
  - `Application/` - Interfaces (Services, Repositories, Queries, Factories, Mappers)
  - `Data/` - Entities and Enums
  - `Domain/` - DTOs and Exceptions
  - `Infrastructure/` - Implementations (Services, Repositories, Queries, HTTP Handlers)
  - `Presentation/` - ViewModels
  - `Resource/` - Routes
- **Filament** for admin panel
- **Laravel Passport** for API authentication
- **Existing notification infrastructure** (basic NotificationEntity exists but incomplete)

## Notification Scenarios to Cover

1. **Customize Meal Plan** - Notify users when their meal plan is customized
2. **Target Reminder** - Remind users about their targets/goals
3. **New Recipe Upload** - Notify users when new recipes are added
4. **Admin Broadcast Messages** - Admin panel section to send custom messages to users

## Implementation Plan

### Phase 1: Database & Entity Layer

**1.1 Create/Update Notifications Migration**
- Create migration for `notifications` table with fields:
  - `uuid` (primary key)
  - `user_uuid` (foreign key to users)
  - `type` (enum: meal_plan_customized, target_reminder, new_recipe, admin_message)
  - `title` (string)
  - `body` (text)
  - `data` (json - additional metadata)
  - `is_read` (boolean, default false)
  - `read_at` (timestamp, nullable)
  - `sent_via` (json - tracks which channels were used: fcm, email, database)
  - `fcm_sent_at` (timestamp, nullable)
  - `email_sent_at` (timestamp, nullable)
  - `created_at`, `updated_at`
  - Indexes on `user_uuid`, `type`, `is_read`, `created_at`

**1.2 Create User Device Tokens Migration**
- Create migration for `user_device_tokens` table:
  - `uuid` (primary key)
  - `user_uuid` (foreign key to users)
  - `device_token` (text - FCM token)
  - `device_type` (enum: ios, android, web)
  - `device_name` (string, nullable)
  - `is_active` (boolean, default true)
  - `last_used_at` (timestamp)
  - `created_at`, `updated_at`
  - Unique index on `device_token`
  - Index on `user_uuid`

**1.3 Update NotificationEntity**
- Add fillable fields and relationships
- Add casts for `data` and `sent_via` as JSON
- Add casts for timestamps
- Add relationship to UserEntity

**1.4 Create UserDeviceTokenEntity**
- Create new entity with proper relationships
- Add scopes for active tokens

**1.5 Create Notification Type Enum**
- Create `NotificationTypeEnum` in `app/Components/Notification/Data/Enums/`
- Define constants: MEAL_PLAN_CUSTOMIZED, TARGET_REMINDER, NEW_RECIPE, ADMIN_MESSAGE

### Phase 2: Core Notification Component Structure

**2.1 Create Notification Component Directory Structure**
```
app/Components/Notification/
├── Application/
│   ├── Service/
│   │   ├── NotificationServiceInterface.php
│   │   └── FcmServiceInterface.php
│   ├── Repository/
│   │   ├── NotificationRepositoryInterface.php
│   │   └── UserDeviceTokenRepositoryInterface.php
│   └── Query/
│       ├── NotificationQueryInterface.php
│       └── UserDeviceTokenQueryInterface.php
├── Data/
│   ├── Entity/
│   │   ├── NotificationEntity.php (move from Auth)
│   │   └── UserDeviceTokenEntity.php
│   ├── Enums/
│   │   ├── NotificationTypeEnum.php
│   │   └── DeviceTypeEnum.php
│   └── DTO/
│       ├── NotificationDto.php
│       └── SendNotificationDto.php
├── Domain/
│   └── Exception/
│       ├── NotificationException.php
│       └── FcmException.php
├── Infrastructure/
│   ├── Service/
│   │   ├── NotificationService.php
│   │   ├── FcmService.php
│   │   └── EmailNotificationService.php
│   ├── Repository/
│   │   ├── NotificationRepository.php
│   │   └── UserDeviceTokenRepository.php
│   ├── Query/
│   │   ├── NotificationQuery.php
│   │   └── UserDeviceTokenQuery.php
│   ├── Http/
│   │   ├── Handler/
│   │   │   ├── RegisterDeviceTokenHandler.php
│   │   │   ├── GetNotificationsHandler.php
│   │   │   ├── MarkAsReadHandler.php
│   │   │   └── MarkAllAsReadHandler.php
│   │   └── Request/
│   │       ├── RegisterDeviceTokenRequest.php
│   │       └── MarkAsReadRequest.php
│   ├── Mail/
│   │   ├── MealPlanCustomizedMail.php
│   │   ├── TargetReminderMail.php
│   │   ├── NewRecipeMail.php
│   │   └── AdminMessageMail.php
│   └── ServiceProvider/
│       └── NotificationServiceProvider.php
└── Resource/
    └── routes.php
```

### Phase 3: Service Layer Implementation

**3.1 Install FCM Package**
- Add `kreait/firebase-php` to composer.json
- Configure Firebase credentials in `.env`

**3.2 Create Configuration File**
- Create `config/firebase.php` for FCM configuration
- Add FCM credentials path and project ID to `.env.example`

**3.3 Implement FcmService**
- Initialize Firebase SDK
- Method: `sendToDevice(string $token, array $notification, array $data)`
- Method: `sendToMultipleDevices(array $tokens, array $notification, array $data)`
- Method: `sendToTopic(string $topic, array $notification, array $data)`
- Handle FCM exceptions and logging

**3.4 Implement EmailNotificationService**
- Create Mailable classes for each notification type
- Use Laravel's Mail facade
- Support both English and Arabic templates
- Include proper styling and branding

**3.5 Implement NotificationService**
- Method: `send(SendNotificationDto $dto)` - orchestrates sending via all channels
- Method: `sendToUser(string $userUuid, string $type, array $data)`
- Method: `sendToMultipleUsers(array $userUuids, string $type, array $data)`
- Method: `markAsRead(string $notificationUuid)`
- Method: `markAllAsRead(string $userUuid)`
- Method: `deleteNotification(string $notificationUuid)`
- Store notification in database
- Queue FCM and email sending
- Handle failures gracefully

**3.6 Implement Repository & Query Classes**
- NotificationRepository: CRUD operations
- UserDeviceTokenRepository: Token management
- NotificationQuery: Fetch notifications with filters, pagination
- UserDeviceTokenQuery: Fetch active tokens for users

### Phase 4: API Endpoints

**4.1 Device Token Management**
- `POST /api/notifications/register-device` - Register FCM token
- `DELETE /api/notifications/unregister-device` - Remove FCM token

**4.2 Notification Endpoints**
- `GET /api/notifications` - List user notifications (paginated, with filters)
- `GET /api/notifications/{uuid}` - Get single notification
- `PATCH /api/notifications/{uuid}/read` - Mark as read
- `PATCH /api/notifications/read-all` - Mark all as read
- `DELETE /api/notifications/{uuid}` - Delete notification

**4.3 Create HTTP Handlers**
- Implement all handlers following existing pattern
- Add OpenAPI documentation attributes
- Validate requests using FormRequest classes

### Phase 5: Event-Driven Notification Triggers

**5.1 Create Events**
- `MealPlanCustomizedEvent` - Triggered when meal plan is customized
- `TargetReminderEvent` - Triggered by scheduler for target reminders
- `NewRecipeUploadedEvent` - Triggered when recipe is created/published
- `AdminBroadcastEvent` - Triggered from admin panel

**5.2 Create Listeners**
- `SendMealPlanCustomizedNotification`
- `SendTargetReminderNotification`
- `SendNewRecipeNotification`
- `SendAdminBroadcastNotification`

**5.3 Register Events & Listeners**
- Update `EventServiceProvider` with event-listener mappings
- Ensure listeners are queued for async processing

**5.4 Integrate Events into Existing Code**
- Add event dispatch in MealPlanner component when plan is customized
- Add event dispatch in Recipe component when recipe is created
- Create scheduler command for target reminders

### Phase 6: Admin Panel (Filament)

**6.1 Create NotificationEntity Filament Resource**
- List all notifications with filters (user, type, read status)
- View notification details
- Bulk actions: mark as read, delete

**6.2 Create Admin Broadcast Feature**
- Create `AdminBroadcastEntity` and migration
- Fields: title, body, target_users (all/specific), scheduled_at
- Filament resource for creating broadcasts
- Support for:
  - Send to all users
  - Send to specific users (multi-select)
  - Schedule for later
  - Preview before sending

**6.3 Create UserDeviceToken Filament Resource**
- View registered devices per user
- Ability to deactivate tokens
- Statistics: total devices, active devices by platform

### Phase 7: Queue & Jobs

**7.1 Create Jobs**
- `SendFcmNotificationJob` - Send FCM notification
- `SendEmailNotificationJob` - Send email notification
- `ProcessTargetRemindersJob` - Check and send target reminders
- `ProcessScheduledBroadcastsJob` - Send scheduled broadcasts

**7.2 Configure Queue**
- Ensure queue worker is running in Docker
- Configure retry logic and failed job handling
- Add queue monitoring in Telescope

### Phase 8: Scheduler Commands

**8.1 Create Console Commands**
- `SendTargetRemindersCommand` - Check targets and send reminders
- `ProcessScheduledBroadcastsCommand` - Process scheduled broadcasts
- `CleanOldNotificationsCommand` - Archive/delete old notifications

**8.2 Register in Kernel**
- Schedule target reminders (daily check)
- Schedule broadcast processing (every minute)
- Schedule cleanup (weekly)

### Phase 9: Testing & Documentation

**9.1 Update .env.example**
- Add FCM configuration variables
- Add notification settings

**9.2 Create Postman/OpenAPI Documentation**
- Document all notification endpoints
- Include example requests/responses

**9.3 Update README**
- Document notification setup
- Firebase configuration steps
- Queue worker setup

### Phase 10: Integration & Cleanup

**10.1 Move Existing Notification Code**
- Move NotificationEntity from Auth to Notification component
- Update imports and namespaces
- Update AuthServiceProvider bindings

**10.2 Register NotificationServiceProvider**
- Add to `bootstrap/providers.php` or `config/app.php`

**10.3 Update Routes**
- Move notification routes from Auth to Notification component
- Maintain backward compatibility if needed

**10.4 Add Middleware**
- Ensure proper authentication on notification endpoints
- Add rate limiting for device registration

## Technical Considerations

### Laravel Best Practices
- ✅ Use Service Container for dependency injection
- ✅ Follow Repository pattern for data access
- ✅ Use Events & Listeners for decoupling
- ✅ Queue long-running tasks (FCM, Email)
- ✅ Use Form Requests for validation
- ✅ Implement proper exception handling
- ✅ Use database transactions where needed
- ✅ Follow PSR-12 coding standards
- ✅ Use Eloquent relationships properly

### Security
- Validate device tokens before storage
- Sanitize notification content
- Implement rate limiting on notification endpoints
- Secure Firebase credentials (use .env, not version control)
- Validate user permissions for admin broadcasts

### Performance
- Index database columns for queries
- Use eager loading to prevent N+1 queries
- Queue FCM and email sending
- Batch FCM sends when possible
- Implement notification pagination
- Cache frequently accessed data

### Scalability
- Use queues for async processing
- Support horizontal scaling with Redis queue
- Implement notification batching for bulk sends
- Consider notification archival strategy

## Dependencies to Add

```json
{
  "kreait/firebase-php": "^7.0",
  "kreait/laravel-firebase": "^5.0"
}
```

## Environment Variables to Add

```env
# Firebase Cloud Messaging
FIREBASE_CREDENTIALS=path/to/firebase-credentials.json
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_DATABASE_URL=https://your-project.firebaseio.com

# Notification Settings
NOTIFICATION_QUEUE=notifications
NOTIFICATION_RETENTION_DAYS=90
```

## Migration Order

1. `create_notifications_table`
2. `create_user_device_tokens_table`
3. `create_admin_broadcasts_table`

## Questions for Clarification

Before implementation, please confirm:

1. **FCM Setup**: Do you already have a Firebase project, or should I include setup instructions?
2. **Email Templates**: Should notifications support both English and Arabic languages?
3. **Target Reminders**: What is the logic for target reminders? (e.g., remind X days before end_date?)
4. **New Recipe Notifications**: Should all users be notified, or only subscribed/interested users?
5. **Admin Broadcasts**: Should there be approval workflow or send immediately?
6. **Notification Retention**: How long should notifications be kept in the database?
7. **Real-time Updates**: Do you need WebSocket/Pusher for real-time notification updates in the app?

## Estimated Timeline

- Phase 1-2: Database & Structure (2-3 hours)
- Phase 3-4: Services & API (3-4 hours)
- Phase 5: Events & Integration (2-3 hours)
- Phase 6: Admin Panel (2-3 hours)
- Phase 7-8: Jobs & Scheduler (2 hours)
- Phase 9-10: Testing & Cleanup (2 hours)

**Total: ~15-20 hours of development**
