<?php
/**
 * Generate quiz questions for Data Automation course
 */

require 'config/config.php';
require 'config/database.php';

echo "=== Generating Quiz Questions for Data Automation Course ===\n";

try {
    // Get all quizzes for Data Automation course
    $quizzes = DatabaseConnection::fetchAll('SELECT * FROM quizzes WHERE course_id = 10');
    
    echo "Found " . count($quizzes) . " quizzes\n\n";
    
    // Quiz questions data (10 questions per quiz)
    $quizQuestions = [
        'Automation Fundamentals Quiz' => [
            [
                'question_text' => 'What is workflow automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using robots to do physical work',
                    'Using technology to automate repetitive business processes',
                    'Writing complex code for every task',
                    'Hiring more people to do manual work'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Workflow automation is the use of technology to automate repetitive, manual business processes to save time and reduce errors.'
            ],
            [
                'question_text' => 'What does API stand for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Automated Process Interface',
                    'Application Programming Interface',
                    'Advanced Process Integration',
                    'Automated Programming Interface'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'API stands for Application Programming Interface - a set of rules that allow different software systems to communicate with each other.'
            ],
            [
                'question_text' => 'What is the primary purpose of webhooks?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To send emails automatically',
                    'To provide real-time communication between applications',
                    'To store data in databases',
                    'To create user interfaces'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Webhooks allow for real-time communication between applications by sending data immediately when an event occurs.'
            ],
            [
                'question_text' => 'In automation terms, what is a "trigger"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'What the automation does',
                    'What starts the automation',
                    'What stops the automation',
                    'What schedules the automation'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'A trigger is the event that starts an automation workflow.'
            ],
            [
                'question_text' => 'What is an "action" in automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'What starts the automation',
                    'What the automation does',
                    'What schedules the automation',
                    'What stops the automation'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'An action is what the automation does after it has been triggered.'
            ],
            [
                'question_text' => 'Which of these is NOT an automation tool?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Zapier',
                    'Make.com',
                    'Airtable',
                    'Photoshop'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'Photoshop is a photo editing tool, not an automation tool. The others are all popular automation platforms.'
            ],
            [
                'question_text' => 'What is the benefit of automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Increased errors',
                    'Reduced efficiency',
                    'Time savings',
                    'More manual work'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'Automation reduces manual work, saves time, and reduces errors.'
            ],
            [
                'question_text' => 'What is a multi-step automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'An automation with only one action',
                    'An automation with multiple actions',
                    'An automation that runs every hour',
                    'An automation that sends emails'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'A multi-step automation includes multiple actions to handle more complex workflows.'
            ],
            [
                'question_text' => 'What is conditional logic in automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Making the automation run faster',
                    'Adding if-then-else decisions',
                    'Scheduling automation',
                    'Creating user interfaces'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Conditional logic adds if-then-else decisions to your automation, allowing it to branch based on conditions.'
            ],
            [
                'question_text' => 'What is an integration?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Connecting two or more applications',
                    'Writing code from scratch',
                    'Creating databases',
                    'Designing websites'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'An integration connects two or more applications so they can share data and work together.'
            ]
        ],
        'Zapier Basics Quiz' => [
            [
                'question_text' => 'What is a "Zap" in Zapier?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A type of email',
                    'An automation workflow',
                    'A programming language',
                    'A database'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'A Zap is an automation workflow in Zapier that connects two or more apps.'
            ],
            [
                'question_text' => 'What do you need to create a Zap?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Only a trigger',
                    'Only an action',
                    'Both a trigger and an action',
                    'Neither a trigger nor an action'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'Every Zap needs at least one trigger and one action.'
            ],
            [
                'question_text' => 'How many apps can you connect in a single Zap?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Only 2',
                    'Up to 5',
                    'Up to 20',
                    'Unlimited'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'With paid Zapier plans, you can connect unlimited apps in a single Zap.'
            ],
            [
                'question_text' => 'What is a filter in Zapier?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To block spam emails',
                    'To only run the Zap if certain conditions are met',
                    'To delete data',
                    'To schedule automation'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Filters in Zapier allow you to only run the Zap if certain conditions are met.'
            ],
            [
                'question_text' => 'What is a path in Zapier?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A way to branch your Zap based on conditions',
                    'A way to delete data',
                    'A way to schedule automation',
                    'A way to connect apps'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Paths allow you to create branches in your Zap based on different conditions, with each branch having its own set of actions.'
            ],
            [
                'question_text' => 'How do you authenticate with apps in Zapier?',
                'question_text' => 'How do you authenticate with apps in Zapier?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using API keys',
                    'Using OAuth',
                    'Both API keys and OAuth',
                    'By guessing passwords'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'Zapier supports both API keys and OAuth for authentication, with OAuth being the more common method for most apps.'
            ],
            [
                'question_text' => 'What does the "Test & Continue" button do?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Deletes your Zap',
                    'Tests your Zap to ensure it works',
                    'Schedules your Zap',
                    'Duplicates your Zap'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'The "Test & Continue" button tests your Zap to ensure it works correctly.'
            ],
            [
                'question_text' => 'How can you view your Zap history?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'In the Zap History section',
                    'In your email inbox',
                    'On your desktop',
                    'You can\'t view Zap history'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'You can view your Zap history in the Zap History section of the Zapier interface.'
            ],
            [
                'question_text' => 'What does the "Pause" button do?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Permanently deletes the Zap',
                    'Temporarily stops the Zap from running',
                    'Deletes all Zap history',
                    'Duplicates the Zap'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'The "Pause" button temporarily stops the Zap from running.'
            ],
            [
                'question_text' => 'What is the difference between a "Multi-step Zap" and a "Single-step Zap"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Single-step Zaps are free',
                    'Multi-step Zaps have more actions',
                    'Single-step Zaps can\'t connect apps',
                    'Multi-step Zaps are always paid'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Multi-step Zaps have more than one action, allowing for more complex automation.'
            ]
        ],
        'Make.com Fundamentals Quiz' => [
            [
                'question_text' => 'What is the equivalent of a "Zap" in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Scenario',
                    'Automation',
                    'Workflow',
                    'Pipeline'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'In Make.com, an automation is called a "Scenario".'
            ],
            [
                'question_text' => 'What are the building blocks of Make.com scenarios?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Nodes',
                    'Blocks',
                    'Cells',
                    'Elements'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Scenarios in Make.com are built using "Nodes".'
            ],
            [
                'question_text' => 'What is a "Router" in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To connect to the internet',
                    'To create branches in your scenario',
                    'To delete data',
                    'To schedule automation'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Routers are used to create branches in your scenarios for different paths based on conditions.'
            ],
            [
                'question_text' => 'How do you handle errors in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'You can\'t handle errors',
                    'Using error handling features',
                    'By deleting the scenario',
                    'By ignoring them'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Make.com provides error handling features to make your automations more robust.'
            ],
            [
                'question_text' => 'What is data mapping in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Transforming data between applications',
                    'Creating databases',
                    'Scheduling automation',
                    'Designing user interfaces'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Data mapping allows you to transform and manipulate data between different formats and applications.'
            ],
            [
                'question_text' => 'What type of node is used for HTTP requests?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'HTTP node',
                    'API node',
                    'Request node',
                    'Web node'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The HTTP node is used for making custom API calls in Make.com.'
            ],
            [
                'question_text' => 'What is a webhook in Make.com?',
                'question_text' => 'What is a webhook in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A way to receive data from external services',
                    'A way to send emails',
                    'A way to store data',
                    'A way to schedule automation'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Webhooks allow Make.com to receive data from external services in real-time.'
            ],
            [
                'question_text' => 'How do you schedule scenarios in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using the Schedule module',
                    'Using time triggers',
                    'Using webhooks',
                    'Both A and B'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'You can schedule scenarios in Make.com using the Schedule module or time triggers.'
            ],
            [
                'question_text' => 'What is a "Replay" in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To delete a scenario',
                    'To run a scenario again',
                    'To duplicate a scenario',
                    'To pause a scenario'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'You can replay past scenario runs to troubleshoot issues.'
            ],
            [
                'question_text' => 'What does the "Data Store" module allow you to do?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Store data in memory between runs',
                    'Send emails',
                    'Make HTTP requests',
                    'Connect to databases'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Data Store module allows you to store data in memory between scenario runs for more complex workflows.'
            ]
        ],
        'Advanced Make.com Quiz' => [
            [
                'question_text' => 'What is the purpose of the "HTTP" module in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To send emails',
                    'To make custom API calls',
                    'To store data',
                    'To schedule automation'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'The HTTP module is used to make custom API calls that aren\'t directly supported by other nodes.'
            ],
            [
                'question_text' => 'What are webhooks used for in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To receive real-time data',
                    'To send data on a schedule',
                    'To store data in a database',
                    'To delete data'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Webhooks allow Make.com to receive real-time data from external services.'
            ],
            [
                'question_text' => 'What is conditional logic used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To make your scenario run faster',
                    'To add if-then-else decisions',
                    'To schedule automation',
                    'To store data'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Conditional logic adds if-then-else decisions to your scenario for advanced branching.'
            ],
            [
                'question_text' => 'What is scheduling in automation terms?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Making the scenario run on a time interval',
                    'Making the scenario run faster',
                    'Making the scenario run in the future',
                    'Making the scenario run in reverse'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Scheduling allows you to run your scenario on a specific time interval (daily, hourly, etc.).'
            ],
            [
                'question_text' => 'How would you deploy a scenario to production?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'By clicking "Production"',
                    'By running it manually',
                    'By scheduling it',
                    'By using a webhook'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'In Make.com, you deploy a scenario to production by clicking the "Production" button.'
            ],
            [
                'question_text' => 'What is error handling?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Deleting all errors',
                    'Handling errors gracefully so the scenario continues',
                    'Ignoring errors',
                    'Letting errors crash the scenario'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Error handling allows your scenario to continue running even if some operations fail.'
            ],
            [
                'question_text' => 'What are loops used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To repeat actions multiple times',
                    'To make your scenario run faster',
                    'To store data',
                    'To delete data'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Loops are used to repeat actions multiple times over a dataset.'
            ],
            [
                'question_text' => 'What is the difference between "Update" and "Create" operations in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Update modifies existing records, Create adds new records',
                    'Update deletes records, Create modifies records',
                    'There\'s no difference',
                    'Update is for databases, Create is for APIs'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Update operations modify existing records, while Create operations add new records.'
            ],
            [
                'question_text' => 'What is JSON used for in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Data serialization format',
                    'Database language',
                    'Programming language',
                    'Email format'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'JSON (JavaScript Object Notation) is a lightweight data-interchange format used in APIs and automation.'
            ],
            [
                'question_text' => 'What is a "Bundle" in Make.com?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A type of node',
                    'A way to group related data',
                    'A way to delete data',
                    'A way to send emails'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Bundles are used to group related data when working with arrays and iterations in Make.com.'
            ]
        ],
        'Airtable Basics Quiz' => [
            [
                'question_text' => 'What is an Airtable "Base"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A single database',
                    'A table within a database',
                    'A field within a table',
                    'A user account'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'A Base is a single database in Airtable.'
            ],
            [
                'question_text' => 'What is a "Field" in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A single database',
                    'A table within a database',
                    'A column within a table',
                    'A row within a table'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'A Field is equivalent to a column in a traditional database table.'
            ],
            [
                'question_text' => 'What is a "Record" in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A column',
                    'A row',
                    'A table',
                    'A database'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'A Record is equivalent to a row in a traditional database table.'
            ],
            [
                'question_text' => 'What are Airtable "Views"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Different ways to view the same data',
                    'Different databases',
                    'Different users',
                    'Different tables'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Views are different ways to view and organize the same data in Airtable.'
            ],
            [
                'question_text' => 'What type of field is used for numbers in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Text field',
                    'Number field',
                    'Currency field',
                    'All of the above'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'Numbers can be stored in Number fields, Currency fields, or Text fields in Airtable.'
            ],
            [
                'question_text' => 'What type of field is used to store dates in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Text field',
                    'Number field',
                    'Date field',
                    'Formula field'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'Airtable has a specific Date field type for storing dates and times.'
            ],
            [
                'question_text' => 'What is an Airtable "Lookup" field?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A field that searches the internet',
                    'A field that looks up data from linked records',
                    'A field that searches within the same table',
                    'A field that deletes data'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Lookup fields pull in data from linked records in other tables.'
            ],
            [
                'question_text' => 'What does the "Collaboration" feature allow?',
                'question_text' => 'What does the "Collaboration" feature allow?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Multiple people to work on the same base',
                    'Multiple bases to be combined',
                    'Multiple fields to be combined',
                    'Multiple views to be combined'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Collaboration allows multiple people to work on the same Airtable base with different permissions.'
            ],
            [
                'question_text' => 'What is an Airtable "Form View"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A way to view forms',
                    'A way to create forms',
                    'A way to delete forms',
                    'A way to duplicate forms'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Form views allow you to create custom forms to collect data into your Airtable base.'
            ],
            [
                'question_text' => 'What is the purpose of the "Gallery View"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To view images',
                    'To view records as cards',
                    'To view tables',
                    'To view databases'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Gallery view displays records as cards, making it ideal for visual data.'
            ]
        ],
        'Advanced Airtable Quiz' => [
            [
                'question_text' => 'What is a "Linked Record" in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A record that links to another table',
                    'A record that links to another database',
                    'A record that links to the internet',
                    'A record that links to a user'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Linked records allow you to create relationships between records in different tables within the same base.'
            ],
            [
                'question_text' => 'What is a "Rollup" field used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Calculations on linked records',
                    'Summing fields in the same table',
                    'Counting fields in the same table',
                    'All of the above'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Rollup fields allow you to perform calculations on linked records from other tables.'
            ],
            [
                'question_text' => 'What are formula fields?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Fields that contain formulas',
                    'Fields that calculate based on other fields',
                    'Fields that store numbers',
                    'Fields that store text'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Formula fields calculate their values based on data from other fields in the same record.'
            ],
            [
                'question_text' => 'What is the difference between a Lookup and a Rollup?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Lookups retrieve values, Rollups calculate values',
                    'Lookups are for numbers, Rollups are for text',
                    'There\'s no difference',
                    'Lookups are for formulas, Rollups are for fields'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Lookups simply retrieve values from linked records, while Rollups can perform calculations on those values.'
            ],
            [
                'question_text' => 'What is the purpose of the "Airtable API"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To connect to other services',
                    'To retrieve and manipulate data programmatically',
                    'To send emails',
                    'To store data'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'The Airtable API allows you to retrieve and manipulate data programmatically using HTTP requests.'
            ],
            [
                'question_text' => 'What does the "Pro" plan allow?',
                'question_text' => 'What does the "Pro" plan allow?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'More records per base',
                    'More advanced features',
                    'More collaborators',
                    'All of the above'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'The Pro plan includes more records per base, advanced features like automations, and more collaborators.'
            ],
            [
                'question_text' => 'How do you create an automation in Airtable?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using the Automations tab',
                    'Using JavaScript',
                    'Using SQL',
                    'You can\'t create automations'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Automations in Airtable are created in the Automations tab with a visual builder.'
            ],
            [
                'question_text' => 'What are Airtable "Extensions"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Additional features that extend Airtable functionality',
                    'Extra tables in your base',
                    'Extra fields in your table',
                    'Extra views in your table'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Extensions are optional add-ons that extend Airtable\'s functionality with features like dashboards, Gantt charts, etc.'
            ],
            [
                'question_text' => 'What is the "Airtable Scripting" extension?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To write JavaScript scripts',
                    'To write SQL queries',
                    'To write Python scripts',
                    'To write HTML'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Scripting extension allows you to write JavaScript scripts to automate custom tasks in Airtable.'
            ],
            [
                'question_text' => 'What is the purpose of the "Interface Designer"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To create custom user interfaces',
                    'To create tables',
                    'To create views',
                    'To create fields'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Interface Designer allows you to create custom user interfaces for your Airtable bases.'
            ]
        ],
        'N8N Basics Quiz' => [
            [
                'question_text' => 'What type of application is N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Cloud-only',
                    'On-premises only',
                    'Both cloud and on-premises',
                    'Desktop-only'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'N8N can be used both as a cloud service and as an on-premises solution.'
            ],
            [
                'question_text' => 'What is a "Node" in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A type of user',
                    'A type of database',
                    'A type of automation',
                    'A building block for workflows'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'Nodes are the building blocks of N8N workflows, representing actions or triggers.'
            ],
            [
                'question_text' => 'What is a "Trigger Node"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A node that starts the workflow',
                    'A node that stops the workflow',
                    'A node that triggers an email',
                    'A node that triggers a database'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Trigger nodes start the workflow, either manually, on a schedule, or via webhook.'
            ],
            [
                'question_text' => 'What is an "Action Node"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A node that starts the workflow',
                    'A node that performs an action',
                    'A node that stops the workflow',
                    'A node that triggers another node'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'Action nodes perform specific tasks like sending emails, writing to databases, etc.'
            ],
            [
                'question_text' => 'How are N8N workflows executed?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Sequentially',
                    'In parallel',
                    'Both sequentially and in parallel',
                    'Randomly'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'N8N supports both sequential execution and parallel execution of workflow branches.'
            ],
            [
                'question_text' => 'What is data transformation in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Changing data from one format to another',
                    'Deleting data',
                    'Storing data',
                    'Sending data'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Data transformation in N8N involves modifying data from one format to another using tools like Set or Function nodes.'
            ],
            [
                'question_text' => 'What does the "IF" node do?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Stops the workflow',
                    'Starts the workflow',
                    'Adds conditional logic',
                    'Deletes data'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'The IF node adds conditional logic to your workflow, allowing it to branch based on conditions.'
            ],
            [
                'question_text' => 'What is the "Set" node used for?',
                'question_text' => 'What is the "Set" node used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To set variables',
                    'To set data structures',
                    'To set permissions',
                    'To set time'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'The Set node is used to define or modify the structure of your data.'
            ],
            [
                'question_text' => 'What is the purpose of the "Code" node?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To write custom JavaScript',
                    'To write SQL queries',
                    'To write HTML',
                    'To write Python'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Code node allows you to write custom JavaScript code for complex operations.'
            ],
            [
                'question_text' => 'What is the difference between N8N and Zapier?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'N8N is open-source, Zapier is closed-source',
                    'N8N is cheaper',
                    'N8N is more powerful',
                    'All of the above'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'N8N is open-source and can be self-hosted, providing more flexibility and often being more cost-effective for large-scale use.'
            ]
        ],
        'Advanced N8N Quiz' => [
            [
                'question_text' => 'What is conditional logic in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using the IF node to branch workflows',
                    'Using the Code node to write if-then-else statements',
                    'Both A and B',
                    'N8N doesn\'t support conditional logic'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'N8N supports conditional logic using both the IF node and through custom JavaScript in the Code node.'
            ],
            [
                'question_text' => 'What are loops used for in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To repeat actions for each item in a list',
                    'To run the same workflow repeatedly',
                    'To make the workflow run faster',
                    'To store data'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Loops in N8N allow you to repeat actions for each item in a list of data.'
            ],
            [
                'question_text' => 'How do you handle errors in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Using error nodes and error handling features',
                    'Using try-catch in the Code node',
                    'Both A and B',
                    'You can\'t handle errors in N8N'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'N8N provides error handling nodes and allows for custom error handling using JavaScript try-catch blocks in the Code node.'
            ],
            [
                'question_text' => 'What options are available for scheduling workflows in N8N?',
                'question_text' => 'What options are available for scheduling workflows in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Cron expressions',
                    'Specific times',
                    'Recurring intervals',
                    'All of the above'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'N8N supports scheduling using cron expressions, specific times, or recurring intervals.'
            ],
            [
                'question_text' => 'What is a "Webhook" in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'A way for external services to trigger your workflow',
                    'A way to send data to external services',
                    'A way to store data',
                    'A way to delete data'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Webhooks in N8N allow external services to trigger your workflow by sending HTTP requests.'
            ],
            [
                'question_text' => 'How are credentials managed in N8N?',
                'question_text' => 'How are credentials managed in N8N?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Stored securely in N8N\'s credential system',
                    'Hardcoded in nodes',
                    'Stored in text files',
                    'Stored in the database'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Credentials are securely managed in N8N\'s credential system, never exposed in the workflow definitions.'
            ],
            [
                'question_text' => 'What is the "Merge" node used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To merge two data streams',
                    'To merge nodes',
                    'To merge workflows',
                    'To merge credentials'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Merge node allows you to combine two separate data streams into one.'
            ],
            [
                'question_text' => 'What is the "Split" node used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To split a data stream into multiple branches',
                    'To split nodes',
                    'To split credentials',
                    'To split workflows'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Split node allows you to split a data stream into multiple branches for parallel processing.'
            ],
            [
                'question_text' => 'What is the "Delay" node used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To delay the execution of subsequent nodes',
                    'To delete nodes',
                    'To delay the start of the workflow',
                    'To delay credentials'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'The Delay node adds a pause between the execution of nodes in your workflow.'
            ],
            [
                'question_text' => 'What type of authentication does N8N support?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Basic auth',
                    'OAuth',
                    'API keys',
                    'All of the above'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'N8N supports multiple authentication methods including basic auth, OAuth, API keys, and more.'
            ]
        ],
        'Data Automation Final Exam' => [
            [
                'question_text' => 'Which automation tool is self-hosted and open-source?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Zapier',
                    'Make.com',
                    'N8N',
                    'Airtable'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'N8N is the only tool listed that is self-hosted and open-source.'
            ],
            [
                'question_text' => 'What is the primary benefit of automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Saving time and reducing errors',
                    'Making more money',
                    'Making your computer run faster',
                    'Making your internet faster'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Automation primarily saves time and reduces errors by eliminating manual repetitive tasks.'
            ],
            [
                'question_text' => 'What is a "Trigger"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'What starts an automation',
                    'What the automation does',
                    'What stops the automation',
                    'What schedules the automation'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'A trigger is the event that starts an automation workflow.'
            ],
            [
                'question_text' => 'Which tool would be best for a large team with specific compliance needs?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Zapier',
                    'N8N',
                    'Make.com',
                    'Airtable'
                ],
                'correct_option' => 1,
                'points' => 1.00,
                'explanation' => 'N8N\'s self-hosted, open-source nature makes it ideal for large teams with specific compliance and security needs.'
            ],
            [
                'question_text' => 'What are webhooks used for?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Real-time communication between applications',
                    'Storing data',
                    'Sending emails',
                    'Creating forms'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Webhooks allow for real-time communication between applications by sending data immediately when an event occurs.'
            ],
            [
                'question_text' => 'Which of these is a database tool with automation capabilities?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Airtable',
                    'Zapier',
                    'N8N',
                    'Make.com'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Airtable is primarily a database tool with built-in automation capabilities.'
            ],
            [
                'question_text' => 'What is the purpose of conditional logic in automation?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'To make the automation more complex',
                    'To make the automation more efficient',
                    'To make the automation make decisions',
                    'To make the automation faster'
                ],
                'correct_option' => 2,
                'points' => 1.00,
                'explanation' => 'Conditional logic allows the automation to make if-then-else decisions based on data values.'
            ],
            [
                'question_text' => 'Which of these is NOT a valid automation tool feature?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Triggers',
                    'Actions',
                    'Conditional logic',
                    'HTML editing'
                ],
                'correct_option' => 3,
                'points' => 1.00,
                'explanation' => 'HTML editing is not a standard feature of automation tools; the other options are core features.'
            ],
            [
                'question_text' => 'What is the difference between "Make.com" and "Zapier"?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Make.com is more powerful but has a steeper learning curve',
                    'Zapier is more powerful but more expensive',
                    'Make.com is for beginners, Zapier is for experts',
                    'There\'s no difference'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Make.com is generally considered more powerful for complex automations but has a steeper learning curve compared to Zapier.'
            ],
            [
                'question_text' => 'What is the key difference between Airtable and traditional databases?',
                'question_text' => 'What is the key difference between Airtable and traditional databases?',
                'question_type' => 'multiple_choice',
                'options' => [
                    'Airtable is more visual and user-friendly',
                    'Airtable is more powerful',
                    'Airtable is faster',
                    'Airtable is more expensive'
                ],
                'correct_option' => 0,
                'points' => 1.00,
                'explanation' => 'Airtable stands out from traditional databases due to its highly visual, user-friendly interface that doesn\'t require technical expertise.'
            ]
        ]
    ];
    
    // Process each quiz
    foreach ($quizzes as $quiz) {
        echo "Processing quiz: " . $quiz['title'] . " (ID: " . $quiz['id'] . ")\n";
        
        // Check if quiz has existing questions
        $existingQuestions = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?', [$quiz['id']]);
        
        if ($existingQuestions == 10) {
            echo "  Quiz already has 10 questions - skipping\n";
            continue;
        } elseif ($existingQuestions > 0) {
            echo "  Deleting existing " . $existingQuestions . " questions\n";
            // Delete existing questions and their options
            $questions = DatabaseConnection::fetchAll('SELECT id FROM quiz_questions WHERE quiz_id = ?', [$quiz['id']]);
            foreach ($questions as $question) {
                DatabaseConnection::delete('quiz_options', 'question_id = ?', [$question['id']]);
            }
            DatabaseConnection::delete('quiz_questions', 'quiz_id = ?', [$quiz['id']]);
        }
        
        // Check if we have questions for this quiz
        $quizTitle = $quiz['title'];
        if (!isset($quizQuestions[$quizTitle])) {
            echo "  No questions defined for this quiz title\n";
            continue;
        }
        
        // Add 10 questions to the quiz
        $questions = $quizQuestions[$quizTitle];
        foreach ($questions as $index => $questionData) {
            // Create the question
            $questionId = DatabaseConnection::insert('quiz_questions', [
                'quiz_id' => $quiz['id'],
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'points' => $questionData['points'],
                'order_index' => $index + 1,
                'explanation' => $questionData['explanation']
            ]);
            
            // Create the options
            foreach ($questionData['options'] as $optIndex => $optionText) {
                DatabaseConnection::insert('quiz_options', [
                    'question_id' => $questionId,
                    'option_text' => $optionText,
                    'is_correct' => ($optIndex == $questionData['correct_option']) ? 1 : 0,
                    'order_index' => $optIndex + 1
                ]);
            }
        }
        
        echo "  ✅ Added 10 questions to quiz\n";
    }
    
    echo "\n=== Verification ===\n";
    $totalQuestions = 0;
    foreach ($quizzes as $quiz) {
        $count = DatabaseConnection::fetchColumn('SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = ?', [$quiz['id']]);
        echo $quiz['title'] . ": " . $count . " questions\n";
        $totalQuestions += $count;
    }
    
    echo "\nTotal questions added: " . $totalQuestions . "\n";
    
    if ($totalQuestions == 90) {
        echo "✅ All quizzes now have exactly 10 questions!";
    } else {
        echo "⚠️  Total questions: " . $totalQuestions . " (expected 90)";
    }
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    if (isset($e->errorInfo)) {
        echo "SQL Error: " . print_r($e->errorInfo, true);
    }
}
?>
