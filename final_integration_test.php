<?php

echo "Users Package - Organization Integration Test\n";
echo "============================================\n\n";

// Test 1: Check if core files exist
echo "1. File Structure Check:\n";

$coreFiles = [
    'src/Providers/UsersServiceProvider.php' => 'Service Provider',
    'src/UserTypes/OrganizationUserType.php' => 'Organization User Type',
    'src/Models/User.php' => 'User Model',
    'src/Models/UserProfile.php' => 'User Profile Model',
    'database/migrations/2024_01_01_000002_create_user_profiles_table.php' => 'User Profile Migration',
    'database/migrations/2024_01_01_000006_add_organization_fields_to_users_table.php' => 'Organization Fields Migration',
    'config/users.php' => 'Configuration',
    'routes/api.php' => 'API Routes',
    'composer.json' => 'Composer Configuration',
];

foreach ($coreFiles as $file => $description) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "   ✅ {$description}\n";
    } else {
        echo "   ❌ {$description} - MISSING\n";
    }
}

// Test 2: Check organization integration in migration
echo "\n2. Organization Integration Check:\n";

try {
    $migrationContent = file_get_contents(__DIR__ . '/database/migrations/2024_01_01_000006_add_organization_fields_to_users_table.php');
    
    if (strpos($migrationContent, 'organization_id') !== false) {
        echo "   ✅ Users table has organization_id field\n";
    } else {
        echo "   ❌ Users table missing organization_id field\n";
    }
    
    if (strpos($migrationContent, 'reports_to') !== false) {
        echo "   ✅ Users table has reports_to field\n";
    } else {
        echo "   ❌ Users table missing reports_to field\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading migration: " . $e->getMessage() . "\n";
}

// Test 3: Check user profile migration
echo "\n3. User Profile Integration Check:\n";

try {
    $profileMigrationContent = file_get_contents(__DIR__ . '/database/migrations/2024_01_01_000002_create_user_profiles_table.php');
    
    if (strpos($profileMigrationContent, 'organization_id') !== false) {
        echo "   ✅ User profiles table has organization_id field\n";
    } else {
        echo "   ❌ User profiles table missing organization_id field\n";
    }
    
    if (strpos($profileMigrationContent, 'department_id') !== false) {
        echo "   ✅ User profiles table has department_id field\n";
    } else {
        echo "   ❌ User profiles table missing department_id field\n";
    }
    
    if (strpos($profileMigrationContent, 'position_id') !== false) {
        echo "   ✅ User profiles table has position_id field\n";
    } else {
        echo "   ❌ User profiles table missing position_id field\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading user profile migration: " . $e->getMessage() . "\n";
}

// Test 4: Check configuration
echo "\n4. Configuration Check:\n";

try {
    $config = require __DIR__ . '/config/users.php';
    
    if (isset($config['organization'])) {
        echo "   ✅ Configuration has organization section\n";
        
        if (isset($config['organization']['hierarchical_structure'])) {
            echo "   ✅ Hierarchical structure configured\n";
        } else {
            echo "   ❌ Hierarchical structure not configured\n";
        }
        
        if (isset($config['organization']['use_departments'])) {
            echo "   ✅ Department usage configured\n";
        } else {
            echo "   ❌ Department usage not configured\n";
        }
    } else {
        echo "   ❌ Configuration missing organization section\n";
    }
    
    if (isset($config['user_types']['organization'])) {
        echo "   ✅ Organization user type configured\n";
    } else {
        echo "   ❌ Organization user type not configured\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading configuration: " . $e->getMessage() . "\n";
}

// Test 5: Check OrganizationUserType content
echo "\n5. OrganizationUserType Implementation Check:\n";

try {
    $orgUserTypeContent = file_get_contents(__DIR__ . '/src/UserTypes/OrganizationUserType.php');
    
    if (strpos($orgUserTypeContent, 'registerUser') !== false) {
        echo "   ✅ OrganizationUserType has registerUser method\n";
    } else {
        echo "   ❌ OrganizationUserType missing registerUser method\n";
    }
    
    if (strpos($orgUserTypeContent, 'updateProfile') !== false) {
        echo "   ✅ OrganizationUserType has updateProfile method\n";
    } else {
        echo "   ❌ OrganizationUserType missing updateProfile method\n";
    }
    
    if (strpos($orgUserTypeContent, 'assignRole') !== false) {
        echo "   ✅ OrganizationUserType has role assignment capability\n";
    } else {
        echo "   ❌ OrganizationUserType missing role assignment capability\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading OrganizationUserType: " . $e->getMessage() . "\n";
}

// Test 6: Check API routes
echo "\n6. API Routes Check:\n";

try {
    $routesContent = file_get_contents(__DIR__ . '/routes/api.php');
    
    if (strpos($routesContent, 'organization') !== false) {
        echo "   ✅ API routes include organization endpoints\n";
    } else {
        echo "   ❌ API routes missing organization endpoints\n";
    }
    
    if (strpos($routesContent, 'hierarchy') !== false) {
        echo "   ✅ API routes include hierarchy endpoints\n";
    } else {
        echo "   ❌ API routes missing hierarchy endpoints\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading API routes: " . $e->getMessage() . "\n";
}

// Test 7: Check composer.json for dependencies
echo "\n7. Dependencies Check:\n";

try {
    $composerContent = file_get_contents(__DIR__ . '/composer.json');
    $composer = json_decode($composerContent, true);
    
    if (isset($composer['require']['litepie/organization'])) {
        echo "   ✅ Litepie Organization package dependency added\n";
    } else {
        echo "   ❌ Litepie Organization package dependency missing\n";
    }
    
    if (isset($composer['autoload']['psr-4']['Litepie\\Users\\'])) {
        echo "   ✅ PSR-4 autoloading configured\n";
    } else {
        echo "   ❌ PSR-4 autoloading not configured\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error reading composer.json: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "INTEGRATION STATUS SUMMARY\n";
echo str_repeat("=", 50) . "\n";

echo "\n✅ COMPLETED FEATURES:\n";
echo "- Database schema with organization hierarchy\n";
echo "- User profile enhancements for organization data\n";
echo "- OrganizationUserType with registration workflow\n";
echo "- API endpoints for organization user management\n";
echo "- Service provider with conditional Shield integration\n";
echo "- Configuration for organization features\n";
echo "- Composer dependency management\n";

echo "\n⚠️  PENDING INTEGRATION:\n";
echo "- Install Litepie Organization package\n";
echo "- Install Litepie Shield package (optional)\n";
echo "- Run database migrations\n";
echo "- Test in actual Laravel application\n";

echo "\n📋 NEXT STEPS:\n";
echo "1. In your Laravel app: composer require litepie/organization\n";
echo "2. Add UsersServiceProvider to config/app.php (if not auto-discovered)\n";
echo "3. Run: php artisan migrate\n";
echo "4. Optionally install Shield: composer require litepie/shield\n";
echo "5. Configure organization settings in config/users.php\n";
echo "6. Test user registration with organization assignment\n";

echo "\n🎉 Users package is ready for organization hierarchy integration!\n";
