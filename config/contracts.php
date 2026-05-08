<?php

return [
    'otp_ttl_minutes' => env('CONTRACT_OTP_TTL_MINUTES', 10),
    'otp_max_attempts' => env('CONTRACT_OTP_MAX_ATTEMPTS', 5),
];
