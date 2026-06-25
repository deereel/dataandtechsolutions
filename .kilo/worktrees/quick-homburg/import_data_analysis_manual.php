<?php
/**
 * Manual Import for Data Analysis Course
 * 
 * This script imports the curriculum step-by-step to avoid variable issues
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Manual Import: Data Analysis Course Curriculum ===" . PHP_EOL;

try {
    $pdo = DatabaseConnection::getInstance();
    
    // ==================== Module 1: Introduction to Data Analysis ====================
    echo "1. Creating Module 1: Introduction to Data Analysis..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Introduction to Data Analysis', 'Learn what data analysis is and the tools of the trade', 1, 1)");
    $module1_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module1_id . PHP_EOL;
    
    // ==================== Module 2: Excel for Data Analysis ====================
    echo "2. Creating Module 2: Excel for Data Analysis..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Excel for Data Analysis', 'Advanced Excel for analysts', 2, 1)");
    $module2_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module2_id . PHP_EOL;
    
    // ==================== Module 3: SQL Fundamentals ====================
    echo "3. Creating Module 3: SQL Fundamentals..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'SQL Fundamentals', 'Master database querying', 3, 1)");
    $module3_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module3_id . PHP_EOL;
    
    // ==================== Module 4: Intermediate SQL ====================
    echo "4. Creating Module 4: Intermediate SQL..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Intermediate SQL', 'Advanced querying techniques', 4, 1)");
    $module4_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module4_id . PHP_EOL;
    
    // ==================== Module 5: Python Basics for Analysts ====================
    echo "5. Creating Module 5: Python Basics for Analysts..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Python Basics for Analysts', 'Introduction to Python programming', 5, 1)");
    $module5_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module5_id . PHP_EOL;
    
    // ==================== Module 6: Data Analysis with Pandas ====================
    echo "6. Creating Module 6: Data Analysis with Pandas..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Data Analysis with Pandas', 'Master the Pandas library', 6, 1)");
    $module6_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module6_id . PHP_EOL;
    
    // ==================== Module 7: Power BI Basics ====================
    echo "7. Creating Module 7: Power BI Basics..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Power BI Basics', 'Introduction to Power BI', 7, 1)");
    $module7_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module7_id . PHP_EOL;
    
    // ==================== Module 8: Advanced Power BI ====================
    echo "8. Creating Module 8: Advanced Power BI..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Advanced Power BI', 'Advanced visualization and modeling', 8, 1)");
    $module8_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module8_id . PHP_EOL;
    
    // ==================== Module 9: Data Cleaning Masterclass ====================
    echo "9. Creating Module 9: Data Cleaning Masterclass..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Data Cleaning Masterclass', 'Advanced data preparation', 9, 1)");
    $module9_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module9_id . PHP_EOL;
    
    // ==================== Module 10: Storytelling with Data ====================
    echo "10. Creating Module 10: Storytelling with Data..." . PHP_EOL;
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Storytelling with Data', 'Presenting insights effectively', 10, 1)");
    $module10_id = $pdo->lastInsertId();
    echo "   ✅ Module ID: " . $module10_id . PHP_EOL;
    
    // ==================== Module 11-17: Mini Projects & Capstone ====================
    echo "11. Creating remaining modules..." . PHP_EOL;
    
    // Mini Project 1: Manual Data Analysis
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Mini Project: Manual Data Analysis', 'Analyze a survey dataset manually', 11, 1)");
    $project1_id = $pdo->lastInsertId();
    
    // Mini Project 2: Sales Performance Analysis
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Mini Project: Sales Performance Analysis', 'Analyze sales data in Excel', 12, 1)");
    $project2_id = $pdo->lastInsertId();
    
    // Mini Project 3: Customer Transaction Analysis
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Mini Project: Customer Transaction Analysis', 'SQL database analysis', 13, 1)");
    $project3_id = $pdo->lastInsertId();
    
    // Mini Project 4: Real Dataset Analysis
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Mini Project: Real Dataset Analysis', 'Analyze with Pandas', 14, 1)");
    $project4_id = $pdo->lastInsertId();
    
    // Mini Project 5: Business Performance Dashboard
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Mini Project: Business Performance Dashboard', 'Build an interactive dashboard', 15, 1)");
    $project5_id = $pdo->lastInsertId();
    
    // Capstone Project: Complete Data Analysis
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Capstone Project: Complete Data Analysis', 'End-to-end data analysis', 16, 1)");
    $capstone_id = $pdo->lastInsertId();
    
    // Final Assessment
    $pdo->exec("INSERT INTO modules (course_id, title, description, order_index, is_published) 
                VALUES (9, 'Final Assessment', 'Comprehensive test', 17, 1)");
    $final_module_id = $pdo->lastInsertId();
    
    echo "   ✅ All modules created" . PHP_EOL;
    
    // ==================== Verify Import ====================
    $moduleCount = $pdo->query('SELECT COUNT(*) FROM modules WHERE course_id = 9')->fetchColumn();
    
    echo PHP_EOL;
    echo "=== Import Verification ===" . PHP_EOL;
    echo "📚 Modules: " . $moduleCount . PHP_EOL;
    
    if ($moduleCount === 17) {
        echo PHP_EOL;
        echo "✅ MODULES SUCCESSFULLY IMPORTED!" . PHP_EOL;
        echo "============================================" . PHP_EOL;
        echo "Now you should create lessons and quizzes for each module" . PHP_EOL;
        echo "============================================" . PHP_EOL;
    } else {
        echo PHP_EOL;
        echo "⚠️  Modules created: " . $moduleCount . " (expected 17)" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo PHP_EOL;
    echo "❌ Error:" . PHP_EOL;
    echo "   " . $e->getMessage() . PHP_EOL;
    if (isset($e->errorInfo)) {
        echo "   SQL Error: " . print_r($e->errorInfo, true) . PHP_EOL;
    }
}
?>
