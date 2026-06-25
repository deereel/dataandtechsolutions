<?php
// Generate valid password hashes
echo "admin123: " . password_hash('admin123', PASSWORD_DEFAULT) . "\n";
echo "admin456: " . password_hash('admin456', PASSWORD_DEFAULT) . "\n";
echo "admin789: " . password_hash('admin789', PASSWORD_DEFAULT) . "\n";
?>