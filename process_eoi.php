<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: apply.html");
    exit;
}

function clean($value) {
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, "UTF-8");
}

$jobref = clean($_POST["jobref"] ?? "");
$position = clean($_POST["position"] ?? "");
$firstname = clean($_POST["firstname"] ?? "");
$lastname = clean($_POST["lastname"] ?? "");
$dob = clean($_POST["dob"] ?? "");
$gender = clean($_POST["gender"] ?? "");
$street = clean($_POST["street"] ?? "");
$suburb = clean($_POST["suburb"] ?? "");
$state = clean($_POST["state"] ?? "");
$postcode = clean($_POST["postcode"] ?? "");
$email = clean($_POST["email"] ?? "");
$phone = clean($_POST["phone"] ?? "");
$otherskills = clean($_POST["otherskills"] ?? "");
$skills = $_POST["skills"] ?? [];

if (!is_array($skills)) {
    $skills = [];
}
$skills = array_map("clean", $skills);

$errors = [];
if (!preg_match("/^[A-Za-z0-9]{5}$/", $jobref)) {
    $errors[] = "Job reference must contain exactly 5 letters or numbers.";
}
if (!filter_var($_POST["email"] ?? "", FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Enter a valid email address.";
}
if (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must contain exactly 4 digits.";
}
if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must contain 8 to 12 digits.";
}
if (empty($skills)) {
    $errors[] = "Select at least one skill.";
}
if (!in_array($state, ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"], true)) {
    $errors[] = "Select a valid state.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application details | NovaCart Careers</title>
    <link rel="stylesheet" href="Styles/styles.css">
    <link rel="stylesheet" href="Styles/applystyle.css">
    <style>
        .confirmation { width: min(90%, 900px); margin: 40px auto; }
        .confirmation .application-card { width: 100%; padding: 0; overflow: hidden; border-radius: 18px; background: white; box-shadow: 0 12px 32px rgba(30, 57, 50, .12); }
        .application-top { padding: 32px 38px; color: white; background: linear-gradient(120deg, #004d35, #006241 65%, #168264); }
        .application-top h2 { margin: 0 0 6px; color: white; font-size: 2rem; }
        .application-top p { margin: 0; color: #e4f1eb; }
        .application-columns { display: grid; grid-template-columns: 1fr 1.6fr; }
        .application-side, .application-content { padding: 28px 32px; }
        .application-side { background: #f4f7f5; }
        .application-group { margin: 0 0 24px; padding: 0; }
        .application-group:last-child { margin-bottom: 0; }
        .application-group h3 { margin: 0 0 12px; padding: 0 0 8px; border-bottom: 2px solid #d4e9e2; color: #006241; font-size: 1rem; }
        .application-item { margin-bottom: 12px; overflow-wrap: anywhere; }
        .application-item strong { display: block; color: #687b74; font-size: .8rem; }
        .application-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .application-tags span { padding: 7px 11px; border-radius: 20px; color: #006241; background: #d4e9e2; }
        .actions { display: flex; gap: 12px; margin-top: 24px; }
        .actions a { padding: 12px 22px; border-radius: 30px; color: white; background: #006241; text-decoration: none; }
        .error { padding: 16px; border-radius: 8px; color: #842029; background: #f8d7da; }

        @media (max-width: 680px) {
            .application-columns { grid-template-columns: 1fr; }
            .application-top { padding: 26px 24px; }
            .application-side, .application-content { padding: 24px; }
            .actions { flex-direction: column; }
            .actions a { text-align: center; }
        }

        @page {
            size: A4;
            margin: 9mm;
        }

        @media print {
            html, body {
                background: white !important;
                font-size: 9pt;
            }

            header {
                padding: 0 0 10px !important;
                color: #1e3932 !important;
                background: white !important;
                text-align: center;
            }

            header::after {
                display: none !important;
            }

            header h1 {
                font-size: 20pt !important;
            }

            header p {
                color: #555 !important;
                font-size: 10pt !important;
            }

            .confirmation {
                width: 100% !important;
                margin: 8px auto !important;
            }

            .confirmation > h2 {
                margin: 0 0 4px !important;
                font-size: 15pt !important;
            }

            .confirmation > p {
                margin: 0 0 10px !important;
            }

            .confirmation .application-card {
                border: 1px solid #dce7e1;
                box-shadow: none;
                break-inside: avoid;
            }

            .application-top {
                padding: 14px 22px;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .application-top h2 {
                font-size: 19pt;
            }

            .application-columns {
                grid-template-columns: 1fr 1.6fr;
            }

            .application-side, .application-content {
                padding: 14px 20px;
            }

            .application-side {
                background: #f4f7f5 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .application-group {
                margin-bottom: 12px;
                break-inside: avoid;
            }

            .application-group h3 {
                margin-bottom: 6px;
                padding-bottom: 4px;
                font-size: 9pt;
            }

            .application-item {
                margin-bottom: 6px;
                font-size: 9pt;
            }

            .application-item strong {
                font-size: 7.5pt;
            }

            .application-tags {
                gap: 5px;
            }

            .application-tags span {
                padding: 4px 8px;
                background: #d4e9e2 !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .actions, footer {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>NovaCart Careers</h1>
    <p>Application details</p>
</header>

<main class="confirmation">
<?php if ($errors): ?>
    <h2>Please check your information</h2>
    <div class="error">
        <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= clean($error) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
    <div class="actions">
        <a href="apply.html">Return to form</a>
    </div>
<?php else: ?>
    <h2>Review your information</h2>
    <p>Please check your details before confirming.</p>

    <div class="application-card">
        <div class="application-top">
            <h2><?= $firstname ?> <?= $lastname ?></h2>
            <p><?= $position ?> · Reference <?= $jobref ?></p>
        </div>

        <div class="application-columns">
            <div class="application-side">
                <section class="application-group">
                    <h3>Contact</h3>
                    <div class="application-item"><strong>Email</strong><?= $email ?></div>
                    <div class="application-item"><strong>Phone</strong><?= $phone ?></div>
                </section>

                <section class="application-group">
                    <h3>Personal details</h3>
                    <div class="application-item"><strong>Date of birth</strong><?= $dob ?></div>
                    <div class="application-item"><strong>Gender</strong><?= $gender ?></div>
                </section>

                <section class="application-group">
                    <h3>Address</h3>
                    <div class="application-item"><strong>Street</strong><?= $street ?></div>
                    <div class="application-item"><strong>Suburb / Town</strong><?= $suburb ?></div>
                    <div class="application-item"><strong>State / Postcode</strong><?= $state ?> <?= $postcode ?></div>
                </section>
            </div>

            <div class="application-content">
                <section class="application-group">
                    <h3>Position</h3>
                    <div class="application-item"><strong>Applied for</strong><?= $position ?></div>
                    <div class="application-item"><strong>Job reference</strong><?= $jobref ?></div>
                </section>

                <section class="application-group">
                    <h3>Skills</h3>
                    <div class="application-tags">
                    <?php foreach ($skills as $skill): ?>
                        <span><?= $skill ?></span>
                    <?php endforeach; ?>
                    </div>
                </section>

                <?php if ($otherskills !== ""): ?>
                    <section class="application-group">
                        <h3>Other skills</h3>
                        <p><?= nl2br($otherskills) ?></p>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="actions">
        <a href="apply.html">Go back and edit</a>
        <a href="javascript:window.print()">Print</a>
    </div>
<?php endif; ?>
</main>

<footer>&copy; 2026 NovaCart. All rights reserved.</footer>
</body>
</html>