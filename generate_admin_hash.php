<?php
// Generate password hash for Amara@123
$password = 'Amara@123';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password hash for '$password':\n";
echo $hash . "\n";

// Verify it works
if (password_verify($password, $hash)) {
    echo "Verification: SUCCESS\n";
} else {
    echo "Verification: FAILED\n";
}
