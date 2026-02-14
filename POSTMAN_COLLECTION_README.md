# 📮 Scrumptious API Postman Collection

## 📋 Overview

Complete Postman collection for testing all Scrumptious application APIs including Authentication, Recipes, Meal Planner, Notifications, Content, and Subscription endpoints.

## 🚀 Quick Start

### 1. Import Collection
1. Open Postman
2. Click **Import** → **Select File**
3. Choose `Scrumptious_API_Postman_Collection.json`
4. Click **Import**

### 2. Set Environment Variables
The collection uses these variables:
- `base_url`: API base URL (default: `http://localhost:8085/api`)
- `auth_token`: JWT token (auto-set after login)
- `user_uuid`: User UUID (auto-set after login)
- `notification_uuid`: Notification UUID (set from response)
- `recipe_uuid`: Recipe UUID (set from response)
- `target_uuid`: Target UUID (set from response)
- `plan_uuid`: Plan UUID (set from response)
- `breakdown_uuid`: Breakdown UUID (set from response)

### 3. Authentication Flow
1. **Register** → Create new account
2. **Login** → Get JWT token (auto-saved)
3. All subsequent requests will use the token automatically

## 📚 API Endpoints

### 🔐 Authentication (16 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| POST | `/auth/register` | Register new user | ❌ |
| POST | `/auth/login` | User login | ❌ |
| POST | `/auth/login-as-guest` | Guest login | ❌ |
| POST | `/auth/verification` | Verify user | ❌ |
| POST | `/auth/forget-password` | Request password reset | ❌ |
| POST | `/auth/forget-password-verification` | Verify password reset | ❌ |
| POST | `/auth/reset-password` | Reset password | ❌ |
| POST | `/auth/resend-otp` | Resend OTP | ❌ |
| POST | `/auth/verify-email` | Verify email | ❌ |
| POST | `/auth/resend-email-verification` | Resend email verification | ❌ |
| POST | `/auth/change-mobile-by-email` | Change mobile via email | ❌ |
| GET | `/auth/logout` | User logout | ✅ |
| GET | `/auth/profile` | Get user profile | ✅ |
| PATCH | `/auth/update-profile` | Update profile | ✅ |
| PATCH | `/auth/change-password` | Change password | ✅ |
| DELETE | `/auth/delete-account` | Delete account | ✅ |
| PATCH | `/auth/change-email` | Change email | ✅ |
| PATCH | `/auth/change-phone` | Change phone | ✅ |
| GET | `/auth/notifications` | Get notifications (old) | ✅ |

### 🔔 Notifications (6 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| POST | `/notifications/register-device` | Register FCM device token | ✅ |
| GET | `/notifications` | Get user notifications | ✅ |
| GET | `/notifications/unread-count` | Get unread count | ✅ |
| PATCH | `/notifications/{uuid}/read` | Mark as read | ✅ |
| PATCH | `/notifications/read-all` | Mark all as read | ✅ |
| DELETE | `/notifications/{uuid}` | Delete notification | ✅ |

### 🍳 Recipes (8 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| GET | `/recipe/list` | List recipes | ❌ |
| GET | `/recipe/{uuid}/show` | Show recipe details | ❌ |
| POST | `/recipe` | Create recipe | ✅ |
| PATCH | `/recipe/{uuid}/update` | Update recipe | ❌ |
| DELETE | `/recipe/{uuid}/delete` | Delete recipe | ❌ |
| POST | `/recipe/{uuid}/toggle-favourite` | Toggle favorite | ✅ |
| GET | `/ingredients` | List ingredients | ❌ |
| GET | `/groceries` | List groceries | ❌ |

### 🥗 Meal Planner (15 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| POST | `/target` | Create target | ✅ |
| GET | `/target/list` | List targets | ✅ |
| GET | `/target/{uuid}/show` | Show target | ✅ |
| PATCH | `/target/{uuid}/update` | Update target | ✅ |
| DELETE | `/target/{uuid}/delete` | Delete target | ✅ |
| POST | `/plans` | Create meal plan | ✅ |
| GET | `/plans/list` | List plans | ✅ |
| GET | `/plans/{uuid}/show` | Show plan | ✅ |
| PATCH | `/plans/{uuid}/update` | Update plan | ✅ |
| DELETE | `/plans/{uuid}/delete` | Delete plan | ✅ |
| POST | `/breakdowns` | Create breakdown | ✅ |
| GET | `/breakdowns/list` | List breakdowns | ✅ |
| GET | `/breakdowns/{uuid}/show` | Show breakdown | ✅ |
| PATCH | `/breakdowns/{uuid}/update` | Update breakdown | ✅ |
| DELETE | `/breakdowns/{uuid}/delete` | Delete breakdown | ✅ |

### 📄 Content (9 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| GET | `/content/countries` | Get countries | ❌ |
| POST | `/content/upload-media` | Upload media file | ❌ |
| GET | `/content/config` | Get app config | ❌ |
| GET | `/content/faq` | Get FAQ | ❌ |
| GET | `/content/categories` | Get categories | ❌ |
| GET | `/content/grocery-categories` | Get grocery categories | ❌ |
| GET | `/content/policies` | Get policies | ❌ |
| POST | `/customer-support` | Contact support | ❌ |
| POST | `/newsletter` | Subscribe newsletter | ❌ |

