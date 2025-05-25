<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Components\Recipe\Data\Entity\IngredientEntity;
use App\Components\Recipe\Data\Entity\GroceryEntity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CopyIngredientsToGroceries extends Command
{
    protected $signature = 'copy:groceries-from-list';
    protected $description = 'Copy groceries from predefined list: move from IngredientEntity to GroceryEntity and keep image';

    protected array $groceries = [
        // Dairy products
        "Cream cheese", "Butter", "Ghee", "Whey protein powder", "White eggs", "Brown eggs",
        "Cheddar cheese", "Goat cheese", "American cheese", "Goat Cheese", "Mozzarella Cheese",
        "Cottage cheese", "Blue cheese", "Halloumi cheese", "Feta cheese", "Labneh", "Sour cream",
        "Plain yogurt", "Greek yogurt", "Flavored Greek yogurt", "Cooking Cream", "Whipping Cream",
        "Fresh cream", "Milk", "Skimmed milk", "Rayeb milk", "Lactose free milk", "Condensed milk",

        // Spices and herbs
        "Cayenne pepper", "Cardamom", "Garam masala", "Cumin", "Dukkah", "Ginger powder",
        "Red chili flakes", "Paprika", "Sumac", "Black pepper", "Salt", "Sea salt", "Chili powder",
        "Turmeric", "Garlic powder", "Onion powder", "Cinnamon", "Balsamic Vinegar", "Vinegar",

        // Fats and oils
        "Sunflower oil", "Vegetable oil", "Extra virgin olive oil", "Olive oil", "Sesame oil",

        // Meat, Poultry and Seafood products
        "Ribeye steak", "Sirloin steak", "Beef escalope", "Beef piccata", "Beef cubes",
        "Beef stroganoff", "Beef burger", "Basterma", "Salami", "Roast beef", "Lamb chops",
        "Chicken breast", "Chicken escalope", "Chicken drumstickS", "Chicken wings",
        "Chicken legs", "Chicken thighs", "Roast turkey", "Smoked salmon", "Salmon steaks",
        "Shrimps", "Mussels",

        // Fruits
        "Strawberries", "Blueberries", "Blackberries", "Raspberries", "Avocado", "Mango",
        "Orange", "Pomegranate", "Grapes", "Banana", "Lemon", "Limes", "Red apples",
        "Yellow apples", "Green apples", "Pineapple", "Watermelon", "Cantaloupe", "Grapefruit",
        "Pear", "Peach", "Kiwi", "Plum",

        // Food cupboard
        "Tomato paste", "Noodles", "Spaghetti pasta", "Penne pasta", "Basmati rice",
        "Egyptian rice", "American rice", "Brown rice", "Quinoa", "Chia seeds", "Popcorn",
        "White beans", "Chickpeas", "Fava beans", "Bulgur", "Lentils", "Cereal", "Jam", "Honey",
        "Hazelnut spread", "Maple syrup", "Tuna", "Olives", "Relish", "Chicken stock cubes",
        "Beef stock cubes", "Vegetable stock cubes", "White sugar", "Brown sugar", "Powdered sugar",
        "Flour", "Baking powder", "Baking soda", "Vanilla extract",

        // Vegetables
        "Red Onion", "White Onion", "Green onion", "Cherry tomatoes", "Tomatoes", "Cabbage",
        "Mushrooms", "Celery", "Garlic", "Radish", "Peas", "Spinach", "Lettuce", "Cucumber",
        "Arugula", "Zucchini", "Red bell pepper", "Green bell pepper", "Yellow bell pepper",
        "Lettuce Mix", "Cauliflower", "Broccoli", "Beets", "Carrots", "Corn", "Eggplant",
        "Sweet potatoes", "Potatoes", "Baby potatoes", "Asparagus",

        // Herbs
        "Mint", "Dill", "Parsley", "Thyme", "Basil", "Coriander", "Chives", "Lemongrass",
        "Rosemary",

        // Nut and seed products
        "Almonds", "Almond butter", "Peanuts", "Peanut butter", "Pecans", "Pistachios", "Walnuts",

        // Snacks
        "Chips", "Protein puffs", "Tortilla chips", "Rice cakes", "Protein bar", "Energy balls",
        "Dark chocolate", "Milk Chocolate", "White chocolate", "Mint", "Lollipops", "Candy",
        "Gum", "Wafers", "Biscuits", "Cookies", "Ice cream", "Dates",

        // Beverages
        "Water", "Sparkling Water", "Ice cubes", "Soft drinks", "Juice", "Energy drinks",
        "Arabic coffee", "Turkish coffee", "Instant coffee", "Iced coffee", "Creamer",
        "English breakfast", "Green tea", "Earl grey", "Hot chocolate powder",

        // Baked products
        "Sourdough bread", "White toast", "Brown toast", "Tortilla", "Brioche bread", "Simit",
        "Balady bread", "Lebanese bread", "Bagels", "Burger buns", "Muffins", "Baguette",
        "Petit pan", "Hot dog rolls", "Croissants", "Crackers", "Grissini",

        // Household products
        "Liquid detergent (colored)", "Liquid detergent (white)", "Fabric softener",
        "Stain remover", "Dishwasher pods", "Dishwasher liquid", "Dishwasher cleaner",
        "Bathroom cleaner", "Kitchen cleaner", "Toilet cleaner", "Glass leaner", "Cream cleaner",
        "Antibacterial liquid cleaner", "Foam sponges", "Towels", "Wood furniture polish",
        "Kitchen towels", "Facial tissues", "Toilet paper", "Bin bags", "Insect killer"
    ];


    public function handle()
    {
        DB::beginTransaction();

        try {
            $this->info("Starting grocery migration...");

            $migrated = 0;

            foreach ($this->groceries as $itemName) {
                // Check in IngredientEntity
                $ingredient = IngredientEntity::where('content', $itemName)->first();

                if ($ingredient) {
                    // Create Grocery
                    $grocery = GroceryEntity::create([
                        'content' => $ingredient->content,
                        'is_active' => $ingredient->is_active,
                    ]);

                    // Copy image if exists
                    if ($ingredient->hasMedia('image')) {
                        foreach ($ingredient->getMedia('image') as $media) {
                            $grocery->addMedia($media->getPath())
                                ->usingFileName($media->file_name)
                                ->preservingOriginal()
                                ->toMediaCollection('image');
                        }
                    }

                    // Delete the IngredientEntity after copying
                    $ingredient->recipes()->detach();
                    $ingredient->delete();

                    $this->info("Moved: {$itemName}");
                } else {
                    // Create new grocery (wasn't in ingredients)
                    GroceryEntity::firstOrCreate([
                        'content' => $itemName,
                    ], [
                        'is_active' => true,
                    ]);

                    $this->info("Created new grocery: {$itemName}");
                }

                $migrated++;
            }

            DB::commit();
            $this->info("✅ Migration complete. Total processed: {$migrated}");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Failed: " . $e->getMessage());
        }

        return 0;
    }
}
