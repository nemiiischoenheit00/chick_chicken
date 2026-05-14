<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="Image" href="assets/Logo.png"/>
    <title>Change Profile | Chick Chicken</title>
    <style>@import url('https://fonts.googleapis.com/css2?family=Oswald:wght@200..700&display=swap');</style>
    <style>@import url('https://fonts.googleapis.com/css2?family=Alegreya+Sans:ital,wght@0,100;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,300;1,400;1,500;1,700;1,800;1,900&family=Oswald:wght@200..700&display=swap');</style>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body, html {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: #f7f3ec;
            font-family: 'Alegreya Sans', sans-serif;
        }

        /* ── LAYOUT ── */
        .change-profile {
            min-height: 100vh;
            display: flex;
            align-items: stretch;
        }

        .separator {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ── LEFT PANEL ── */
        .greetings {
            flex: 1 1 45%;
            background: #FFDE59;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px 56px;
            position: relative;
            overflow: hidden;
            #1a1a1a
        }

        .greetings h1 {
            font-family: 'Oswald', sans-serif;
            font-size: clamp(2.4rem, 4vw, 3.6rem);
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 24px;
            line-height: 1.1;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .greetings p {
            font-size: 1.25rem;
            color: #1a1a1a;
            line-height: 1.75;
            max-width: 420px;
            margin: 0;
            opacity: 0.85;
        }

        .greetings .back-link {
            margin-top: 40px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-family: 'Oswald', sans-serif;
            font-size: 1.15rem;
            color: #D62828;
            text-decoration: none;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: gap 0.2s;
        }
        .greetings .back-link:hover { gap: 12px; }

        /* ── RIGHT PANEL ── */
        .profile-form {
            flex: 1 1 55%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            background: #fff;
        }

        .form-box {
            width: 100%;
            max-width: 520px;
        }

        .form-box .logo {
            display: block;
            height: 52px;
            width: auto;
            margin: 0 auto 18px;
        }

        .form-box h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 1.9rem;
            font-weight: 600;
            color: #1a1a1a;
            text-align: center;
            margin: 0 0 6px;
            letter-spacing: 0.5px;
        }

        .form-box .subtitle {
            text-align: center;
            color: #888;
            font-size: 0.95rem;
            margin: 0 0 28px;
        }

        /* ── ALERTS ── */
        .alert-msg {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 14px;
            font-size: 0.93rem;
            text-align: left;
        }
        .alert-error   { background: #ffe5e5; color: #b00020; border: 1px solid #f5c2c2; }
        .alert-success { background: #e6f4ea; color: #1e6e34; border: 1px solid #b7dfc4; }

        /* ── FORM FIELDS ── */
        .field-row {
            display: flex;
            gap: 14px;
        }
        .field-row .field-group { flex: 1; }

        .field-group {
            margin-bottom: 14px;
        }

        .field-group label {
            display: block;
            font-family: 'Oswald', sans-serif;
            font-size: 0.88rem;
            font-weight: 500;
            color: #555;
            margin-bottom: 5px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .field-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Alegreya Sans', sans-serif;
            font-size: 1rem;
            color: #222;
            background: #fafafa;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            outline: none;
        }
        .field-group input:focus {
            border-color: #D62828;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(214,40,40,0.08);
        }
        .field-group input::placeholder { color: #bbb; }

        /* ── DIVIDER ── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 18px 0 16px;
        }
        .section-divider span {
            font-family: 'Oswald', sans-serif;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #aaa;
            white-space: nowrap;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #eee;
        }

        /* ── PASSWORD TOGGLE ── */
        .password-wrap {
            position: relative;
        }
        .password-wrap input {
            padding-right: 44px;
        }
        .toggle-pw {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #D62828; }

        /* ── SUBMIT ── */
        .btn-save {
            width: 100%;
            padding: 13px;
            background: #D62828;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'Oswald', sans-serif;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.8px;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, box-shadow 0.2s;
        }
        .btn-save:hover {
            background: #b81e1e;
            box-shadow: 0 4px 18px rgba(214,40,40,0.25);
        }

        .note-text {
            text-align: center;
            font-size: 0.85rem;
            color: #aaa;
            margin-top: 14px;
        }
        .note-text span {
            color: #D62828;
            font-weight: 600;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .separator { flex-direction: column; }
            .greetings { padding: 40px 28px; flex: none; }
            .profile-form { padding: 40px 24px; flex: none; }
            .field-row { flex-direction: column; gap: 0; }
        }
    </style>
</head>
<body>
<section class="change-profile">
    <div class="separator">

<!-- LEFT -->
<div class="greetings">
    <h1>Greetings, Our Beloved Customer!</h1>
    <p>Welcome to Chick Chicken! Sign in to be part of our growing family of chicken lovers. Whether you're here to browse, order, or just check out what's new, we're happy to have you around. Go ahead — sign in and let's make your day a little more delicious.</p>
    <a href="index.php" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Back to Home
    </a>
</div>

        <!-- RIGHT -->
        <div class="profile-form">
            <div class="form-box">
                <img src="assets/Logo2.png" alt="Chick Chicken Logo" class="logo">
                <h2>Change Profile</h2>
                <p class="subtitle">Edit your account details below</p>

                <?php
                if (isset($_GET['error'])) {
                    $errors = [
                        'missing_fields'   => 'Please fill in all required fields.',
                        'invalid_email'    => 'Please enter a valid email address.',
                        'email_taken'      => 'That email is already in use by another account.',
                        'wrong_password'   => 'Your current password is incorrect.',
                        'password_match'   => 'New passwords do not match.',
                        'password_short'   => 'New password must be at least 8 characters.',
                        'db_error'         => 'Something went wrong. Please try again.',
                    ];
                    $msg = $errors[$_GET['error']] ?? 'An error occurred. Please try again.';
                    echo '<div class="alert-msg alert-error">' . htmlspecialchars($msg) . '</div>';
                }
                if (isset($_GET['success'])) {
                    echo '<div class="alert-msg alert-success">✓ Your profile has been updated successfully.</div>';
                }

                $firstName = htmlspecialchars($_SESSION['first_name'] ?? '');
                $lastName  = htmlspecialchars($_SESSION['last_name']  ?? '');
                $phone     = htmlspecialchars($_SESSION['phone']      ?? '');
                $email     = htmlspecialchars($_SESSION['email']      ?? '');
                ?>

                <form action="change_profile_process.php" method="POST">

                    <div class="section-divider"><span>Personal Info</span></div>

                    <div class="field-row">
                        <div class="field-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name"
                                   placeholder="Juan" value="<?= $firstName ?>" required>
                        </div>
                        <div class="field-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name"
                                   placeholder="Dela Cruz" value="<?= $lastName ?>" required>
                        </div>
                    </div>

                    <div class="field-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone"
                               placeholder="+63 9XX XXX XXXX" value="<?= $phone ?>" required>
                    </div>

                    <div class="field-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                               placeholder="you@email.com" value="<?= $email ?>" required>
                    </div>

                    <div class="section-divider"><span>Change Password</span></div>

                    <div class="field-group">
                        <label for="current_password">Current Password</label>
                        <div class="password-wrap">
                            <input type="password" id="current_password" name="current_password"
                                   placeholder="Enter current password">
                            <button type="button" class="toggle-pw" data-target="current_password" aria-label="Toggle visibility">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field-group">
                            <label for="new_password">New Password</label>
                            <div class="password-wrap">
                                <input type="password" id="new_password" name="new_password"
                                       placeholder="Min. 8 characters">
                                <button type="button" class="toggle-pw" data-target="new_password" aria-label="Toggle visibility">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="field-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <div class="password-wrap">
                                <input type="password" id="confirm_password" name="confirm_password"
                                       placeholder="Repeat new password">
                                <button type="button" class="toggle-pw" data-target="confirm_password" aria-label="Toggle visibility">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <p class="note-text">Leave password fields <span>blank</span> to keep your current password.</p>

                    <button type="submit" class="btn-save">Save Changes</button>
                </form>
            </div>
        </div>

    </div>
</section>

<script>
document.querySelectorAll('.toggle-pw').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var input = document.getElementById(this.dataset.target);
        var isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        this.innerHTML = isText
            ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>'
            : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
    });
});
</script>
</body>
</html>