### 💳 Subscription (2 endpoints)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|--------------|
| GET | `/subscription/plans` | Get subscription plans | ❌ |
| POST | `/subscription/payment` | Create payment | ❌/✅ |

## 🧪 Testing Workflows

### **Workflow 1: Complete User Journey**
1. **Register** → Create account
2. **Login** → Get auth token
3. **Create Target** → Set fitness goal
4. **Create Recipe** → Add recipe
5. **Create Meal Plan** → Plan meals
6. **Register Device** → Enable notifications
7. **Get Notifications** → Check notifications

### **Workflow 2: Meal Planning**
1. **Login** → Authenticate
2. **List Recipes** → Browse recipes
3. **Create Target** → Set goal
4. **Create Plan** → Make meal plan
5. **Create Breakdowns** → Add meal details
6. **Update Plan** → Modify plan

### **Workflow 3: Notification Testing**
1. **Login** → Authenticate
2. **Register Device** → Add FCM token
3. **Get Notifications** → Check list
4. **Mark as Read** → Update status
5. **Get Unread Count** → Verify count

## 📝 Sample Request Bodies

### **Register User**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "country_id": "1"
}
```

### **Create Target**
```json
{
    "title": "Lose Weight",
    "description": "Lose 5kg in 2 months",
    "target_type": "weight_loss",
    "start_date": "2024-01-01",
    "end_date": "2024-03-01",
    "target_value": "5",
    "current_value": "0",
    "unit": "kg"
}
```

### **Create Recipe**
```json
{
    "name": "Delicious Pasta",
    "description": "A tasty pasta dish with fresh ingredients",
    "instructions": "1. Boil pasta\n2. Add sauce\n3. Serve hot",
    "prep_time": 30,
    "cook_time": 20,
    "servings": 4,
    "difficulty": "medium",
    "category_id": "1",
    "ingredients": [
        {
            "name": "Pasta",
            "amount": "200g",
            "unit": "grams"
        },
        {
            "name": "Tomato Sauce",
            "amount": "100ml",
            "unit": "milliliters"
        }
    ]
}
```

### **Register Device Token**
```json
{
    "device_token": "fcm_token_here_12345",
    "device_type": "android",
    "device_name": "Samsung Galaxy S21"
}
```

## 🔧 Configuration

### **Base URL**
Default: `http://localhost:8085/api`

Change it in collection variables if your app runs on different port/host.

### **Authentication**
- Login request automatically saves `auth_token` and `user_uuid`
- All protected endpoints use Bearer token authentication
- Token is automatically added to request headers

### **UUID Variables**
After creating resources, copy UUIDs from responses and set them in collection variables:
- `notification_uuid` (from notification response)
- `recipe_uuid` (from recipe response)
- `target_uuid` (from target response)
- `plan_uuid` (from plan response)
- `breakdown_uuid` (from breakdown response)

## 🚨 Error Handling

Common HTTP status codes:
- `200` - Success
- `201` - Created
- `400` - Bad Request (validation errors)
- `401` - Unauthorized (invalid/missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## 📊 Response Examples

### **Login Success**
```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "uuid": "123e4567-e89b-12d3-a456-426614174000",
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

### **Notification List**
```json
{
    "data": [
        {
            "uuid": "456e7890-e89b-12d3-a456-426614174001",
            "type": "meal_plan_customized",
            "title": "Meal Plan Updated",
            "body": "Your meal plan has been customized!",
            "is_read": false,
            "created_at": "2024-01-15T10:30:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 20,
        "total": 1
    }
}
```

## 🎯 Tips for Testing

1. **Start with Login** - Most endpoints require authentication
2. **Copy UUIDs** - After creating resources, copy UUIDs for update/delete operations
3. **Check Responses** - Look for UUIDs in response data to set variables
4. **Test Validation** - Try invalid data to see error responses
5. **Use Variables** - Leverage collection variables for dynamic data
6. **Test Workflows** - Follow the suggested workflows for comprehensive testing

## 📱 Mobile App Testing

For mobile app testing:
1. Use the same collection but change `base_url` to your production/staging URL
2. Test device token registration with real FCM tokens
3. Verify notification flows end-to-end

## 🐛 Troubleshooting

### **Common Issues**
1. **401 Unauthorized** → Check if `auth_token` is set and valid
2. **404 Not Found** → Verify UUID variables are set correctly
3. **422 Validation Error** → Check request body format and required fields
4. **Connection Refused** → Ensure Laravel app is running on correct port

### **Debug Steps**
1. Check console logs in Postman
2. Verify environment variables
3. Test with curl command for comparison
4. Check Laravel logs: `./vendor/bin/sail artisan pail`

---

**Total Endpoints**: 56 APIs across 6 modules  
**Collection Size**: Complete with all request bodies, headers, and variables  
**Ready for**: Development, testing, and API documentation

Happy testing! 🚀
