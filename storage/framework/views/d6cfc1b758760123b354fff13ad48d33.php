<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Smart Feedback System — Login</title>
    <meta name="description" content="Login to the Smart Feedback & Attendance Portal for IITI">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #EBF5FF 0%, #DBEAFE 40%, #BFDBFE 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
        }

        /* Branding */
        .branding {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }
        .brand-icon svg { color: white; }
        .brand-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.02em;
        }
        .brand-subtitle {
            font-size: 0.875rem;
            color: #64748B;
            margin-top: 4px;
            font-weight: 400;
        }

        /* Card */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 4px 40px rgba(37, 99, 235, 0.10), 0 1px 6px rgba(0,0,0,0.06);
            padding: 28px 32px 32px;
            border: 1px solid rgba(219, 234, 254, 0.8);
        }

        /* Role Tabs */
        .role-tabs {
            display: flex;
            background: #F1F5F9;
            border-radius: 10px;
            padding: 4px;
            gap: 2px;
            margin-bottom: 24px;
        }
        .role-tab {
            flex: 1;
            padding: 8px 12px;
            border: none;
            background: transparent;
            border-radius: 7px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .role-tab.active {
            background: #ffffff;
            color: #1D4ED8;
            font-weight: 600;
            box-shadow: 0 1px 4px rgba(0,0,0,0.10);
        }
        .role-tab:hover:not(.active) {
            color: #374151;
        }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 7px;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon {
            position: absolute;
            left: 13px;
            color: #94A3B8;
            display: flex;
            align-items: center;
        }
        .form-input {
            width: 100%;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 10px 12px 10px 40px;
            font-size: 0.9rem;
            color: #0F172A;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: all 0.2s;
            background: #FAFBFC;
        }
        .form-input:focus {
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            background: #fff;
        }
        .form-input::placeholder { color: #94A3B8; }

        /* Password row */
        .password-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 7px;
        }
        .forgot-link {
            font-size: 0.8rem;
            color: #2563EB;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }

        /* Submit */
        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.30);
            margin-top: 8px;
            letter-spacing: 0.01em;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.40);
        }
        .btn-login:active { transform: translateY(0); }

        /* Alerts */
        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            color: #B91C1C;
            margin-bottom: 16px;
        }
        .alert-info {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: #1E40AF;
            margin-bottom: 16px;
        }
        .alert-info code {
            font-family: 'Courier New', monospace;
            background: rgba(37,99,235,0.12);
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 0.78rem;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- Branding -->
        <div class="branding">
            <div class="brand-icon">
                <svg width="30" height="30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <div class="brand-title">Smart Feedback System</div>
            <div class="brand-subtitle">Feedback &amp; Attendance Portal</div>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <!-- Demo credentials hint -->
            <div class="alert-info" id="demoHint">
                Demo: <code>alice@student.iiti.ac.in</code> — password: <code>password</code>
            </div>

            <?php if($errors->any()): ?>
            <div class="alert-error">
                <?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <?php if(session('status')): ?>
            <div class="alert-info"><?php echo e(session('status')); ?></div>
            <?php endif; ?>

            <!-- Role Tabs -->
            <div class="role-tabs" id="roleTabs">
                <button type="button" class="role-tab active" id="tab-student" onclick="switchTab('student')">Student</button>
                <button type="button" class="role-tab" id="tab-faculty"  onclick="switchTab('faculty')">Faculty</button>
                <button type="button" class="role-tab" id="tab-staff"    onclick="switchTab('staff')">Staff</button>
                <button type="button" class="role-tab" id="tab-admin"    onclick="switchTab('admin')">Admin</button>
            </div>

            <!-- Login Form -->
            <form method="POST" action="<?php echo e(route('login.post')); ?>" id="loginForm">
                <?php echo csrf_field(); ?>

                <!-- Email / Roll Number -->
                <div class="form-group">
                    <label class="form-label" for="email" id="emailLabel">Institute Email / Roll Number</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input id="email" name="email" type="email"
                               class="form-input"
                               value="<?php echo e(old('email')); ?>"
                               placeholder="student@iiti.ac.in"
                               required autofocus autocomplete="username">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group" style="margin-bottom:22px">
                    <div class="password-row">
                        <label class="form-label" for="password" style="margin-bottom:0">Password</label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>
                    <div class="input-wrapper" style="margin-top:7px">
                        <span class="input-icon">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input id="password" name="password" type="password"
                               class="form-input"
                               placeholder="••••••••••"
                               required autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="btn-login" id="submitBtn">Login to Dashboard</button>
            </form>
        </div>
    </div>

    <script>
        const placeholders = {
            student: { placeholder: 'student@iiti.ac.in', label: 'Institute Email / Roll Number', demoEmail: 'alice@student.iiti.ac.in' },
            faculty: { placeholder: 'aris.thorne@iiti.ac.in', label: 'Institute Email', demoEmail: 'aris.thorne@iiti.ac.in' },
            staff:   { placeholder: 'staff@iiti.ac.in', label: 'Institute Email', demoEmail: 'staff@iiti.ac.in' },
            admin:   { placeholder: 'admin@iiti.ac.in',   label: 'Admin Email', demoEmail: 'admin@iiti.ac.in' },
        };

        function switchTab(role) {
            document.querySelectorAll('.role-tab').forEach(t => t.classList.remove('active'));
            document.getElementById('tab-' + role).classList.add('active');
            
            const emailInput = document.getElementById('email');
            const emailLabel = document.getElementById('emailLabel');
            const demoHint = document.getElementById('demoHint');
            
            emailInput.placeholder = placeholders[role].placeholder;
            emailLabel.textContent  = placeholders[role].label;
            demoHint.innerHTML = `Demo: <code>${placeholders[role].demoEmail}</code> — password: <code>password</code>`;
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\abhis\Web Dev\Teaching_Feeback_System\resources\views/auth/login.blade.php ENDPATH**/ ?>