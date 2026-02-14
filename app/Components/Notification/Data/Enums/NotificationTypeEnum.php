<?php

namespace App\Components\Notification\Data\Enums;

enum NotificationTypeEnum: string
{
    case MEAL_PLAN_CUSTOMIZED = 'meal_plan_customized';
    case TARGET_REMINDER = 'target_reminder';
    case NEW_RECIPE = 'new_recipe';
    case ADMIN_MESSAGE = 'admin_message';

    public function label(): string
    {
        return match($this) {
            self::MEAL_PLAN_CUSTOMIZED => 'Meal Plan Customized',
            self::TARGET_REMINDER => 'Target Reminder',
            self::NEW_RECIPE => 'New Recipe',
            self::ADMIN_MESSAGE => 'Admin Message',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
