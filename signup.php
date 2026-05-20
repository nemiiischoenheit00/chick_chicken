<?php
if (isset($_GET['error'])) {
    $errors = [
        'invalid_email'     => 'Please enter a valid email address.',
        'password_mismatch' => 'Passwords do not match.',
        'weak_password'     => 'Password must be at least 6 characters and contain letters and numbers. No spaces allowed.',
        'email_exists'      => 'An account with that email already exists.',
        'server_error'      => 'Something went wrong. Please try again.',
    ];
    $error_msg = $errors[$_GET['error']] ?? 'An error occurred.';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>
    <div class="login-container">
        <h2>Create an Account</h2>
        <p class="subtitle">Already have an account? <a href="login.php">Login</a></p>

        <?php if (isset($error_msg)): ?>
            <p class="error-msg"><?= htmlspecialchars($error_msg) ?></p>
        <?php endif; ?>

        <form action="signup_process.php" method="POST" onsubmit="return validateForm()">

            <div class="name-row">
                <div class="input-group">
                    <label for="Fname">First name</label>
                    <input type="text" id="Fname" name="Fname" placeholder="Enter first name" required>
                </div>
                <div class="input-group">
                    <label for="Lname">Last name</label>
                    <input type="text" id="Lname" name="Lname" placeholder="Enter last name" required>
                </div>
            </div>

            <label for="phone">Mobile number</label>
            <input
                type="text"
                id="phone"
                name="phone"
                placeholder="09171234567"
                inputmode="numeric"
                maxlength="11"
                autocomplete="tel"
                required>
            <span id="phone-error" style="display:none; color:#b00020; font-size:0.85em; margin-top:-8px; margin-bottom:8px; display:block;"></span>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>

            <div class="name-row">
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>

                    <!-- Strength bar -->
                    <div class="strength-bar-wrap">
                        <div class="strength-seg" id="seg0"></div>
                        <div class="strength-seg" id="seg1"></div>
                        <div class="strength-seg" id="seg2"></div>
                        <div class="strength-seg" id="seg3"></div>
                    </div>
                    <div class="strength-row">
                        <span class="strength-label" id="strength-label"></span>
                    </div>

                    <!-- Requirements checklist -->
                    <ul class="pw-requirements" id="pw-requirements">
                        <li id="r-len">
                            <span class="req-circle">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            At least 6 characters
                        </li>
                        <li id="r-letter">
                            <span class="req-circle">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Contains a letter
                        </li>
                        <li id="r-number">
                            <span class="req-circle">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            Contains a number
                        </li>
                        <li id="r-nospace">
                            <span class="req-circle">
                                <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 8l3.5 3.5L13 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            No spaces
                        </li>
                    </ul>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                    <span class="confirm-msg" id="confirm-msg"></span>
                </div>
            </div>
            

            <button type="submit">Sign Up</button>
        </form>
    </div>

<script>
    /* ── Phone validation ─────────────────────────────────────── */
    const phoneInput = document.getElementById('phone');
    const phoneError = document.getElementById('phone-error');

    phoneInput.addEventListener('input', function () {
        let val = this.value.replace(/\D/g, '');
        if (val.length > 11) val = val.slice(0, 11);
        this.value = val;
        phoneError.style.display = 'none';
    });

    phoneInput.addEventListener('keydown', function (e) {
        const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Home','End'];
        if (!allowed.includes(e.key) && !/^\d$/.test(e.key)) {
            e.preventDefault();
        }
    });

    phoneInput.addEventListener('paste', function (e) {
        e.preventDefault();
        let pasted = (e.clipboardData || window.clipboardData).getData('text');
        let clean  = pasted.replace(/\D/g, '').slice(0, 11);
        this.value = clean;
    });

    /* ── Password strength + requirements ────────────────────── */
    const passwordInput  = document.getElementById('password');
    const strengthLabel  = document.getElementById('strength-label');
    const pwRequirements = document.getElementById('pw-requirements');
    const segs           = [0,1,2,3].map(i => document.getElementById('seg' + i));

    const SEG_COLORS = ['#e53935', '#fb8c00', '#fdd835', '#43a047'];
    const SEG_LABELS = ['Weak', 'Fair', 'Good', 'Strong'];

    const rules = [
        { id: 'r-len',     test: v => v.length >= 6 },
        { id: 'r-letter',  test: v => /[a-zA-Z]/.test(v) },
        { id: 'r-number',  test: v => /[0-9]/.test(v) },
        { id: 'r-nospace', test: v => v.length > 0 && !/\s/.test(v) },
    ];

    function getStrength(pw) {
        if (!pw) return 0;
        let score = 0;
        if (pw.length >= 6)                        score++;
        if (pw.length >= 10)                       score++;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw))                      score++;
        if (/[^A-Za-z0-9]/.test(pw))              score++;
        return Math.max(1, Math.min(Math.round(score * 4 / 5), 4));
    }

    passwordInput.addEventListener('focus', function () {
        pwRequirements.style.display = 'flex';
    });

    passwordInput.addEventListener('input', function () {
        const val = this.value;

        pwRequirements.style.display = val.length > 0 ? 'flex' : 'none';

        rules.forEach(({ id, test }) => {
            document.getElementById(id).classList.toggle('met', test(val));
        });

        const level = val.length === 0 ? 0 : getStrength(val);
        segs.forEach((seg, i) => {
            seg.style.backgroundColor = i < level ? SEG_COLORS[level - 1] : '#e0e0e0';
        });
        strengthLabel.textContent = level > 0 ? SEG_LABELS[level - 1] : '';
        strengthLabel.style.color = level > 0 ? SEG_COLORS[level - 1] : '';
    });

    /* ── Confirm password match ───────────────────────────────── */
    const confirmInput = document.getElementById('confirm_password');
    const confirmMsg   = document.getElementById('confirm-msg');

    confirmInput.addEventListener('input', function () {
        if (!this.value) {
            confirmMsg.textContent = '';
            confirmMsg.className = 'confirm-msg';
            return;
        }
        const match = this.value === passwordInput.value;
        confirmMsg.textContent = match ? 'Passwords match' : 'Passwords do not match';
        confirmMsg.className   = 'confirm-msg ' + (match ? 'match' : 'no-match');
    });

    /* ── Combined submit validation ───────────────────────────── */
    function validateForm() {
        const val = phoneInput.value;
        if (!/^09\d{9}$/.test(val)) {
            phoneError.textContent = 'Please enter a valid 11-digit number starting with 09.';
            phoneError.style.display = 'block';
            phoneInput.focus();
            return false;
        }
        return true;
    }
</script>
</body>

</html>