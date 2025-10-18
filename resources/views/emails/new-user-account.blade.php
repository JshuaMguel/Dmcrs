<x-mail::message>
# Welcome to USTP DMCRS!

Hello **{{ $user->name }}**,

Your account has been successfully created for the **University of Science and Technology of the Philippines (USTP) - Digital Makeup Class Request System**.

## Your Account Details

**Email:** {{ $user->email }}
**Password:** {{ $password }}
**Role:** {{ ucwords(str_replace('_', ' ', $user->role)) }}
@if($user->department)
**Department:** {{ $user->department->name }}
@endif

## Getting Started

You can now log in to the system using the credentials above. Please follow these steps:

1. Visit the DMCRS login page
2. Enter your email and password
3. **Important:** Change your password after first login for security

<x-mail::button :url="config('app.url') . '/login'">
Login to DMCRS
</x-mail::button>

## System Features

Based on your role, you will have access to:

@if($user->role === 'faculty')
- Create makeup class requests
- View your request history
- Receive notifications about request status
@elseif($user->role === 'department_chair')
- Review and approve/reject makeup class requests
- View department request history
- Manage department faculty requests
@elseif($user->role === 'academic_head')
- Final approval authority for all requests
- System-wide oversight and reporting
- Manage all departments and requests
@endif

If you have any questions or need assistance, please contact your system administrator.

Best regards,<br>
**USTP DMCRS Team**<br>
University of Science and Technology of the Philippines
</x-mail::message>
