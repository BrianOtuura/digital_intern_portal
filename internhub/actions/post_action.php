<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../post.php');
    exit;
}

// ── Collect and sanitize inputs ───────────────────────────────────────────
$company  = trim($_POST['company']  ?? '');
$title    = trim($_POST['title']    ?? '');
$contact  = trim($_POST['contact']  ?? '');
$location = trim($_POST['location'] ?? 'Kampala');
$field    = trim($_POST['field']    ?? 'Other');
$industry = trim($_POST['industry'] ?? '');
$duration = (int)($_POST['duration'] ?? 3);
$paid     = trim($_POST['paid']     ?? 'unpaid');
$slots    = max(1, (int)($_POST['slots'] ?? 1));
$desc     = trim($_POST['description']    ?? '');
$deadline = trim($_POST['deadline'] ?? '');
$postedBy = $_SESSION['user_id'] ?? null;

// ── Validate required fields ──────────────────────────────────────────────
if (!$company || !$title || !$contact || !$desc) {
    header('Location: ../post.php?error=' . urlencode('Please fill in all required fields.'));
    exit;
}

if (!filter_var($contact, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../post.php?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

if (!$deadline) {
    $deadline = date('Y-m-d', strtotime('+3 months'));
}

// ── Process responsibilities and requirements ─────────────────────────────
$responsibilities = implode('|', array_filter(
    array_map('trim', explode("\n", $_POST['responsibilities'] ?? ''))
));
$requirements = implode('|', array_filter(
    array_map('trim', explode("\n", $_POST['requirements'] ?? ''))
));

if (!$responsibilities) $responsibilities = 'See job description.';
if (!$requirements)     $requirements     = 'See job description.';

// ── Generate logo initials and color ─────────────────────────────────────
$words     = explode(' ', $company);
$logo      = strtoupper(
    substr($words[0], 0, 1) .
    (isset($words[1]) ? substr($words[1], 0, 1) : substr($words[0], 1, 1))
);
$colors    = ['#F5A623','#64B5F6','#81C784','#CE93D8','#FF8A65','#4DB6AC','#F06292','#AED581'];
$logoColor = $colors[abs(crc32($company)) % count($colors)];

// ── Build tags ────────────────────────────────────────────────────────────
$paidLabel = ['paid' => 'Paid', 'stipend' => 'Stipend', 'unpaid' => 'Unpaid'];
$tags = implode(',', array_filter([
    $field,
    $industry,
    $paidLabel[$paid] ?? 'Unpaid',
    $duration . ' months'
]));

// ── Stipend display text ──────────────────────────────────────────────────
$stipendMap = [
    'paid'    => 'Paid — amount on request',
    'stipend' => 'Stipend provided',
    'unpaid'  => 'Unpaid / Voluntary'
];
$stipend = $stipendMap[$paid];

// ── Insert into database ──────────────────────────────────────────────────
try {
    $stmt = $pdo->prepare("
        INSERT INTO internships
            (title, company, logo, logo_color, location, field,
             duration, paid, stipend, deadline, slots,
             description, responsibilities, requirements,
             contact, tags, posted_by)
        VALUES
            (:title, :company, :logo, :logo_color, :location, :field,
             :duration, :paid, :stipend, :deadline, :slots,
             :description, :responsibilities, :requirements,
             :contact, :tags, :posted_by)
    ");

    $stmt->execute([
        ':title'            => $title,
        ':company'          => $company,
        ':logo'             => $logo,
        ':logo_color'       => $logoColor,
        ':location'         => $location,
        ':field'            => $field,
        ':duration'         => $duration,
        ':paid'             => $paid,
        ':stipend'          => $stipend,
        ':deadline'         => $deadline,
        ':slots'            => $slots,
        ':description'      => $desc,
        ':responsibilities' => $responsibilities,
        ':requirements'     => $requirements,
        ':contact'          => $contact,
        ':tags'             => $tags,
        ':posted_by'        => $postedBy,
    ]);

    header('Location: ../post.php?success=1');
    exit;

} catch (PDOException $e) {
    header('Location: ../post.php?error=' . urlencode('Something went wrong. Please try again.'));
    exit;
}
?>