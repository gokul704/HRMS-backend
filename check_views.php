<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\View;
use App\Helpers\ViewHelper;

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Checking All Views\n";
echo "===================\n\n";

$viewNames = [
    // Payrolls
    'payrolls.index', 'payrolls.create', 'payrolls.show', 'payrolls.edit',
    'payrolls.statistics', 'payrolls.by-employee', 'payrolls.employee-payrolls',

    // Offer Letters
    'offer-letters.index', 'offer-letters.create', 'offer-letters.show',
    'offer-letters.edit', 'offer-letters.statistics', 'offer-letters.by-department',

    // Employees
    'employees.index', 'employees.create', 'employees.show', 'employees.edit',
    'employees.statistics', 'employees.by-department', 'employees.profile',

    // Departments
    'departments.index', 'departments.create', 'departments.show', 'departments.edit',
    'departments.statistics',

    // Dashboard
    'dashboard.index', 'dashboard.statistics', 'dashboard.approvals',

    // Auth
    'auth.login',

    // Profile
    'profile.show',

    // Errors
    'errors.404', 'errors.500', 'errors.database', 'errors.view-not-found'
];

$missing = [];
$existing = [];

foreach ($viewNames as $view) {
    if (View::exists($view)) {
        $existing[] = $view;
        echo "✅ {$view}\n";
    } else {
        $missing[] = $view;
        echo "❌ {$view} - MISSING\n";

        // Auto-create the view
        try {
            ViewHelper::createMissingView($view);
            echo "   📝 Created {$view}\n";
        } catch (Exception $e) {
            echo "   ⚠️ Failed to create {$view}: " . $e->getMessage() . "\n";
        }
    }
}

echo "\n📊 Summary:\n";
echo "===========\n";
echo "✅ Existing views: " . count($existing) . "\n";
echo "❌ Missing views: " . count($missing) . "\n";
echo "📝 Auto-created: " . count(array_filter($missing, function($view) use ($viewNames) {
    return View::exists($view);
})) . "\n";

if (empty($missing)) {
    echo "\n🎉 All views are present!\n";
} else {
    echo "\n⚠️ Some views are still missing. Check the logs above.\n";
}
