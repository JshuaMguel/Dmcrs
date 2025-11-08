<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to USTP DMCRS</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: #1f2937; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { padding: 20px 0; }
        .credentials { background: #f8fafc; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #3b82f6; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; color: #666; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to USTP DMCRS!</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            
            <p>Your account has been successfully created for the <strong>University of Science and Technology of the Philippines (USTP) - Digital Makeup Class Request System</strong>.</p>
            
            <div class="credentials">
                <h3>Your Account Details</h3>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Password:</strong> {{ $password }}</p>
                <p><strong>Role:</strong> {{ ucwords(str_replace('_', ' ', $user->role)) }}</p>
                @if(isset($user->department) && $user->department)
                <p><strong>Department:</strong> {{ $user->department->name }}</p>
                @endif
            </div>
            
            <h3>Getting Started</h3>
            <p>You can now log in to the system using the credentials above. Please follow these steps:</p>
            
            <ol>
                <li>Visit the <a href="https://dmcrs.onrender.com/login">DMCRS Login Page</a></li>
                <li>Enter your email and password</li>
                <li>Complete your profile information</li>
                <li>Start using the system</li>
            </ol>
            
            <a href="https://dmcrs.onrender.com/login" class="button">Login to DMCRS</a>
            
            <h3>Important Security Notice</h3>
            <p><strong>Please change your password</strong> after your first login for security purposes.</p>
            
            <p>If you have any questions or need assistance, please contact your system administrator.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from USTP Balubal Campus - DMCRS<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>